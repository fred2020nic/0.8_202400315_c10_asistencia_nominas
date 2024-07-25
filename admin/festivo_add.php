<?php
	include 'includes/session.php';

	if(isset($_POST['add'])){
		$holiday_date = $_POST['holiday_date'];
		$description = $_POST['description'];

		$sql = "INSERT INTO festivos (holiday_date, description) VALUES ('$holiday_date', '$description')";
		if($conn->query($sql)){
			$_SESSION['success'] = 'Festivo added successfully';
		}
		else{
			$_SESSION['error'] = $conn->error;
		}
	}	
	else{
		$_SESSION['error'] = 'Fill up add form first';
	}

	header('location: festivo.php');

?>