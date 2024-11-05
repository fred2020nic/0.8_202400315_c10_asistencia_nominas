<?php 
	include 'includes/session.php';

	if(isset($_POST['id'])){
		$id = $_POST['id'];
		$sql = "SELECT *, solicitudpermiso.id AS spid, employees.employee_id AS empid FROM solicitudpermiso LEFT JOIN employees ON employees.id=solicitudpermiso.employee_id WHERE solicitudpermiso.id='$id'";
		$query = $conn->query($sql);
		$row = $query->fetch_assoc();

		echo json_encode($row);
	}
?>
