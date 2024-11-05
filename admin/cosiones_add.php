<?php
  include 'includes/session.php';

  if(isset($_POST['add'])){
    $employee = $conn->real_escape_string($_POST['employee']);
    $monto = $conn->real_escape_string($_POST['monto']);
    $date = $conn->real_escape_string($_POST['date']);
    $status = $conn->real_escape_string($_POST['status']);

    // Verificar si el empleado existe
    $sql = "SELECT * FROM employees WHERE id = '$employee'";
    $query = $conn->query($sql);

    if($query->num_rows < 1){
      $_SESSION['error'] = 'Empleado no encontrado';
    }
    else{
      // Verificar si ya existe una cosión para el mismo empleado y fecha
      $sql = "SELECT * FROM cosiones WHERE employee_id = '$employee' AND date = '$date'";
      $query = $conn->query($sql);

      if($query->num_rows > 0){
        $_SESSION['error'] = 'Ya existe una cosión para este empleado en la fecha seleccionada';
      }
      else{
        // Insertar la nueva cosión
        $sql = "INSERT INTO cosiones (employee_id, date, monto, status) VALUES ('$employee', '$date', '$monto', '$status')";
        if($conn->query($sql)){
          $_SESSION['success'] = 'Cosión agregada exitosamente';
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

  header('location: cosiones.php');
?>
