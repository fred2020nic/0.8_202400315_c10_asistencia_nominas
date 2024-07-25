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

$sql1 = "SELECT id, firstname, lastname FROM employees";
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
                <th>Festivo</th> <!-- Nueva columna para indicar si es un día festivo -->
            </tr>
        </thead>
        <tbody>';
    
    $sql2 = "SELECT a.date, a.num_hr, a.time_in, a.time_out, o.hours, o.rate as p_extra, o.date_overtime, 
            (SELECT p.rate FROM position p LEFT JOIN employees e ON p.id = e.position_id WHERE e.id = '$empid') as pago,
            CASE WHEN f.holiday_date IS NOT NULL AND a.date = f.holiday_date THEN 1 ELSE 0 END AS is_holiday
            FROM attendance a
            LEFT JOIN overtime o ON o.employee_id = a.employee_id AND o.date_overtime = a.date
            LEFT JOIN festivos f ON a.date = f.holiday_date
            WHERE a.employee_id = '$empid' AND a.date BETWEEN '$from' AND '$to' 
            ORDER BY a.date ASC";

    $query2 = $conn->query($sql2);
    while ($row2 = $query2->fetch_assoc()) {
        $is_holiday = $row2['is_holiday'];
        $valor_festivo = $is_holiday ? $row2['pago'] * 0.1 : 0;
        $pago_neto = ($row2['num_hr'] * $row2['pago']) + ($row2['hours'] * $row2['pago'] * $row2['p_extra']) + $valor_festivo;

        $contents .= '<tr>
            <td align="center">' . date('d/M/Y', strtotime($row2['date'])) . '</td>
            <td align="center">' . date('H:i', strtotime($row2['time_in'])) . '</td>
            <td align="center">' . date('H:i', strtotime($row2['time_out'])) . '</td>
            <td align="center"></td>
            <td align="center">' . number_format($pago_neto, 2) . '</td>
            <td align="center"></td>
            <td align="center"></td>
            <td align="center">' . $row2['hours'] . '</td>
            <td align="center">' . $row2['num_hr'] . '</td>
            <td align="center"></td>
            <td align="center">' . ($is_holiday ? 'Sí' : 'No') . '</td>
        </tr>';
    }

    $contents .= '</tbody>
    </table>
    <h4>Firma: _______________</h4>
    <br>';
}
$pdf->writeHTML($contents);
$pdf->Output('payroll.pdf', 'I');
?>
