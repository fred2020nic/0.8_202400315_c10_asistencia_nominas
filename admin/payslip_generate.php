<?php
include 'includes/session.php';

$range = $_POST['date_range'];
$ex = explode(' - ', $range);
$from = date('Y-m-d', strtotime($ex[0]));
$to = date('Y-m-d', strtotime($ex[1]));

$from_title = date('M d, Y', strtotime($ex[0]));
$to_title = date('M d, Y', strtotime($ex[1]));

require_once('../tcpdf/tcpdf.php');

// Crear nuevo PDF con orientación vertical y tamaño carta
$pdf = new TCPDF('P', PDF_UNIT, 'LETTER', true, 'UTF-8', false);

$pdf->SetCreator(PDF_CREATOR);
$pdf->SetTitle('Recibos de Pago: ' . $from_title . ' - ' . $to_title);
$pdf->SetHeaderData('', '', 'Recibos de Pago', '');
$pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
$pdf->SetDefaultMonospacedFont('helvetica');
$pdf->SetFooterMargin(10);
$pdf->SetMargins(15, 10, 15); // Ajusta los márgenes si es necesario
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);
$pdf->SetAutoPageBreak(TRUE, 15);
$pdf->SetFont('helvetica', '', 9);
$pdf->AddPage();

$sql = "
SELECT 
    e.id AS employee_id,
    e.firstname, 
    e.lastname,
    e.employee_id AS employee_code,
    IFNULL(att.total_hours, 0) AS total_hours,
    IFNULL(att.valor_festivo, 0) AS valor_festivo,
    e.extras AS horas_contrato,
    p.rate AS salario_hr,
    (IFNULL(att.total_hours, 0) * p.rate) AS gross,
    GREATEST(IFNULL(att.total_hours, 0) - e.extras, 0) AS horas_extras,
    (p.rate * 1.5) AS valor_hora_extra,
    GREATEST(IFNULL(att.total_hours, 0) - e.extras, 0) * p.rate * 1.5 AS monto_horas_extra,
    IFNULL(bonos.total_bonos, 0) AS bonos,
    IFNULL(deducciones.total_deducciones, 0) AS deducciones,
    IFNULL(comisiones.total_comisiones, 0) AS comisiones,
    IFNULL(propinas.total_propinas, 0) AS propinas,
    IFNULL(prestamos.total_prestamos, 0) AS prestamos,
    -- Campos adicionales (puedes ajustar los cálculos según necesites)
    0 AS base_ispt,
    0 AS retenciones,
    0 AS horas_festivas,
    0 AS domingo,
    0 AS horas_dominicales,
    0 AS vacaciones,
    0 AS isr,
    0 AS imss,
    0 AS ptu,
    -- Cálculo del pago neto
    ((IFNULL(att.total_hours, 0) * p.rate)
    + GREATEST(IFNULL(att.total_hours, 0) - e.extras, 0) * p.rate * 1.5
    + IFNULL(att.valor_festivo, 0)
    + IFNULL(bonos.total_bonos, 0)
    + IFNULL(comisiones.total_comisiones, 0)
    + IFNULL(propinas.total_propinas, 0))
    - (IFNULL(deducciones.total_deducciones, 0)
    + IFNULL(prestamos.total_prestamos, 0)) AS neto,
    -- Importe total
    ((IFNULL(att.total_hours, 0) * p.rate)
    + GREATEST(IFNULL(att.total_hours, 0) - e.extras, 0) * p.rate * 1.5
    + IFNULL(att.valor_festivo, 0)
    + IFNULL(bonos.total_bonos, 0)
    + IFNULL(comisiones.total_comisiones, 0)
    + IFNULL(propinas.total_propinas, 0)) AS importe_total
FROM 
    employees e
LEFT JOIN 
    position p ON p.id = e.position_id
