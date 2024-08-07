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

$from_title = date('M d, Y', strtotime($ex[0]));
$to_title = date('M d, Y', strtotime($ex[1]));

require_once('../tcpdf/tcpdf.php');
$pdf = new TCPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetTitle('Payslip: ' . $from_title . ' - ' . $to_title);
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

$sql = "SELECT *, SUM(num_hr) AS total_hr, attendance.employee_id AS empid, employees.employee_id AS employee, employees.extras as extras, position.rate as salario 
        FROM attendance 
        LEFT JOIN employees ON employees.id=attendance.employee_id 
        LEFT JOIN position ON position.id=employees.position_id 
        LEFT JOIN festivos f ON attendance.date = f.holiday_date 
        WHERE date BETWEEN '$from' AND '$to' 
        GROUP BY attendance.employee_id 
        ORDER BY employees.lastname ASC, employees.firstname ASC";

$query = $conn->query($sql);
while ($row = $query->fetch_assoc()) {
    $empid = $row['empid'];

    $casql = "SELECT *, SUM(amount) AS cashamount FROM cashadvance WHERE employee_id='$empid' AND date_advance BETWEEN '$from' AND '$to'";
    $caquery = $conn->query($casql);
    $carow = $caquery->fetch_assoc();
    $cashadvance = $carow['cashamount'];

    $oveql = "SELECT rate, SUM(hours) AS horas FROM overtime WHERE employee_id='$empid' AND date_overtime BETWEEN '$from' AND '$to'";
    $ovequery = $conn->query($oveql);
    $over = $ovequery->fetch_assoc();
    $overtime = $over['horas'];
    $rate_ove = $over['rate'];

    $extras = $row['extras'];
    $tot_hrs = $row['total_hr'];
    $salario_hr = $row['salario'];
    $cantidad_hroas_extras = ($extras / 4) - $tot_hrs;
    $valor_extra = ($row['rate'] * 1.5) * $cantidad_hroas_extras;
    $gross = $row['rate'] * $tot_hrs;
    $total_deduction = $deduction + $cashadvance;

    // Calcular valor festivo
    $is_holiday = $row['holiday_date'] ? true : false;
    $valor_festivo = $is_holiday ? $salario_hr * 0.1 : 0;

    // Calcular el salario neto incluyendo el valor festivo
    $net = $gross + ($rate_ove * $overtime * $row['rate']) - $total_deduction + $valor_extra + $valor_festivo;

    $contents .= '
        <h2 align="center">ConfiguroWeb</h2>
        <h4 align="center">' . $from_title . " - " . $to_title . '</h4>
        <table cellspacing="0" cellpadding="3">  
            <tr>  
                <td width="25%" align="right">Nombre Empleado: </td>
                <td width="25%"><b>' . $row['firstname'] . " " . $row['lastname'] . '</b></td>
                <td width="25%" align="right">Total de Horas: </td>
                <td width="25%" align="right">' . number_format($tot_hrs, 2) . '</td> 
            </tr>
            <tr>
                <td width="25%" align="right">ID Empleado: </td>
                <td width="25%">' . $row['employee'] . '</td>   
                <td width="25%" align="right">Total de Horas Extra: </td>
                <td width="25%" align="right">' . number_format($cantidad_hroas_extras, 2) . '</td> 
            </tr>
            <tr> 
                <td width="25%" align="right">Pago por Hora: </td>
                <td width="25%">' . number_format($row['rate'], 2) . '</td>
                <td width="25%" align="right"><b>Pago Real: </b></td>
                <td width="25%" align="right"><b>' . number_format($gross, 2) . '</b></td> 
            </tr>
            <tr> 
                <td></td> 
                <td></td>
                <td width="25%" align="right">Valor por Hora: </td>
                <td width="25%" align="right">' . number_format($salario_hr * 1.5, 2) . '</td> 
            </tr>
            <tr> 
                <td></td> 
                <td></td>
                <td width="25%" align="right">Valor Realizado Por Monto Extra: </td>
                <td width="25%" align="right">' . number_format($valor_extra, 2) . '</td> 
            </tr>
            <tr> 
                <td></td> 
                <td></td>
                <td width="25%" align="right">Valor Festivo: </td>
                <td width="25%" align="right">' . number_format($valor_festivo, 2) . '</td> 
            </tr>
            <tr> 
                <td></td> 
                <td></td>
                <td width="25%" align="right"><b>Total Deduciones:</b></td>
                <td width="25%" align="right"><b>' . number_format($total_deduction, 2) . '</b></td> 
            </tr>
            <tr> 
                <td></td> 
                <td></td>
                <td width="25%" align="right"><b>Salario Neto:</b></td>
                <td width="25%" align="right"><b>' . number_format($net, 2) . '</b></td> 
            </tr>
        </table>
        <br><hr>
    ';
}
$pdf->writeHTML($contents);
$pdf->Output('payslip.pdf', 'I');
?>
