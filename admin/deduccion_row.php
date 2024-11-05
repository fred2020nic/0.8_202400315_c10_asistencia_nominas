<?php 
  include 'includes/session.php';

  if(isset($_POST['id'])){
    $id = $conn->real_escape_string($_POST['id']);
    $sql = "SELECT deducciones.*, employees.employee_id AS emp_number, employees.firstname, employees.lastname 
            FROM deducciones 
            LEFT JOIN employees ON employees.id=deducciones.employee_id 
            WHERE deducciones.id = '$id'";
    $query = $conn->query($sql);

    if($query->num_rows > 0){
      $row = $query->fetch_assoc();
      echo json_encode($row);
    }
    else{
      echo json_encode(['error' => 'No se encontró la deducción']);
    }
  }
?>
