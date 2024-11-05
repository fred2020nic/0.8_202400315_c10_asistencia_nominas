<?php
  include 'includes/session.php';

  if(isset($_POST['add'])){
    $employee = $_POST['employee'];
    $monto = $_POST['monto'];
    $date = $_POST['date'];
    $status = $_POST['status'];

    // Verificar si el empleado existe
    $sql = "SELECT * FROM employees WHERE id = '$employee'";
    $query = $conn->query($sql);

    if($query->num_rows < 1){
      $_SESSION['error'] = 'Empleado no encontrado';
    }
    else{
      // Verificar si ya existe un bono para el mismo empleado y fecha
      $sql = "SELECT * FROM bono WHERE employee_id = '$employee' AND date = '$date'";
      $query = $conn->query($sql);

      if($query->num_rows > 0){
        $_SESSION['error'] = 'Ya existe un bono para este empleado en la fecha seleccionada';
      }
      else{
        // Insertar el nuevo bono
        $sql = "INSERT INTO bono (employee_id, date, monto, status) VALUES ('$employee', '$date', '$monto', '$status')";
        if($conn->query($sql)){
          $_SESSION['success'] = 'Bono agregado exitosamente';
        }
        else{
          $_SESSION['error'] = $conn->error;
        }
      }
    }
  }
  else{
    $_SESSION['error'] = 'Completa el formulario de agregar primero';
  }

  header('location: bono.php');
?>
