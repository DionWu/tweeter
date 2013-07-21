<html>
<head>
	<script type="text/javascript" src="function.js"> </script>
</head>
<body>


<?php
	session_start();
	include_once 'header.php';
	include_once 'function.php';
?>

<div class="navbar">
	<ul>
		<li><a href="main_page.php"> Feed </a></li>
		<li><a href="#"> <?php echo $_SESSION['username'] ?> </a></li>
		<li> 
			<div class="logout_button">
				<form action="login_page.php">
					<input type="submit" value="Logout">
				</form>
			</div>
		</li>
	</ul>
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
	<form  action="query.php" method="get" >
		Search for people to Follow! <br>
		<input class = "autosuggest" type="text" name="query">
		<input type="submit" value="Search!">
	</form>
	<div class="dropdown">
		<ul class="result">		</ul>
	</div>
</div>



<h1> Profile Page of @<?php echo $_SESSION['username']  ?> </h1>




<!-- PERSONAL TWEETER FEED -->
<div class="feed_container">
	<h3> Your Tweeter Feed! </h3>
	<?php
		$all_tweets = show_tweets($pdo, $_SESSION['user_id'], false);
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