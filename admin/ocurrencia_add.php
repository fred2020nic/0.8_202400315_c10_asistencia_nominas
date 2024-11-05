<?php
	include 'includes/session.php';

	if(isset($_POST['add'])){
		$name = $conn->real_escape_string($_POST['name']);
		$description = $conn->real_escape_string($_POST['description']);

		$sql = "INSERT INTO ocurrencia (name, description) VALUES ('$name', '$description')";
		if($conn->query($sql)){
			$_SESSION['success'] = 'Ocurrencia agregada exitosamente';
		}
		else{
			$_SESSION['error'] = $conn->error;
		}
	}	
	else{
		$_SESSION['error'] = 'Completa el formulario de agregar primero';
	}

	header('location: ocurrencia.php');
?>
