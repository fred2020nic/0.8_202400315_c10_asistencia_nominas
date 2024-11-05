<?php
  include 'includes/session.php';

  if(isset($_POST['delete'])){
    $id = $_POST['id'];
    $sql = "DELETE FROM bono WHERE id = '$id'";
    if($conn->query($sql)){
      $_SESSION['success'] = 'Bono eliminado exitosamente';
    }
    else{
      $_SESSION['error'] = $conn->error;
    }
  }
  else{
    $_SESSION['error'] = 'Selecciona un bono para eliminar primero';
  }

  header('location: bono.php');
?>
