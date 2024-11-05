<?php

$host = $_SERVER['HTTP_HOST'];
//  var_dump($host);

if ($host === 'localhost') {

	$conn = new mysqli("localhost", "root", "", '0.9_20240801_m20_asistencia_nominas_f2');

	if ($conn->connect_error) {
	    die("Connection failed: " . $conn->connect_error);
	}

} else {

	$conn = new mysqli("localhost", "u363832898_c10_asistencia", "#1234Abcd..#", 'u363832898_c10_asistencia');

	if ($conn->connect_error) {
	    die("Connection failed: " . $conn->connect_error);
	}
}
?>