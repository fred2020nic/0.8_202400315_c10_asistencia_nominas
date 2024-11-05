<?php
	include 'includes/session.php';

	if(isset($_POST['delete'])){
		$id = $conn->real_escape_string($_POST['id']);

		$sql = "DELETE FROM ocurrencia WHERE id = '$id'";
		if($conn->query($sql)){
			$_SESSION['success'] = 'Ocurrencia eliminada exitosamente';
		}
		else{
			$_SESSION['error'] = $conn->error;
		}
	}
	else{
		$_SESSION['error'] = 'Selecciona una ocurrencia para eliminar primero';
	}

	header('location: ocurrencia.php');
?>
