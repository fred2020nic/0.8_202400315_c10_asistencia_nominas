<?php
	include 'includes/session.php';

	if(isset($_POST['delete'])){
		$id = $_POST['id'];
		$sql = "DELETE FROM festivos WHERE id = '$id'";
		if($conn->query($sql)){
			$_SESSION['success'] = 'Festivo eliminada con éxito';
		}
		else{
			$_SESSION['error'] = $conn->error;
		}
	}
	else{
		$_SESSION['error'] = 'Seleccione el elemento para eliminar primero';
	}

	header('location: festivo.php');
	
?>