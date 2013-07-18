<html>
<head>
<script type="text/javascript" src="function.js"> </script>
</head>
<body>


<?php
	session_start();
	include_once 'header.php';
	include_once 'function.php';

	/* Define other username variable & id */
	if (isset($_GET['query'])) {
		$others_profile_username = $_GET['query'];
	} else if (isset($_GET['profile_username'])) {
		$others_profile_username = $_GET['profile_username'];
	}

	$fetch_id_result = fetch_others_user_id($pdo, $others_profile_username);
	$others_profile_id = $fetch_id_result['id'];

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



<!-- Search Bar -->
<div class="search">
	<form action="others_profile.php" method="get" >
		Search for someone! <br>
		<input type="text" name="query">
		<input type="submit" value="Search!">
	</form>
</div>




<h1> Profile Page of @<?php echo $others_profile_username ?> </h1>




<!-- OTHERS TWEETER FEED --> 
<div class="feed_container">
	<h3> Your Tweeter Feed! </h3>
	<?php
		$all_tweets = show_tweets($pdo, $others_profile_id, false);
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
				Written By: <a href=" .$url. "?profile_username=" .$value['username'] .">" .
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

</body>
</html>