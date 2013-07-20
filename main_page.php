<html>
<head>
	<script type="text/javascript" src="function.js"> </script>
</head>
<body>


<?php
	session_start();
	include_once 'header.php';
	include_once 'function.php';

/* Determine if path to main screen is from login or registering */
	if (isset($_POST['input_username']) && isset($_POST['input_password'])) {
		login($pdo);
		$_SESSION['user_id'] = $_SESSION['identify']['user_id'];
		$_SESSION['username'] = $_SESSION['identify']['username'];
	} else if (isset($_POST['firstname']) && isset($_POST['lastname']) && isset($_POST['username']) && isset($_POST['password']) && isset($_POST['email'])) {
		register($pdo);
		$_SESSION['user_id'] = $_SESSION['identify']['user_id'];
		$_SESSION['username'] = $_SESSION['identify']['username'];
	};
?>


<div class="navbar">
	<ul>
		<li><a href="#"> Feed </a></li>
		<li><a href="profile.php"> <?php echo $_SESSION['username'] ?> </a></li>
		<li> 
			<div class="logout_button">
				<form action="login_page.php">
					<input type="submit" value="Logout">
				</form>
			</div>
		</li>
	</ul>
</div>





<div class="hello">
	<p> Welcome back to tweeter<p>
</div>





<!-- ADD TWEET -->
<?php
	if (isset($_POST['tweet'])) {
		add_tweet_check($pdo);
	};
?>
<div class="post_tweet">
	<form method="post">
		Tweet Something! <br>
		<input type="text" name="tweet">
		<input type="submit" value="Tweet!">
	</form>
</div>




<!-- SEARCH BAR -->
<div class="search">
	<form action="others_profile.php" method="get" >
		Search for people to Follow! <br>
		<input type="text" name="query">
		<input type="submit" value="Search!">
	</form>
</div>


<!-- Suggest Followers box -->
<div class="follower_suggestions">

</div>

<!-- COMBINED TWEETER FEED -->
<div class="feed_container">
	<h3> Your Tweeter Feed! </h3>
	<?php
		$all_tweets = show_tweets($pdo, $_SESSION['user_id'], true);
		if (count($all_tweets)) {
			foreach($all_tweets as $key => $value) {
	?>
	<div class="tweet">
		<?php
				/* Determine which type of profile page to direct user to */
				if ($value['username'] === $_SESSION['username']) {
						$url = 'profile.php';
					} else {
						$url = 'others_profile.php';
					};

				/* Actual tweet embodied here */	
				echo $value['tweet'] . "<br> 
				Written By: <a href=" .$url. "?profile_username=". $value['username'] .">" .
				$value['username'] . 
				"</a> on " . $value['time'] . "<br>";
		?>

		<div class="follow_unfollow_button">
			<!-- Call on det_follow_button() to find value for button -->
			<?php
				$det_button_result = det_follow_button($pdo, $_SESSION['user_id'], $value['user_id']);
				/* call on det_follow_alert() to determine what function to call */
				$alert_type = det_follow_alert($det_button_result);

				/* Show a Follow/Unfolow button */
				if ($det_button_result) {
					echo "<button type='submit' name='follow_button' onclick='" . $alert_type ."(" . $_SESSION['user_id'] . ", " . $value['user_id'] . ")'; >" . $det_button_result . "</button>";
				}
			?>
		</div>

	</div>
	<?php
			};
		} else {
			echo "You haven't posted anything yet!";
		};
	?>
</div>

<div class="suggestion_container">
	<h3> We think you'd love these Tweeters </h3>
	<h4> Follow them here! </h4>
	<div class = "suggestion_box">
		<?php
			$suggestion_array = suggestion($pdo, $_SESSION['user_id']);
			foreach ($suggestion_array as $key => $value) {
		?>
			<div class="suggestion">
				<?php echo "<a href='others_profile.php?profile_username=" . $value['username'] . "'>" . $value['username'] . "</a>" . 
					"<button type='submit' 
						name='follow_button' 
						onclick='follow_alert(" . $_SESSION['user_id'] . ", " . $value['user_id'] . ")';> 
						Follow 
					</button>"
				?>
			</div>
		<?php
			};
		?>
	</div>
</div>

	
</body>
</html>

