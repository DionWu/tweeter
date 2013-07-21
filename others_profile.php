<html>
<head>
	<link rel="stylesheet" type="text/css"  href="tweeter.css" />
</head>
<body>


<?php
	session_start();
	include_once 'header.php';
	include_once 'function.php';

	/* Define other username variable & id */
	$others_username = null;
	if (!isset($_SESSION['other_username'])) {
		$_SESSION['other_username'] = $_GET['query'];
	} else {
		$others_username = $_SESSION['other_username'];
	}
	$fetch_id_result = fetch_others_user_id($pdo, $others_username);
	$others_id = $fetch_id_result['id'];

?>

<div class="navbar">
	<ul>
		<li><a href="main_page.php"> Feed </a></li>
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


<!-- SEARCH BAR -->
<div class="search_container">
	<form  action="query.php" method="get" >
		Search for people to Follow! <br>
		<input class = "autosuggest" type="text" name="query">
		<input type="submit" value="Search!">
	</form>
	<div class="dropdown">
		<ul class="result">		</ul>
	</div>
</div>


<div class="hello">
	<h2> Profile Page of @<?php echo $others_username ?> 
	<?php
		$det_button_result = det_follow_button($pdo, $_SESSION['user_id'], $others_id);
		/* call on det_follow_alert() to determine what function to call */
		$alert_type = det_follow_alert($det_button_result);

		/* Show a Follow/Unfolow button */
		if ($det_button_result) {
			echo "<button type='submit' name='follow_button' onclick='" . $alert_type ."(" . $_SESSION['user_id'] . ", " . $others_id . ")'; >" . $det_button_result . "</button>";
		}
	?>
</h2>
</div>



<!-- Add Tweet -->
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





<!-- Suggest Followers box -->
<div class="suggestion_container">
	We think you'd love these Tweeters 
	<div class = "suggestion_box">
		<?php
			$suggestion_array = suggestion($pdo, $_SESSION['user_id']);
			foreach ($suggestion_array as $key => $value) {
		?>
			<div class="suggestion">
				<?php echo "<a href='others_profile.php?query=" . $value['username'] . "'class='suggestion_link'>" . $value['username'] . "</a>" . 
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




<!-- OTHERS TWEETER FEED --> 
<div class="feed_container">
	<h3> Tweeter Feed of @<?php echo $others_username ?> </h3>
	<?php
		$all_tweets = show_tweets($pdo, $others_id, false);
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
				/* Convert to users timezone */
				$users_timezone = new DateTimeZone($_SESSION['timezone']);
				$date = new DateTime($value['time']);
				$date->setTimeZone($users_timezone);
				$new_date = $date->format('M j, o g:i a e');

				/* Actual tweet embodied here */	
				echo $value['tweet'] . "<br> 
				Written By: <a href=" .$url. "?query=". $value['username'] .">" .
				$value['username'] . 
				"</a> on " . $new_date . "<br>";
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





<script type="text/javascript" src="../jquery.js"> </script>
<script type="text/javascript" src="function.js"> </script>

</body>
</html>