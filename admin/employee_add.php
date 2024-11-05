<?php
	include 'includes/session.php';

	if(isset($_POST['add'])){
		$firstname = $_POST['firstname'];
		$lastname = $_POST['lastname'];
		$address = $_POST['address'];
		$birthdate = $_POST['birthdate'];
		$contact = $_POST['contact'];
		$gender = $_POST['gender'];
		$position = $_POST['position'];
		$schedule = $_POST['schedule'];

		$curp = $_POST['curp'];
		$rfc = $_POST['rfc'];
		$nss = $_POST['nss'];
		$tipo_sangre = $_POST['tipo_sangre'];
		$domicilio = $_POST['domicilio'];
		$colonia = $_POST['colonia'];
		$cp = $_POST['cp'];
		// $puesto = $_POST['puesto'];
		$fecha_ingreso = $_POST['fecha_ingreso'];
		$fecha_alta_imss = $_POST['fecha_alta_imss'];
		$tel_emergencia_1 = $_POST['tel_emergencia_1'];
		$parentesco_1 = $_POST['parentesco_1'];
		$tel_emergencia_2 = $_POST['tel_emergencia_2'];
		$parentesco_2 = $_POST['parentesco_2'];
		// $schedule = $_POST['schedule'];



		
		$extras = $_POST['extras'];
		$filename = $_FILES['photo']['name'];
		if(!empty($filename)){
			move_uploaded_file($_FILES['photo']['tmp_name'], '../images/'.$filename);	
		}
		//creating employeeid
		$letters = '';
		$numbers = '';
		foreach (range('A', 'Z') as $char) {
		    $letters .= $char;
		}
		for($i = 0; $i < 10; $i++){
			$numbers .= $i;
		}
		$employee_id = substr(str_shuffle($letters), 0, 3).substr(str_shuffle($numbers), 0, 9);
		//
		$sql = "INSERT INTO employees (employee_id, firstname, lastname, address, birthdate, contact_info, gender, position_id, schedule_id, curp, rfc, nss, tipo_sangre, domicilio, colonia, cp, fecha_ingreso, fecha_alta_imss, tel_emergencia_1, parentesco_1, tel_emergencia_2, parentesco_2, extras, photo, created_on) VALUES ('$employee_id', '$firstname', '$lastname', '$address', '$birthdate', '$contact', '$gender', '$position', '$schedule', '$curp', '$rfc', '$nss', '$tipo_sangre', '$domicilio', '$colonia', '$cp', '$fecha_ingreso', '$fecha_alta_imss', '$tel_emergencia_1', '$parentesco_1', '$tel_emergencia_2', '$parentesco_2' , '$extras', '$filename', NOW())";
		if($conn->query($sql)){
			$_SESSION['success'] = 'Empleado añadido satisfactoriamente';
		}
		else{
			$_SESSION['error'] = $conn->error;
		}

	}
	else{
		$_SESSION['error'] = 'Fill up add form first';
	}

	header('location: employee.php');
?>