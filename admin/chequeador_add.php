<?php
  include 'includes/session.php';

  if(isset($_POST['add'])){
    $employee_id = $conn->real_escape_string($_POST['employee_id']);
    $ocurrencia_id = $conn->real_escape_string($_POST['ocurrencia_id']);
    $date = $conn->real_escape_string($_POST['date']);
    $justificacion = $conn->real_escape_string($_POST['justificacion']);
    $retardos = $conn->real_escape_string($_POST['retardos']);
    $status = $conn->real_escape_string($_POST['status']);

    $sql = "INSERT INTO chequeador (employee_id, ocurrencia_id, date, justificacion, retardos, status) 
            VALUES ('$employee_id', '$ocurrencia_id', '$date', '$justificacion', '$retardos', '$status')";
    if($conn->query($sql)){
      $_SESSION['success'] = 'Registro de asistencia agregado exitosamente';
    }
    else{
      $_SESSION['error'] = 'Error al agregar: ' . $conn->error;
    }
  }	
  else{
    $_SESSION['error'] = 'Completa el formulario de agregar primero';
  }

  header('location: chequeador.php');
?>
