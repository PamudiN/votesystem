<?php
	session_start();
	include 'includes/conn.php';

	//recaptcha
	$captcha = $_POST['g-recaptcha-response'];
	$response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=6Ldc18glAAAAAF7ygxFzJzKLmqaynFp4AXmesnvk&response=".$captcha);
	$decoded_response = json_decode($response, true);
	if(!$decoded_response['success']){
		$_SESSION['error'] = 'Invalid captcha. Please try again.';
		header('location: index.php');//if captcha not validate again ridirect to index page
		exit;
	}//end of recaptcha

	if(isset($_POST['login'])){
		$voter = $_POST['voter'];
		$password = $_POST['password'];

		$sql = "SELECT * FROM voters WHERE voters_id = '$voter'";
		$query = $conn->query($sql);

		if($query->num_rows < 1){
			$_SESSION['error'] = 'Cannot find voter with the ID';
		}
		else{
			$row = $query->fetch_assoc();
			if(password_verify($password, $row['password'])){
				$_SESSION['voter'] = $row['id'];
			}
			else{
				$_SESSION['error'] = 'Incorrect password';
			}
		}
		
	}
	else{
		$_SESSION['error'] = 'Input voter credentials first';
	}

	header('location: index.php');

?>