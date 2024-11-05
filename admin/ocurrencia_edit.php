<?php
	include 'includes/session.php';

	if(isset($_POST['edit'])){
		$id = $conn->real_escape_string($_POST['id']);
		$name = $conn->real_escape_string($_POST['name']);
		$description = $conn->real_escape_string($_POST['description']);

		$sql = "UPDATE ocurrencia SET name = '$name', description = '$description' WHERE id = '$id'";
		if($conn->query($sql)){
			$_SESSION['success'] = 'Ocurrencia actualizada satisfactoriamente';
		}
		else{
			$_SESSION['error'] = $conn->error;
		}
	}
	else{
		$_SESSION['error'] = 'Completa el formulario de edición primero';
	}

	header('location: ocurrencia.php');
?>
