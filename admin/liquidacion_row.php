<?php
// liquidaciones_row.php
include 'includes/session.php';
include 'includes/conn.php'; // Asegúrate de incluir la conexión a la base de datos

if(isset($_POST['id'])){
    $liquidacion_id = intval($_POST['id']);

    // Obtener la liquidación principal
    $sql = "SELECT * FROM liquidaciones WHERE id = ?";
    $stmt = $conn->prepare($sql);
    if(!$stmt){
        echo json_encode(['error' => 'Preparación de la consulta fallida: ' . $conn->error]);
        exit;
    }
    $stmt->bind_param("i", $liquidacion_id);
    if(!$stmt->execute()){
        echo json_encode(['error' => 'Ejecución de la consulta fallida: ' . $stmt->error]);
        exit;
    }
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();

    if($row){
        $employee_id = $row['employee_id'];
        $total_monto = $row['total_monto'];
        $status = $row['status'];

        // Obtener datos del empleado
        $sql_emp = "SELECT firstname, lastname, position.rate FROM employees 
                    LEFT JOIN position ON employees.position_id = position.id 
                    WHERE employees.id = ?";
        $stmt_emp = $conn->prepare($sql_emp);
        if(!$stmt_emp){
            echo json_encode(['error' => 'Preparación de la consulta fallida (Empleado): ' . $conn->error]);
            exit;
        }
        $stmt_emp->bind_param("i", $employee_id);
        if(!$stmt_emp->execute()){
            echo json_encode(['error' => 'Ejecución de la consulta fallida (Empleado): ' . $stmt_emp->error]);
            exit;
        }
        $result_emp = $stmt_emp->get_result();
        $employee = $result_emp->fetch_assoc();
        $stmt_emp->close();

        // Obtener vacaciones específicas para la liquidación
        $sql_vac = "SELECT * FROM vacaciones WHERE liquidacion_id = ?";
        $stmt_vac = $conn->prepare($sql_vac);
        if(!$stmt_vac){
            echo json_encode(['error' => 'Preparación de la consulta fallida (Vacaciones): ' . $conn->error]);
            exit;
        }
        $stmt_vac->bind_param("i", $liquidacion_id);
        if(!$stmt_vac->execute()){
            echo json_encode(['error' => 'Ejecución de la consulta fallida (Vacaciones): ' . $stmt_vac->error]);
            exit;
        }
        $result_vac = $stmt_vac->get_result();
        $vacaciones = $result_vac->fetch_assoc();
        $stmt_vac->close();

        // Obtener aguinaldo específico para la liquidación
        $sql_agu = "SELECT * FROM aguinaldo WHERE liquidacion_id = ?";
        $stmt_agu = $conn->prepare($sql_agu);
        if(!$stmt_agu){
            echo json_encode(['error' => 'Preparación de la consulta fallida (Aguinaldo): ' . $conn->error]);
            exit;
        }
        $stmt_agu->bind_param("i", $liquidacion_id);
        if(!$stmt_agu->execute()){
            echo json_encode(['error' => 'Ejecución de la consulta fallida (Aguinaldo): ' . $stmt_agu->error]);
            exit;
        }
        $result_agu = $stmt_agu->get_result();
        $aguinaldo = $result_agu->fetch_assoc();
        $stmt_agu->close();

        // Obtener indemnización específica para la liquidación
        $sql_ind = "SELECT * FROM indemnizacion WHERE liquidacion_id = ?";
        $stmt_ind = $conn->prepare($sql_ind);
        if(!$stmt_ind){
            echo json_encode(['error' => 'Preparación de la consulta fallida (Indemnización): ' . $conn->error]);
            exit;
        }
        $stmt_ind->bind_param("i", $liquidacion_id);
        if(!$stmt_ind->execute()){
            echo json_encode(['error' => 'Ejecución de la consulta fallida (Indemnización): ' . $stmt_ind->error]);
            exit;
        }
        $result_ind = $stmt_ind->get_result();
        $indemnizacion = $result_ind->fetch_assoc();
        $stmt_ind->close();

        // Obtener el número de empleado y salario
        $emp_number = isset($row['employee_id']) ? $row['employee_id'] : '';
        $firstname = isset($employee['firstname']) ? htmlspecialchars($employee['firstname']) : '';
        $lastname = isset($employee['lastname']) ? htmlspecialchars($employee['lastname']) : '';
        $salary = isset($employee['rate']) ? htmlspecialchars($employee['rate']) : '';

        echo json_encode([
            'id' => $liquidacion_id,
            'employee_id' => $employee_id,
            'emp_number' => $emp_number, // Asegúrate de que este campo exista
            'firstname' => $firstname,
            'lastname' => $lastname,
            'salary' => $salary,
            'status' => $status,
            'vacaciones' => $vacaciones,
            'aguinaldo' => $aguinaldo,
            'indemnizacion' => $indemnizacion
        ]);
    } else {
        echo json_encode(['error' => 'Liquidación no encontrada']);
    }
}
?>
