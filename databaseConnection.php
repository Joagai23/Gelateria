<?php

	$hostname = "localhost";
	$user = "jorge";
	$password = "contraseña";
	$db = "ice_cream_shop";

	$mysqli = new mysqli($hostname, $user, $password, $db);

	if($mysqli->connect_errno != 0){
		echo "Error trying to connect to the database <br>";
		die('Connect Error: ' . $mysqli->connect_errno);
	}

	function getMySQLIReference(){
		if($mysqli != null){

			return $mysqli;
		}
	}
?>