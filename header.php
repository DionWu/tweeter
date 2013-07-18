<?php

	/* initialize PDO object & check for error in connection */
	try {
		$pdo = new PDO('mysql:host=localhost; dbname=tweeter', 'root', '');
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	} catch (Exception $e) {
		echo 'ERROR: ' . $e->getMessage();
	};

?>