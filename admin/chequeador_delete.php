<?php
  include 'includes/session.php';

  if(isset($_POST['delete'])){
    $id = $conn->real_escape_string($_POST['id']);

    $sql = "DELETE FROM chequeador WHERE id = '$id'";
    if($conn->query($sql)){
      $_SESSION['success'] = 'Registro de asistencia eliminado exitosamente';
    }
    else{
      $_SESSION['error'] = 'Error al eliminar: ' . $conn->error;
    }
  }
  else{
    $_SESSION['error'] = 'Selecciona un registro de asistencia para eliminar primero';
  }

  header('location: chequeador.php');
?>
