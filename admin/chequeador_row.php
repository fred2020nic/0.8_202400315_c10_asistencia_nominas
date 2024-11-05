<?php 
  include 'includes/session.php';

  if(isset($_POST['id'])){
    $id = $conn->real_escape_string($_POST['id']);
    $sql = "SELECT chequeador.*, employees.firstname, employees.lastname, ocurrencia.name AS ocurrencia_name 
            FROM chequeador 
            LEFT JOIN employees ON employees.id = chequeador.employee_id 
            LEFT JOIN ocurrencia ON ocurrencia.id = chequeador.ocurrencia_id 
            WHERE chequeador.id = '$id'";
    $query = $conn->query($sql);
    if($query){
      $row = $query->fetch_assoc();
      echo json_encode($row);
    }
    else{
      echo json_encode(['error' => $conn->error]);
    }
  }
?>
