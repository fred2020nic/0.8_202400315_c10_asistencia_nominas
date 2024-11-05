<?php
include 'includes/session.php';

if(isset($_POST['edit'])){
    $empid = $_POST['id'];
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $address = $_POST['address'];
    $birthdate = $_POST['birthdate'];
    $contact = $_POST['contact'];
    $gender = $_POST['gender'];
    $position = $_POST['position'];
    $schedule = $_POST['schedule'];
    $extras = $_POST['extras'];

    $curp = $_POST['curp'];
    $rfc = $_POST['rfc'];
    $nss = $_POST['nss'];
    $tipo_sangre = $_POST['tipo_sangre'];
    $domicilio = $_POST['domicilio'];
    $colonia = $_POST['colonia'];
    $cp = $_POST['cp'];
    $fecha_ingreso = $_POST['fecha_ingreso'];
    $puesto = $_POST['puesto'];
    $fecha_alta_imss = $_POST['fecha_alta_imss'];
    $tel_emergencia_1 = $_POST['tel_emergencia_1'];
    $parentesco_1 = $_POST['parentesco_1'];
    $tel_emergencia_2 = $_POST['tel_emergencia_2'];
    $parentesco_2 = $_POST['parentesco_2'];

    $sql = "UPDATE employees SET 
            firstname = '$firstname', 
            lastname = '$lastname', 
            address = '$address', 
            birthdate = '$birthdate', 
            contact_info = '$contact', 
            gender = '$gender', 
            position_id = '$position', 
            schedule_id = '$schedule', 
            extras = '$extras',
            curp = '$curp',
            rfc = '$rfc',
            nss = '$nss',
            tipo_sangre = '$tipo_sangre',
            domicilio = '$domicilio',
            colonia = '$colonia',
            cp = '$cp',
            fecha_ingreso = '$fecha_ingreso',
            puesto = '$puesto',
            fecha_alta_imss = '$fecha_alta_imss',
            tel_emergencia_1 = '$tel_emergencia_1',
            parentesco_1 = '$parentesco_1',
            tel_emergencia_2 = '$tel_emergencia_2',
            parentesco_2 = '$parentesco_2'
            WHERE id = '$empid'";
    
    if($conn->query($sql)){
        $_SESSION['success'] = 'Empleado actualizado con éxito';
    }
    else{
        $_SESSION['error'] = $conn->error;
    }
}
else{
    $_SESSION['error'] = 'Seleccionar empleado para editar primero';
}

header('location: employee.php');
?>
