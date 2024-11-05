<?php
  include 'includes/session.php';

  if(isset($_POST['delete'])){
    $id = $conn->real_escape_string($_POST['id']);
    $sql = "DELETE FROM deducciones WHERE id = '$id'";
    if($conn->query($sql)){
      $_SESSION['success'] = 'Deducción eliminada exitosamente';
    }
    else{
      $_SESSION['error'] = $conn->error;
    }
  }
  else{
    $_SESSION['error'] = 'Selecciona una deducción para eliminar primero';
  }

  header('location: deducciones.php');
?>
