<html>
<head>
	<link rel="stylesheet" type="text/css"  href="tweeter.css" />

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



<div class="search_results_container">
	<h1> Your search results! </h1>
	<?php 
	if (isset($_GET['query'])) {
		$search_results = search($pdo, $_GET['query']);
		foreach($search_results as $key => $value) {
	?>
			<div class="follow_unfollow_button">
			<!-- Call on det_follow_button() to find value for button -->
			<?php
				$det_button_result = det_follow_button($pdo, $_SESSION['user_id'], $value['id']);
				/* call on det_follow_alert() to determine what function to call */
				$alert_type = det_follow_alert($det_button_result);

				/* Show a Follow/Unfolow button */
				if ($det_button_result) {
					echo "<a href='others_profile.php?profile_username=" . $value['username'] . "'>" . 
						$value['username'] . "</a> <br>" .
						"<button type='submit' 
							name='follow_button' 
							onclick='" . $alert_type ."(" . $_SESSION['user_id'] . ", " . $value['id'] . ")'; >" . 
							$det_button_result . 
						"</button>";
				}
			?>
			</div>
		<?php
		} 
	}
	?>
</div>




	
	<script type="text/javascript" src="../jquery.js"> </script>
	<script type="text/javascript" src="function.js"> </script>

</body>
</html>




