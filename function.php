<?php

	function register($pdo) {
		/* Define variables & escape */
		$firstname = $_POST['firstname'];
		$lastname = $_POST['lastname'];
		$username = $_POST['username'];

		/* salt and hash password before entering in sql table */
		$unhashed_password = $_POST['password'];
		$password = crypt($unhashed_password);

		$email = $_POST['email'];

		/* Insert variables into SQL table */
		$reg_insert_stmt = "INSERT INTO `login_info` (`firstname`, `lastname`, `username`, `password`, `email`) VALUES (:firstname, :lastname, :username, :password, :email)";
		$reg_insert = $pdo->prepare($reg_insert_stmt);
		$reg_insert_result = $reg_insert->execute(array(
				':firstname' => $firstname,
				':lastname' => $lastname,
				':username' => $username,
				':password' => $password,
				':email' => $email
		));

		/* Check if inserted correctly & if not, output error */
		if (!$reg_insert_result) {
			echo $pdo->errorInfo();
			exit();
		} else {
			$fetch_user_id_stmt = "SELECT id, username FROM login_info WHERE username = :username";
			$fetch_user_id = $pdo->prepare($fetch_user_id_stmt);
			$fetch_user_id->bindParam(':username', $username);
			$fetch_user_id->execute();

			while ($row = $fetch_user_id->fetch(PDO::FETCH_ASSOC)) {
				$_SESSION['user_id'] = $row['id'];
				$_SESSION['username'] = $row['username'];
				$_SESSION['identify'] = array('user_id' => $_SESSION['user_id'], 'username' => $_SESSION['username']);
				return $_SESSION['identify'];
			};
		};
	}


	function login($pdo) {
		/* POST & assign variables form login page */
		$input_username = $_POST['input_username'];
		$input_password = $_POST['input_password'];

		/* Fetch password to compare to user inputted password */
		$fetch_password_stmt = "SELECT `username`, `password`, `id` FROM `login_info` WHERE `username`= :username";
		$fetch_password = $pdo->prepare($fetch_password_stmt);
		$fetch_password->execute(array(':username'=> $input_username));

		while ($row = $fetch_password->fetch(PDO::FETCH_ASSOC)) {
			if(crypt($input_password, $row['password']) !== $row['password']) {
				echo "PASSWORD DENIED";
				exit();
			} else {
				$_SESSION['user_id'] = $row['id'];
				$_SESSION['username'] = $row['username'];
				$_SESSION['identify'] = array('user_id' => $_SESSION['user_id'], 'username' => $_SESSION['username']);
				return $_SESSION['identify'];
			}
		};
	}

	function add_tweet($pdo, $user_id, $username) {
		/* Define variables */
		$tweet = $_POST['tweet'];
		$time = date('M j, o g:i a e');
		$unixtime = time();

		/* Add tweet to table `tweets` */
		$add_tweet_stmt = "INSERT INTO tweets (user_id, username, tweet, time, unixtime) VALUES (:user_id, :username, :tweet, :time, :unixtime)";
		$add_tweet = $pdo->prepare($add_tweet_stmt);
		$add_tweet_result = $add_tweet->execute(array(
							':user_id' => $user_id,
							':username' => $username,
							':tweet' => $tweet,
							':time' => $time,
							':unixtime' => $unixtime
		));

		if(!$add_tweet_result) {
			printf("Tweet failed to be sent because : %s", $pdo->error);
		};
	}

	function add_tweet_check($pdo) {
		add_tweet($pdo, $_SESSION['user_id'], $_SESSION['username']);
		header('Location:' . $_SERVER['PHP_SELF']);
		exit();
		
	}


	function find_followers($pdo, $user_id, $others) {
		/* Checking if to find user_ids from people you follow */
		if ($others) {
			/* Find who you are following first */
			$find_following_stmt = "SELECT following_id FROM followers WHERE user_id = :user_id";
			$find_following = $pdo->prepare($find_following_stmt);
			$find_following->bindParam(':user_id', $user_id);
			$find_following_result = $find_following->execute();

			/* REMEMBER THAT FETCH / FETCHALL RETURNS ASSOC. ARRAYS */
			$result_array = $find_following->fetchAll();
			$following_array = array();
			foreach($result_array as $key => $value) {
				array_push($following_array, $value['following_id']);
			};
			/*add your own tweets */
			array_push($following_array, $user_id);

		} else {
			/* Define array containing just user_id to display only his/her tweets */
			$following_array = array($user_id);
		};

		return $following_array;
	}

	function show_tweets($pdo, $user_id, $others) {
		/* Fetches tweets from defined users*/

		$following_array = find_followers($pdo, $user_id, $others);
		/* LEARNING LESSON : When preparing statements and bindParaming, you must have a :value or ? for EACH parameter you want to bind! You can't just bind an array into one :value or ?

		Thus, I used a str_repeater to generate the necessary ? and then you can just directly execute($array) which will fill in the ?. Note, I think the $array has to be simple numeric */

		$show_tweets_stmt = "SELECT user_id, username, tweet, time, unixtime FROM tweets WHERE user_id IN (" . str_repeat('?,', count($following_array) - 1) . "?) ORDER BY unixtime DESC";
		$show_tweets = $pdo->prepare($show_tweets_stmt);
		$show_tweets_result = $show_tweets->execute($following_array);


		if(!$show_tweets_result) {
			printf("Tweets failed to be retrieved! <br> %s", $pdo->error);
		} else {
			$tweets_array = $show_tweets->fetchAll();
			return $tweets_array; 
		};
	}


	/* Used to fetch id to show_tweets of other users ONLY on their profile pages */
	function fetch_others_user_id($pdo, $others_profile_username) {
		$fetch_id_stmt = "SELECT id FROM login_info WHERE username=:username";
		$fetch_id = $pdo->prepare($fetch_id_stmt);
		$fetch_id->bindParam(':username', $others_profile_username);
		$fetch_id_result = $fetch_id->execute();

		if (!$fetch_id_result) {
			printf("Could not fetch id! <br> %s", $pdo->error);
		} else {
			$fetch_id_array = $fetch_id->fetch(PDO::FETCH_ASSOC);
			return $fetch_id_array;
		};
	}

	/* Determine whether to display a follow/unfollow button or none at all in the case of your own tweet */
	function det_follow_button($pdo, $user_id, $following_id) {
		if ($user_id === $following_id) {
			return;
		} else{
			$det_button_stmt = "SELECT user_id, following_id FROM followers WHERE user_id = :user_id AND following_id = :following_id";
			$det_button = $pdo->prepare($det_button_stmt);
			$det_button_result = $det_button-> execute(array(
				'user_id' => $user_id,
				'following_id' => $following_id
			));

			if(!$det_button_result) {
				printf($pdo->error);
			} else {
				$follow_or_not = $det_button->fetchAll();
				if (empty($follow_or_not)) {
					return 'Follow';
				} else {
					return 'Unfollow';
				};
			}
		}
	}


	function follow($user_id, $following_id) {
		/* Start new PDO instance */
		/* LEARNING LESSON :  "You can’t pass a resource, like a PDO instance, to a JavaScript function. It doesn’t exist by the time the HTML is rendered. You’ll need to create the PDO instance in the PHP script your JavaScript function calls." */

		try {
		$pdo = new PDO('mysql:host=localhost; dbname=tweeter', 'root', '');
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		} catch (Exception $e) {
			echo "Error: " . $e->getMessage();
		}

		/* add to following table */
		$follow_stmt = "INSERT INTO followers (user_id, following_id) VALUES (:user_id, :following_id)";
		$follow = $pdo->prepare($follow_stmt);
		$follow_result = $follow->execute(array(
			':user_id' => $user_id,
			':following_id' => $following_id
		));

		if (!$follow_result) {
			printf("Failed to follow! <br> %s", $pdo->error);
		} else {
			echo "You successfully followed!";
		};
	}


	function unfollow($user_id, $following_id){
		/* intialize PDO */
		try {
			$pdo = new PDO('mysql:host=localhost; dbname=tweeter', 'root', '');
			$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		} catch (Exception $e) {
			echo "Error: " . $e->getMessage();
		}

		/* Unfollow */
		$unfollow_stmt = "DELETE FROM followers WHERE user_id = :user_id AND following_id = :following_id";
		$unfollow = $pdo->prepare($unfollow_stmt);
		$unfollow_result = $unfollow->execute(array(
			'user_id' => $user_id,
			'following_id' => $following_id
		));

		if (!$unfollow_result) {
			printf("Failed to follow! <br> %s", $pdo->error);
		} else {
			echo "You successfully unfollowed!";
		};
	}

	/* Switch/Case that allows ajax to pick which function to run */
	if (isset($_GET['function'])) {
		switch($_GET['function']) {
			case 'follow':
				$user_id = $_GET['user_id'];
				$following_id = $_GET['following_id'];
				follow($user_id, $following_id);
				break;
			case 'unfollow':
				$user_id = $_GET['user_id'];
				$following_id = $_GET['following_id'];
				unfollow($user_id, $following_id);
				break;
		}
	}

	/* Determines whether to call follow_alert() or unfollow_alert()function */
	function det_follow_alert($det_button_result) {
		if ($det_button_result === 'Follow') {
			return 'follow_alert';
		} else if ($det_button_result === 'Unfollow') {
			return 'unfollow_alert';
		}
	}


	/* Function for suggesting people to follow */
	function suggestion($pdo, $user_id) {
		$following_array = find_followers($pdo, $user_id, true);
		$suggestion_stmt = "SELECT MAX(id) as max_id, user_id, username FROM tweets WHERE NOT user_id IN (" . str_repeat('?,' , count($following_array) - 1) . "?) GROUP BY user_id ORDER BY max_id DESC LIMIT 3";
		$suggestion = $pdo->prepare($suggestion_stmt);
		$suggestion_result = $suggestion->execute($following_array);

		if(!$suggestion_result) {
			echo "We could not find any suggestions for you! " . $pdo->error;
		} else {
			$suggestion_array = $suggestion->fetchAll();
			return $suggestion_array;
		}
	}

?>