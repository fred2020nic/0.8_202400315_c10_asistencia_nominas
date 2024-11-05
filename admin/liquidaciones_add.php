<?php
// liquidaciones_add.php
include 'includes/session.php';
include 'includes/conn.php'; // Asegúrate de incluir la conexión a la base de datos

if(isset($_POST['add'])){
    $employee_id = $_POST['employee'];
    $status = $_POST['status'];
    $total_monto = $_POST['total_monto'];

    // Obtener datos de cada liquidación
    $vacaciones = isset($_POST['vacaciones']) ? $_POST['vacaciones'] : null;
    $aguinaldo = isset($_POST['aguinaldo']) ? $_POST['aguinaldo'] : null;
    $indemnizacion = isset($_POST['indemnizacion']) ? $_POST['indemnizacion'] : null;

    // Iniciar una transacción
    $conn->begin_transaction();

    try {
        // Insertar en Liquidaciones (sumatoria)
        $stmt = $conn->prepare("INSERT INTO liquidaciones (employee_id, total_monto, status) VALUES (?, ?, ?)");
        if(!$stmt){
            throw new Exception("Preparación de la consulta fallida (Liquidaciones): " . $conn->error);
        }
        $stmt->bind_param("idi", $employee_id, $total_monto, $status);
        $stmt->execute();
        $liquidacion_id = $stmt->insert_id; // Obtener el ID de la liquidación recién creada
        $stmt->close();

        // Actualizar estado del empleado
        $stmt = $conn->prepare("UPDATE employees SET status = ? WHERE id = ?");
        if(!$stmt){
            throw new Exception("Preparación de la consulta fallida: " . $conn->error);
        }
        $stmt->bind_param("ii", $status, $employee_id);
        $stmt->execute();
        $stmt->close();

        // Insertar en Vacaciones
        if($vacaciones){
            $stmt = $conn->prepare("INSERT INTO vacaciones (employee_id, liquidacion_id, inicio, fin, dias, valor_dia, monto, estado) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            if(!$stmt){
                throw new Exception("Preparación de la consulta fallida (Vacaciones): " . $conn->error);
            }
            $stmt->bind_param("iissiddi", $employee_id, $liquidacion_id, $vacaciones['inicio'], $vacaciones['fin'], $vacaciones['dias'], $vacaciones['valor_dia'], $vacaciones['monto'], $status);
            $stmt->execute();
            $stmt->close();
        }

        // Insertar en Aguinaldo
        if($aguinaldo){
            $stmt = $conn->prepare("INSERT INTO aguinaldo (employee_id, liquidacion_id, inicio, fin, dias, valor_dia, monto, estado) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            if(!$stmt){
                throw new Exception("Preparación de la consulta fallida (Aguinaldo): " . $conn->error);
            }
            $stmt->bind_param("iissiddi", $employee_id, $liquidacion_id, $aguinaldo['inicio'], $aguinaldo['fin'], $aguinaldo['dias'], $aguinaldo['valor_dia'], $aguinaldo['monto'], $status);
            $stmt->execute();
            $stmt->close();
        }

        // Insertar en Indemnización
        if($indemnizacion){
            $stmt = $conn->prepare("INSERT INTO indemnizacion (employee_id, liquidacion_id, inicio, fin, dias, valor_dia, monto, estado) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            if(!$stmt){
                throw new Exception("Preparación de la consulta fallida (Indemnización): " . $conn->error);
            }
            $stmt->bind_param("iissiddi", $employee_id, $liquidacion_id, $indemnizacion['inicio'], $indemnizacion['fin'], $indemnizacion['dias'], $indemnizacion['valor_dia'], $indemnizacion['monto'], $status);
            $stmt->execute();
            $stmt->close();
        }

        // Confirmar transacción
        $conn->commit();
        $_SESSION['success'] = 'Liquidaciones agregadas correctamente.';
    } catch (Exception $e) {
        $conn->rollback();
        $_SESSION['error'] = 'Error al agregar liquidaciones: ' . $e->getMessage();
    }

    header('location: liquidaciones.php');
}
?>
