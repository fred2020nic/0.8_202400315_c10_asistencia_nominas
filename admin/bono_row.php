<?php 
  include 'includes/session.php';

  if(isset($_POST['id'])){
    $id = $conn->real_escape_string($_POST['id']); // Sanitizar entrada
    $sql = "SELECT bono.*, employees.employee_id AS emp_number, employees.firstname, employees.lastname 
            FROM bono 
            LEFT JOIN employees ON employees.id=bono.employee_id 
            WHERE bono.id = '$id'";
    $query = $conn->query($sql);

    if($query->num_rows > 0){
      $row = $query->fetch_assoc();
      echo json_encode($row);
    }
    else{
      echo json_encode(['error' => 'No se encontró el bono']);
    }
  }
?>