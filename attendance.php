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
		
		// $timezone = 'America/Bogota';
		// date_default_timezone_set($timezone);

		$date_now = 'America/Managua';
		date_default_timezone_set($date_now);

		if ($status == 'in') {
			$sql = "SELECT * FROM attendance WHERE employee_id = '$id' AND date = '$date_now' AND time_in IS NOT NULL";
			$query = $conn->query($sql);
			if ($query->num_rows > 0) {
				$output['error'] = true;
				$output['message'] = 'Has registrado tu entrada por hoy';
			} else {
				//updates
				$sched = $row['schedule_id'];
				$lognow = date('H:i:s');
				$sql = "SELECT * FROM schedules WHERE id = '$sched'";
				$squery = $conn->query($sql);
				$srow = $squery->fetch_assoc();
				$logstatus = ($lognow > $srow['time_in']) ? 0 : 1;
				//
				$sql = "INSERT INTO attendance (employee_id, date, time_in, status, time_out,	num_hr) VALUES ('$id', '$date_now', NOW(), '$logstatus', '00:00:00', '0')";
				if ($conn->query($sql)) {
					$output['message'] = 'Llegada: ' . $row['firstname'] . ' ' . $row['lastname'];
				} else {
					$output['error'] = true;
					$output['message'] = $conn->error;
				}
			}
		} else {
			$sql = "SELECT *, attendance.id AS uid FROM attendance LEFT JOIN employees ON employees.id=attendance.employee_id WHERE attendance.employee_id = '$id' AND date = '$date_now'";
			$query = $conn->query($sql);
			if ($query->num_rows < 1) {
				$output['error'] = true;
				$output['message'] = 'No se puede registrar tu salida, sin previamente registrar	 tu entrada.';
			} else {
				$row = $query->fetch_assoc();
				if ($row['time_out'] != '00:00:00') {
					$output['error'] = true;
					$output['message'] = ' Has registrado tu salida satisfactoriamente por el día de hoy';
				} else {

					$sql = "UPDATE attendance SET time_out = NOW() WHERE id = '" . $row['uid'] . "'";
					if ($conn->query($sql)) {
						$extra = null;
						$output['message'] = 'Salida: ' . $row['firstname'] . ' ' . $row['lastname'];

						$sql = "SELECT * FROM attendance WHERE id = '" . $row['uid'] . "'";
						$query = $conn->query($sql);
						$urow = $query->fetch_assoc();

						$time_in = $urow['time_in'];
						$time_out = $urow['time_out'];

						$sql = "SELECT * FROM employees LEFT JOIN schedules ON schedules.id=employees.schedule_id WHERE employees.id = '$id'";
						$query = $conn->query($sql);
						$srow = $query->fetch_assoc();

						/* if ($srow['time_in'] > $urow['time_in']) {
							$time_in = $srow['time_in'];
						}

						if ($srow['time_out'] < $urow['time_in']) {
							$time_out = $srow['time_out'];
						} */

						$time_in = new DateTime($time_in);
						$time_out = new DateTime($time_out);
						$interval = $time_in->diff($time_out);
						$hrs = $interval->format('%h');
						$mins = $interval->format('%i');
						$mins = $mins / 60;
						$int = $hrs + $mins;
						if ($int > 8) {
							$extra = $int - 8;
							$int = 8;
						}

						$sql = "UPDATE attendance SET num_hr = '$int' WHERE id = '" . $row['uid'] . "'";
						$conn->query($sql);
						if ($extra != null && $extra > 0) {
							$sql = "INSERT INTO overtime (employee_id, hours, rate,date_overtime) VALUES ('$id', '$extra', 2, '$date_now')";
							$conn->query($sql);
						}
					} else {
						$output['error'] = true;
						$output['message'] = $conn->error;
					}
				}
			}
		}
	} else {
		$output['error'] = true;
		$output['message'] = 'ID de empleado no encontrado';
	}
}

echo json_encode($output);
