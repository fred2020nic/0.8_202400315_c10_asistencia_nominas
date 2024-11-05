<?php
	include 'includes/session.php';

	if(isset($_POST['delete'])){
		$id = $_POST['id'];
		$sql = "DELETE FROM solicitudpermiso WHERE id = '$id'";
		if($conn->query($sql)){
			$_SESSION['success'] = 'Solicitud de Permiso eliminada con éxito';
		}
		else{
			$_SESSION['error'] = $conn->error;
		}
	}
	else{
		$_SESSION['error'] = 'Seleccione el elemento a eliminar primero';
	}

	header('location: permiso.php');
	
?>
