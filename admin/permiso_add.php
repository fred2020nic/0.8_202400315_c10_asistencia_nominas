<?php
	include 'includes/session.php';

	if(isset($_POST['add'])){
		$employee = $_POST['employee'];
		$tipo_permiso = $_POST['tipo_permiso'];
		$motivo = $_POST['motivo'];
		$observaciones = $_POST['observaciones'];
		
		$sql = "SELECT * FROM employees WHERE employee_id = '$employee'";
		$query = $conn->query($sql);
		if($query->num_rows < 1){
			$_SESSION['error'] = 'Employee not found';
		}
		else{
			$row = $query->fetch_assoc();
			$employee_id = $row['id'];
			$sql = "INSERT INTO solicitudpermiso (employee_id, fecha_solicitud, tipo_permiso, motivo, observaciones) VALUES ('$employee_id', NOW(), '$tipo_permiso', '$motivo', '$observaciones')";
			if($conn->query($sql)){
				$_SESSION['success'] = 'Solicitud de Permiso added successfully';
			}
			else{
				$_SESSION['error'] = $conn->error;
			}
		}
	}	
	else{
		$_SESSION['error'] = 'Fill up add form first';
	}

	header('location: permiso.php');

?>
