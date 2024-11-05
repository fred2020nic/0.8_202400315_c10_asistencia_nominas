<?php
include 'includes/session.php';

if(isset($_POST['edit'])){
    $id = $_POST['id'];
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
    $estado = $_POST['estado'];

    // Registrar todos los datos recibidos para depuración
    error_log(print_r($_POST, true), 3, 'error_log.txt');
    
    // Validar que el ID no esté vacío
    if (empty($id)) {
        $_SESSION['error'] = 'ID de solicitud no encontrado. No se puede actualizar.';
        header('location: vacacion.php');
        exit();
    }

    $sql = "UPDATE vacacion SET 
            fecha_inicio = '$fecha_inicio', 
            fecha_fin = '$fecha_fin', 
            fecha_regreso = '$fecha_regreso', 
            empresa = '$empresa', 
            puesto = '$puesto', 
            con_goce = '$con_goce', 
            sin_goce = '$sin_goce', 
            hora_retiro = '$hora_retiro', 
            fecha_salida = '$fecha_salida', 
            motivo_paterno = '$motivo_paterno', 
            motivo_materno = '$motivo_materno', 
            motivo_fallecimiento = '$motivo_fallecimiento', 
            motivo_tramites = '$motivo_tramites', 
            motivo_lactancia = '$motivo_lactancia', 
            motivo_otra_situacion = '$motivo_otra_situacion', 
            observaciones = '$observaciones', 
            estado = '$estado' 
            WHERE id = '$id'";

    if($conn->query($sql)){
        $_SESSION['success'] = 'Solicitud de Vacaciones actualizada con éxito';
    }
    else{
        $_SESSION['error'] = $conn->error;
        // Registrar el error en el archivo error_log.txt
        error_log($conn->error, 3, 'error_log.txt');
    }
}
else{
    $_SESSION['error'] = 'Complete el formulario de edición primero';
}

header('location: vacacion.php');
exit();
