<?php 
include 'includes/session.php';

if(isset($_POST['id'])){
    $id = $_POST['id'];
    $sql = "SELECT *, vacacion.id AS vacid, employees.employee_id AS empid, employees.firstname, employees.lastname, vacacion.fecha_inicio, vacacion.fecha_fin, vacacion.fecha_regreso, vacacion.empresa, vacacion.puesto, vacacion.con_goce, vacacion.sin_goce, vacacion.hora_retiro, vacacion.fecha_salida, vacacion.motivo_paterno, vacacion.motivo_materno, vacacion.motivo_fallecimiento, vacacion.motivo_tramites, vacacion.motivo_lactancia, vacacion.motivo_otra_situacion, vacacion.observaciones FROM vacacion LEFT JOIN employees ON employees.id=vacacion.employee_id WHERE vacacion.id='$id'";
    $query = $conn->query($sql);

    if ($query) {
        $row = $query->fetch_assoc();
        // Registrar los datos para depuración
        error_log(print_r($row, true), 3, 'error_log.txt');
        echo json_encode($row);
    } else {
        echo json_encode(["error" => $conn->error]);  // Añadir manejo de errores
        // Registrar el error en el archivo error_log.txt
        error_log($conn->error, 3, 'error_log.txt');
    }
}
