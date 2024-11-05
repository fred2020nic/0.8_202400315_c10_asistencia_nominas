<?php
include 'includes/session.php';

if(isset($_POST['add'])){
    $employee = $_POST['employee'];
    $fecha_inicio = $_POST['fecha_inicio'];
    $fecha_fin = $_POST['fecha_fin'];
    $fecha_regreso = $_POST['fecha_regreso'];
    $empresa = $_POST['empresa'];
    $puesto = $_POST['puesto'];
    $con_goce = isset($_POST['con_goce']) ? 1 : 0;
    $sin_goce = isset($_POST['sin_goce']) ? 1 : 0;
    $hora_retiro = $_POST['hora_retiro'];
    $fecha_salida = $_POST['fecha_salida'];
    $motivo_paterno = isset($_POST['motivo_paterno']) ? 1 : 0;
    $motivo_materno = isset($_POST['motivo_materno']) ? 1 : 0;
    $motivo_fallecimiento = isset($_POST['motivo_fallecimiento']) ? 1 : 0;
    $motivo_tramites = isset($_POST['motivo_tramites']) ? 1 : 0;
    $motivo_lactancia = isset($_POST['motivo_lactancia']) ? 1 : 0;
    $motivo_otra_situacion = isset($_POST['motivo_otra_situacion']) ? 1 : 0;
    $observaciones = $_POST['observaciones'];
    $estado = 'pendiente'; // Estado predeterminado

    // Depuración para verificar datos:
    error_log(print_r($_POST, true));

    $sql = "SELECT * FROM employees WHERE employee_id = '$employee'";
    $query = $conn->query($sql);
    if($query->num_rows < 1){
        $_SESSION['error'] = 'Empleado no encontrado';
    }
    else{
        $row = $query->fetch_assoc();
        $employee_id = $row['id'];
        $sql = "INSERT INTO vacacion (employee_id, fecha_solicitud, fecha_inicio, fecha_fin, fecha_regreso, empresa, puesto, con_goce, sin_goce, hora_retiro, fecha_salida, motivo_paterno, motivo_materno, motivo_fallecimiento, motivo_tramites, motivo_lactancia, motivo_otra_situacion, observaciones, estado) 
                VALUES ('$employee_id', NOW(), '$fecha_inicio', '$fecha_fin', '$fecha_regreso', '$empresa', '$puesto', '$con_goce', '$sin_goce', '$hora_retiro', '$fecha_salida', '$motivo_paterno', '$motivo_materno', '$motivo_fallecimiento', '$motivo_tramites', '$motivo_lactancia', '$motivo_otra_situacion', '$observaciones', '$estado')";
        if($conn->query($sql)){
            $_SESSION['success'] = 'Solicitud añadida con éxito';
        }
        else{
            $_SESSION['error'] = $conn->error;
        }
    }
}   
else{
    $_SESSION['error'] = 'Complete el formulario primero';
}

header('location: vacacion.php');
