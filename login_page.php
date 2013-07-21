<html>
<head>
</head>
<body>


<div class = "login_block">
	<form action="main_page.php" method="post">
	<strong>Login</strong><br>
	Username: <input type="text" name="input_username"><br>
	Password: <input type="password" name="input_password"><br>
	<input type="submit" value="Submit">
	</form>
</div>

<div class = "registration_block">
	<form action="main_page.php" method="post">
	<strong>Register Here!</strong><br>
	First Name: <input type = "text" name="firstname"><br>
	Last Name: <input type = "text" name="lastname"><br>
	Username: <input type = "text" name="username"><br>
	Password: <input type = "password" name="password"><br>
	Email: <input type = "text" name="email"><br>
	Timezone: 
	<select name="timezone">
		<?php 
			$tzlist = DateTimeZone::listIdentifiers(DateTimeZone::ALL);
		?>
		<option value=""> -- Select -- </option>
		<?php
			foreach ($tzlist as $timezone) {
				echo "<option value='" . $timezone . "'>" . $timezone . "</option>";
			}
		?>
	</select> <br>
	<input type="submit" value="Submit">
	</form>
</div>

</body>
</html>