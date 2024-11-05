<?php
include 'includes/session.php';

if (isset($_POST['edit'])) {
    $id = $_POST['id'];
    $tipo_permiso = $_POST['tipo_permiso'];
    $motivo = $_POST['motivo'];
    $observaciones = $_POST['observaciones'];

    $sql = "UPDATE solicitudpermiso SET tipo_permiso = '$tipo_permiso', motivo = '$motivo', observaciones = '$observaciones' WHERE id = '$id'";
    if ($conn->query($sql)) {
        $_SESSION['success'] = 'Solicitud de Permiso actualizada exitosamente';
    } else {
        $_SESSION['error'] = $conn->error;
    }
} else {
    $_SESSION['error'] = 'Primero llene el formulario de edición';
}

header('location: permiso.php');
?>
