<?php
include 'includes/session.php';

if(isset($_POST['delete'])){
    $employee_id = $_POST['employee_id'];

    // Iniciar una transacción
    $conn->begin_transaction();

    try {
        // Eliminar Vacaciones
        $sql_vac = "DELETE FROM vacaciones WHERE employee_id='$employee_id'";
        if(!$conn->query($sql_vac)){
            throw new Exception("Error al eliminar Vacaciones: " . $conn->error);
        }

        // Eliminar Aguinaldo
        $sql_agui = "DELETE FROM aguinaldo WHERE employee_id='$employee_id'";
        if(!$conn->query($sql_agui)){
            throw new Exception("Error al eliminar Aguinaldo: " . $conn->error);
        }

        // Eliminar Indemnización
        $sql_indem = "DELETE FROM indemnizacion WHERE employee_id='$employee_id'";
        if(!$conn->query($sql_indem)){
            throw new Exception("Error al eliminar Indemnización: " . $conn->error);
        }

        // Confirmar transacción
        $conn->commit();
        $_SESSION['success'] = 'Liquidaciones eliminadas exitosamente';
    } catch (Exception $e) {
        // Revertir transacción
        $conn->rollback();
        $_SESSION['error'] = $e->getMessage();
    }

} else {
    $_SESSION['error'] = 'Acceso no autorizado';
}

header('location: liquidaciones.php');
?>
