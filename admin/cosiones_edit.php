<?php
  include 'includes/session.php';

  if(isset($_POST['edit'])){
    if(empty($_POST['id'])){
      $_SESSION['error'] = 'ID de la cosión está vacío';
      header('location: cosiones.php');
      exit();
    }

    $id = $conn->real_escape_string($_POST['id']);
    $employee = $conn->real_escape_string($_POST['edit_employee']);
    $monto = $conn->real_escape_string($_POST['edit_monto']);
    $date = $conn->real_escape_string($_POST['edit_date']);
    $status = $conn->real_escape_string($_POST['edit_status']);

    // Verificar si el empleado existe
    $sql = "SELECT * FROM employees WHERE id = '$employee'";
    $query = $conn->query($sql);

    if($query->num_rows < 1){
      $_SESSION['error'] = 'Empleado no encontrado';
    }
    else{
      // Verificar si ya existe otra cosión para el mismo empleado y fecha
      $sql = "SELECT * FROM cosiones WHERE employee_id = '$employee' AND date = '$date' AND id != '$id'";
      $query = $conn->query($sql);

      if($query->num_rows > 0){
        $_SESSION['error'] = 'Ya existe otra cosión para este empleado en la fecha seleccionada';
      }
      else{
        // Actualizar la cosión
        $sql = "UPDATE cosiones SET employee_id = '$employee', monto = '$monto', date = '$date', status = '$status' WHERE id = '$id'";
        if($conn->query($sql)){
          $_SESSION['success'] = 'Cosión actualizada exitosamente';
        }
        else{
          $_SESSION['error'] = $conn->error;
        }
      }
    }
  }
  else{
    $_SESSION['error'] = 'Completa el formulario de edición primero';
  }

  header('location: cosiones.php');
?>
