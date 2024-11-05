<?php
  include 'includes/session.php';

  if(isset($_POST['edit'])){
    $id = $conn->real_escape_string($_POST['id']);
    $employee_id = $conn->real_escape_string($_POST['employee_id']);
    $ocurrencia_id = $conn->real_escape_string($_POST['ocurrencia_id']);
    $date = $conn->real_escape_string($_POST['date']);
    $justificacion = $conn->real_escape_string($_POST['justificacion']);
    $retardos = $conn->real_escape_string($_POST['retardos']);
    $status = $conn->real_escape_string($_POST['status']);

    $sql = "UPDATE chequeador SET 
              employee_id = '$employee_id', 
              ocurrencia_id = '$ocurrencia_id', 
              date = '$date', 
              justificacion = '$justificacion', 
              retardos = '$retardos', 
              status = '$status' 
            WHERE id = '$id'";
    if($conn->query($sql)){
      $_SESSION['success'] = 'Registro de asistencia actualizado exitosamente';
    }
    else{
      $_SESSION['error'] = 'Error al actualizar: ' . $conn->error;
    }
  }
  else{
    $_SESSION['error'] = 'Completa el formulario de edición primero';
  }

  header('location: chequeador.php');
?>
