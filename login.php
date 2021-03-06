<?php

	session_start();
		
	if ((!isset($_POST['login'])) || (!isset($_POST['password']))) {
		header('Location: index.php');
		exit();
	}
	
	require_once "connect.php";
	
	$connection = new mysqli($host, $db_user, $db_password, $db_name);
	
	if ($connection->connect_errno!=0){
		echo "Error: ".$connection->connect_errno;
	} else {
		$login = $_POST['login'];
		$pass = $_POST['password'];
		
		$login = htmlentities($login,ENT_QUOTES,"UTF-8");
		
		if($result = $connection->query(sprintf("SELECT * FROM users WHERE username='%s'",
		mysqli_real_escape_string($connection,$login)))){
			$numbersOfUsers = $result->num_rows;
			if ($numbersOfUsers > 0) {
				$row = $result->fetch_assoc();
				
				if(password_verify($pass, $row['password'])) {
					$_SESSION['logged'] = true;
				
					$_SESSION['logged_id'] = $row['id'];
					$_SESSION['logged_username'] = $row['username'];
					unset($_SESSION['error']);
					$result->free_result();
					header('Location: mainpage.php');
				} else {
				$_SESSION['error'] = '<span style="color:red">Login or password is incorrect!</span>';
				header('Location: index.php');
				}	
			} else {
				$_SESSION['error'] = '<span style="color:red">Login or password is incorrect!</span>';
				header('Location: index.php');
			}
		}
		$connection->close();
	}

?>