LEFT JOIN (
    SELECT 
        a.employee_id,
        SUM(a.num_hr) AS total_hours,
        SUM(CASE WHEN f.holiday_date IS NOT NULL THEN a.num_hr * p.rate * 0.1 ELSE 0 END) AS valor_festivo
    FROM 
        attendance a
    LEFT JOIN 
        festivos f ON a.date = f.holiday_date
    JOIN 
        employees e ON e.id = a.employee_id
    JOIN 
        position p ON p.id = e.position_id
    WHERE 
        a.date BETWEEN '$from' AND '$to'
    GROUP BY 
        a.employee_id
) att ON att.employee_id = e.id
LEFT JOIN (
    SELECT 
        employee_id,
        SUM(monto) AS total_bonos
    FROM 
        bono
    WHERE 
        date BETWEEN '$from' AND '$to' AND status = 1
    GROUP BY 
        employee_id
) bonos ON bonos.employee_id = e.id
LEFT JOIN (
    SELECT 
        employee_id,
        SUM(monto) AS total_deducciones
    FROM 
        deducciones
    WHERE 
        date BETWEEN '$from' AND '$to' AND status = 1
    GROUP BY 
        employee_id
) deducciones ON deducciones.employee_id = e.id
LEFT JOIN (
    SELECT 
        employee_id,
        SUM(monto) AS total_comisiones
    FROM 
        cosiones  -- Nombre de la tabla corregido
    WHERE 
        date BETWEEN '$from' AND '$to' AND status = 1
    GROUP BY 
        employee_id
) comisiones ON comisiones.employee_id = e.id
LEFT JOIN (
    SELECT 
        employee_id,
        SUM(monto) AS total_propinas
    FROM 
        propinas
    WHERE 
        date BETWEEN '$from' AND '$to' AND status = 1
    GROUP BY 
        employee_id
) propinas ON propinas.employee_id = e.id
LEFT JOIN (
    SELECT 
        employee_id,
        SUM(amount) AS total_prestamos
    FROM 
        cashadvance
    WHERE 
        date_advance BETWEEN '$from' AND '$to'
    GROUP BY 
        employee_id
) prestamos ON prestamos.employee_id = e.id
WHERE 
    e.status = 1
ORDER BY 
    e.lastname ASC, e.firstname ASC
";

$query = $conn->query($sql);

if (!$query) {
    echo "Error en la consulta SQL: " . $conn->error;
    exit;
}

$employee_count = 0;
$total_employees = $query->num_rows;

while ($row = $query->fetch_assoc()) {
    $employee_count++;

    // Construir el contenido del recibo de pago
    $contents = ''; // Reiniciar el contenido para cada empleado

    $contents .= '
    <h2 align="center">ECOTRAPGUARD</h2>
    <h4 align="center">Recibo de Pago</h4>
    <h5 align="center">' . $from_title . ' - ' . $to_title . '</h5>
    <table cellpadding="3">
        <tr>
            <td width="25%" align="right"><strong>Nombre Empleado:</strong></td>
            <td width="25%">' . $row['firstname'] . ' ' . $row['lastname'] . '</td>
            <td width="25%" align="right"><strong>ID Empleado:</strong></td>
            <td width="25%">' . $row['employee_code'] . '</td>
        </tr>
        <tr>
            <td width="25%" align="right">Total Horas:</td>
            <td width="25%">' . number_format($row['total_hours'], 2) . '</td>
            <td width="25%" align="right">Valor Festivo:</td>
            <td width="25%">' . number_format($row['valor_festivo'], 2) . '</td>
        </tr>
        <tr>
            <td width="25%" align="right">Horas Contrato:</td>
            <td width="25%">' . number_format($row['horas_contrato'], 2) . '</td>
            <td width="25%" align="right">Gross:</td>
            <td width="25%">' . number_format($row['gross'], 2) . '</td>
        </tr>
        <tr>
            <td width="25%" align="right">Valor por Hora:</td>
            <td width="25%">' . number_format($row['salario_hr'], 2) . '</td>
            <td width="25%" align="right">Horas Extras:</td>
            <td width="25%">' . number_format($row['horas_extras'], 2) . '</td>
        </tr>
        <tr>
            <td width="25%" align="right">Valor por Hora Extra:</td>
            <td width="25%">' . number_format($row['valor_hora_extra'], 2) . '</td>
            <td width="25%" align="right">Monto Horas Extra:</td>
            <td width="25%">' . number_format($row['monto_horas_extra'], 2) . '</td>
        </tr>
        <tr>
            <td width="25%" align="right">Bonos:</td>
            <td width="25%">' . number_format($row['bonos'], 2) . '</td>
            <td width="25%" align="right">Deducciones:</td>
            <td width="25%">' . number_format($row['deducciones'], 2) . '</td>
        </tr>
        <tr>
            <td width="25%" align="right">Comisiones:</td>
            <td width="25%">' . number_format($row['comisiones'], 2) . '</td>
            <td width="25%" align="right">Propinas:</td>
            <td width="25%">' . number_format($row['propinas'], 2) . '</td>
        </tr>
        <tr>
            <td width="25%" align="right">Préstamos:</td>
            <td width="25%">' . number_format($row['prestamos'], 2) . '</td>
            <td width="25%" align="right">Pago Neto:</td>
            <td width="25%"><strong>' . number_format($row['neto'], 2) . '</strong></td>
        </tr>
        <!-- Agrega otros campos adicionales si es necesario -->
    </table>
    <br><hr><br>
    ';

    $pdf->writeHTML($contents);

    if ($employee_count % 2 == 0 && $employee_count != $total_employees) {
        // Después de cada 2 empleados, agregar una nueva página
        $pdf->AddPage();
    }
}

$pdf->Output('payslips.pdf', 'I');

?>
