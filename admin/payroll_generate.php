<?php
include 'includes/session.php';

$range = $_POST['date_range'];
$ex = explode(' - ', $range);
$from = date('Y-m-d', strtotime($ex[0]));
$to = date('Y-m-d', strtotime($ex[1]));

$sql = "SELECT *, SUM(amount) as total_amount FROM deductions";
$query = $conn->query($sql);
$drow = $query->fetch_assoc();
$deduction = $drow['total_amount'];

$from_title = date('d M, Y', strtotime($ex[0]));
$to_title = date('d M, Y', strtotime($ex[1]));

require_once('../tcpdf/tcpdf.php');
$pdf = new TCPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetTitle('Payroll: ' . $from_title . ' - ' . $to_title);
$pdf->SetHeaderData('', '', PDF_HEADER_TITLE, PDF_HEADER_STRING);
$pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
$pdf->SetDefaultMonospacedFont('helvetica');
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
$pdf->SetMargins(PDF_MARGIN_LEFT, '10', PDF_MARGIN_RIGHT);
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);
$pdf->SetAutoPageBreak(TRUE, 10);
$pdf->SetFont('helvetica', '', 11);
$pdf->AddPage();
$contents = '';

$sql1 = "SELECT id,firstname,lastname FROM employees";

$query1 = $conn->query($sql1);
while ($row1 = $query1->fetch_assoc()) {
	$empid = $row1['id'];

	$contents .= '<h2 align="center">REPORTE DE ASISTENCIAS</h2>
	<p align="center"><b>Empresa: </b>ECOTRAPGUARD</p>
	<p align="center"><b>Reporte del </b>' . $from_title . '<b> al </b>' . $to_title . '</p>
	<h3>Nombre:' . $row1['firstname'] . " " . $row1['lastname'] . '</h3>
	<table style="font-size: 8px;" border="1" cellspacing="0" cellpadding="3" width="100%">
		<thead>
			<tr>
				<th>Fecha</th>
				<th>Ent</th>
				<th>Sal</th>
				<th>Comida</th>
				<th>Pago Neto</th>
				<th>Ent</th>
				<th>Sal</th>
				<th>Extra</th>
				<th>Tiempo</th>
				<th>Comida</th>
			</tr>
		</thead>
		<tbody>';
	$sql1 = "SELECT a.date,a.num_hr,a.time_in,a.time_out,o.hours,o.rate as p_extra ,o.date_overtime,(SELECT p.rate FROM position p LEFT JOIN employees e ON p.id = e.position_id WHERE e.id = '$empid') as pago FROM attendance a LEFT JOIN overtime o ON o.employee_id=a.employee_id AND o.date_overtime=a.date WHERE a.employee_id='$empid' AND a.date BETWEEN '$from' AND '$to' ORDER BY a.date ASC";

	$query = $conn->query($sql1);
	while ($row = $query->fetch_assoc()) {
		$contents .= '<tr>
			<td align="center">' . date('d/M/Y', strtotime($row['date'])) . '</td>
			<td align="center">' . date('H:i', strtotime($row['time_in'])) . '</td>
			<td align="center">' . date('H:i', strtotime($row['time_out'])) . '</td>
			<td align="center"></td>
			<td align="center">' . number_format(($row['num_hr'] * $row['pago']) + ($row['hours'] * $row['pago'] * $row['p_extra']), 2) . '</td>
			<td align="center"></td>
			<td align="center"></td>
			<td align="center">' . $row['hours'] . '</td>
			<td align="center">' . $row['num_hr'] . '</td>
			<td align="center"></td>
		</tr>';
	}

	$contents .= '</tbody>
	</table>
	<h4>Firma: _______________</h4>
	<br>';
}
$pdf->writeHTML($contents);
$pdf->Output('payroll.pdf', 'I');
