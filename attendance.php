<?php
if (isset($_POST['employee'])) {
    $output = array('error' => false);

    include 'conn.php';
    include './timezone.php';

    $employee = $_POST['employee'];
    $status = $_POST['status'];

    $sql = "SELECT * FROM employees WHERE employee_id = '$employee'";
    $query = $conn->query($sql);

    if ($query->num_rows > 0) {
        $row = $query->fetch_assoc();
        $id = $row['id'];

        date_default_timezone_set('America/Managua'); // Establece la zona horaria
        $date_now = date('Y-m-d'); // Obtén la fecha actual

        // Verifica si hay un registro de asistencia para hoy
        $sql = "SELECT * FROM attendance WHERE employee_id = '$id' AND date = '$date_now'";
        $attendance_query = $conn->query($sql);

        // Si no hay registro de asistencia para hoy, lo creamos
        if ($attendance_query->num_rows == 0 && $status == 'in') {
            $sched = $row['schedule_id'];
            $lognow = date('H:i:s');
            $sql = "SELECT * FROM schedules WHERE id = '$sched'";
            $squery = $conn->query($sql);
            $srow = $squery->fetch_assoc();
            $logstatus = ($lognow > $srow['time_in']) ? 0 : 1;

            $sql = "INSERT INTO attendance (employee_id, date, time_in, status) VALUES ('$id', '$date_now', NOW(), '$logstatus')";
            if ($conn->query($sql)) {
                $output['message'] = 'Llegada: ' . $row['firstname'] . ' ' . $row['lastname'];
            } else {
                $output['error'] = true;
                $output['message'] = $conn->error;
            }
        } else {
            // Si ya hay un registro de asistencia para hoy
            $attendance_row = $attendance_query->fetch_assoc();
            $attendance_id = $attendance_row['id'];

            if ($status == 'in') {
                if ($attendance_row['time_in'] != '00:00:00') {
                    $output['error'] = true;
                    $output['message'] = 'Ya has registrado tu entrada por hoy.';
                } else {
                    $sched = $row['schedule_id'];
                    $lognow = date('H:i:s');
                    $sql = "SELECT * FROM schedules WHERE id = '$sched'";
                    $squery = $conn->query($sql);
                    $srow = $squery->fetch_assoc();
                    $logstatus = ($lognow > $srow['time_in']) ? 0 : 1;

                    $sql = "UPDATE attendance SET time_in = NOW(), status = '$logstatus' WHERE id = '$attendance_id'";
                    if ($conn->query($sql)) {
                        $output['message'] = 'Llegada: ' . $row['firstname'] . ' ' . $row['lastname'];
                    } else {
                        $output['error'] = true;
                        $output['message'] = $conn->error;
                    }
                }
            } elseif ($status == 'lunch_out') {
                if ($attendance_row['time_in'] == '00:00:00') {
                    $output['error'] = true;
                    $output['message'] = 'No puedes registrar salida a comer sin haber registrado tu entrada.';
                } elseif ($attendance_row['lunch_out'] != '00:00:00') {
                    $output['error'] = true;
                    $output['message'] = 'Ya has registrado tu salida a comer.';
                } else {
                    $sql = "UPDATE attendance SET lunch_out = NOW() WHERE id = '$attendance_id'";
                    if ($conn->query($sql)) {
                        $output['message'] = 'Salida a Comer registrada para: ' . $row['firstname'] . ' ' . $row['lastname'];
                    } else {
                        $output['error'] = true;
                        $output['message'] = $conn->error;
                    }
                }
            } elseif ($status == 'lunch_in') {
                if ($attendance_row['lunch_out'] == '00:00:00') {
                    $output['error'] = true;
                    $output['message'] = 'No puedes registrar entrada de comer sin haber registrado tu salida a comer.';
                } elseif ($attendance_row['lunch_in'] != '00:00:00') {
                    $output['error'] = true;
                    $output['message'] = 'Ya has registrado tu entrada de comer.';
                } else {
                    $sql = "UPDATE attendance SET lunch_in = NOW() WHERE id = '$attendance_id'";
                    if ($conn->query($sql)) {
                        $output['message'] = 'Entrada de Comer registrada para: ' . $row['firstname'] . ' ' . $row['lastname'];
                    } else {
                        $output['error'] = true;
                        $output['message'] = $conn->error;
                    }
                }
            } elseif ($status == 'out') {
                if ($attendance_row['time_in'] == '00:00:00') {
                    $output['error'] = true;
                    $output['message'] = 'No puedes registrar salida sin haber registrado tu entrada.';
                } elseif ($attendance_row['time_out'] != '00:00:00') {
                    $output['error'] = true;
                    $output['message'] = 'Ya has registrado tu salida por hoy.';
                } else {
                    $sql = "UPDATE attendance SET time_out = NOW() WHERE id = '$attendance_id'";
                    if ($conn->query($sql)) {
                        $output['message'] = 'Salida registrada para: ' . $row['firstname'] . ' ' . $row['lastname'];

                        // Cálculo de horas trabajadas
                        $time_in = new DateTime($attendance_row['time_in']);
                        $time_out = new DateTime(date('H:i:s'));
                        $interval = $time_in->diff($time_out);
                        $hrs = $interval->format('%h');
                        $mins = $interval->format('%i');
                        $mins = $mins / 60;
                        $int = $hrs + $mins;

                        // Restar tiempo de almuerzo si corresponde
                        if ($attendance_row['lunch_out'] != '00:00:00' && $attendance_row['lunch_in'] != '00:00:00') {
                            $lunch_out = new DateTime($attendance_row['lunch_out']);
                            $lunch_in = new DateTime($attendance_row['lunch_in']);
                            $lunch_interval = $lunch_out->diff($lunch_in);
                            $lunch_hrs = $lunch_interval->format('%h');
                            $lunch_mins = $lunch_interval->format('%i');
                            $lunch_mins = $lunch_mins / 60;
                            $lunch_total = $lunch_hrs + $lunch_mins;

                            // Restar el tiempo de almuerzo de las horas trabajadas
                            $int -= $lunch_total;
                        }

                        // Actualizar las horas trabajadas
                        $sql = "UPDATE attendance SET num_hr = '$int' WHERE id = '$attendance_id'";
                        $conn->query($sql);

                        // Manejo de horas extras
                        if ($int > 8) {
                            $extra = $int - 8;
                            $sql = "INSERT INTO overtime (employee_id, hours, rate, date_overtime) VALUES ('$id', '$extra', 2, '$date_now')";
                            $conn->query($sql);
                        }

                    } else {
                        $output['error'] = true;
                        $output['message'] = $conn->error;
                    }
                }
            } else {
                $output['error'] = true;
                $output['message'] = 'Estado no reconocido.';
            }
        }
    } else {
        $output['error'] = true;
        $output['message'] = 'ID de empleado no encontrado';
    }
}

echo json_encode($output);
?>
