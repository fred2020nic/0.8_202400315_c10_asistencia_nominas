<?php
// liquidaciones_edit.php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'includes/session.php';
include 'includes/conn.php'; // Asegúrate de incluir la conexión a la base de datos

if(isset($_POST['edit'])){
    $liquidacion_id = intval($_POST['liquidacion_id']);
    $employee_id = intval($_POST['edit_employee']);
    $status = intval($_POST['status']);
    $total_monto = floatval($_POST['total_monto']);

    // Obtener datos de cada liquidación
    $vacaciones = isset($_POST['vacaciones']) ? $_POST['vacaciones'] : null;
    $aguinaldo = isset($_POST['aguinaldo']) ? $_POST['aguinaldo'] : null;
    $indemnizacion = isset($_POST['indemnizacion']) ? $_POST['indemnizacion'] : null;

    // Iniciar una transacción
    $conn->begin_transaction();

    try {
        // Actualizar estado del empleado
        $stmt = $conn->prepare("UPDATE employees SET status = ? WHERE id = ?");
        if(!$stmt){
            throw new Exception("Preparación de la consulta fallida: " . $conn->error);
        }
        $stmt->bind_param("ii", $status, $employee_id);
        if(!$stmt->execute()){
            throw new Exception("Ejecución de la consulta fallida (Employees): " . $stmt->error);
        }
        $stmt->close();

        // Función para manejar actualizaciones e inserciones basadas en liquidacion_id
        function actualizar_o_insertar($conn, $tabla, $datos, $liquidacion_id, $status) {
            // Verificar si ya existe una entrada para esta liquidacion_id
            $check_sql = "SELECT id FROM $tabla WHERE liquidacion_id = ?";
            $check_stmt = $conn->prepare($check_sql);
            if(!$check_stmt){
                throw new Exception("Preparación de la consulta fallida ($tabla): " . $conn->error);
            }
            $check_stmt->bind_param("i", $liquidacion_id);
            $check_stmt->execute();
            $check_stmt->store_result();
            $exists = $check_stmt->num_rows > 0;
            $check_stmt->close();

            if($exists){
                // Actualizar
                $update_sql = "UPDATE $tabla SET inicio = ?, fin = ?, dias = ?, valor_dia = ?, monto = ?, estado = ? WHERE liquidacion_id = ?";
                $stmt = $conn->prepare($update_sql);
                if(!$stmt){
                    throw new Exception("Preparación de la consulta fallida (Actualizar $tabla): " . $conn->error);
                }
                $stmt->bind_param("ssiddii", $datos['inicio'], $datos['fin'], $datos['dias'], $datos['valor_dia'], $datos['monto'], $status, $liquidacion_id);
                if(!$stmt->execute()){
                    throw new Exception("Ejecución de la consulta fallida (Actualizar $tabla): " . $stmt->error);
                }
                $stmt->close();
            } else {
                // Insertar
                $insert_sql = "INSERT INTO $tabla (employee_id, liquidacion_id, inicio, fin, dias, valor_dia, monto, estado) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($insert_sql);
                if(!$stmt){
                    throw new Exception("Preparación de la consulta fallida (Insertar $tabla): " . $conn->error);
                }
                $stmt->bind_param("iissiddi", $datos['employee_id'], $liquidacion_id, $datos['inicio'], $datos['fin'], $datos['dias'], $datos['valor_dia'], $datos['monto'], $status);
                if(!$stmt->execute()){
                    throw new Exception("Ejecución de la consulta fallida (Insertar $tabla): " . $stmt->error);
                }
                $stmt->close();
            }
        }

        // Actualizar en Vacaciones
        if($vacaciones){
            $vacaciones['employee_id'] = $employee_id; // Asegurar que employee_id esté presente
            actualizar_o_insertar($conn, 'vacaciones', $vacaciones, $liquidacion_id, $status);
        }

        // Actualizar en Aguinaldo
        if($aguinaldo){
            $aguinaldo['employee_id'] = $employee_id; // Asegurar que employee_id esté presente
            actualizar_o_insertar($conn, 'aguinaldo', $aguinaldo, $liquidacion_id, $status);
        }

        // Actualizar en Indemnización
        if($indemnizacion){
            $indemnizacion['employee_id'] = $employee_id; // Asegurar que employee_id esté presente
            actualizar_o_insertar($conn, 'indemnizacion', $indemnizacion, $liquidacion_id, $status);
        }

        // Actualizar en Liquidaciones (sumatoria)
        $stmt = $conn->prepare("UPDATE liquidaciones SET employee_id = ?, total_monto = ?, status = ? WHERE id = ?");
        if(!$stmt){
            throw new Exception("Preparación de la consulta fallida (Liquidaciones): " . $conn->error);
        }
        $stmt->bind_param("diii", $employee_id, $total_monto, $status, $liquidacion_id);
        if(!$stmt->execute()){
            throw new Exception("Ejecución de la consulta fallida (Liquidaciones): " . $stmt->error);
        }
        $stmt->close();

        // Confirmar transacción
        $conn->commit();
        $_SESSION['success'] = 'Liquidaciones actualizadas correctamente.';
        error_log("Liquidaciones actualizadas correctamente para el ID: " . $liquidacion_id);
    } catch (Exception $e) {
        $conn->rollback();
        $_SESSION['error'] = 'Error al actualizar liquidaciones: ' . $e->getMessage();
        error_log("Error al actualizar liquidaciones para el ID: " . $liquidacion_id . ". Error: " . $e->getMessage());
    }

    header('location: liquidaciones.php');
}
?>
