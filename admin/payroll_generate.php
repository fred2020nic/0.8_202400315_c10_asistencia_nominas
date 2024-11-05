<?php
include 'includes/session.php';

$range = $_POST['date_range'];
$ex = explode(' - ', $range);
$from = date('Y-m-d', strtotime($ex[0]));
$to = date('Y-m-d', strtotime($ex[1]));

$from_title = date('d M, Y', strtotime($ex[0]));
$to_title = date('d M, Y', strtotime($ex[1]));

// Consulta SQL modificada para incluir todos los campos y agrupar por empleado
$sql = "
SELECT 
    e.id AS employee_id,
    e.firstname, 
    e.lastname,
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

require_once('../tcpdf/tcpdf.php');

// Crear nuevo PDF con orientación horizontal y tamaño legal
$pdf = new TCPDF('L', PDF_UNIT, 'LEGAL', true, 'UTF-8', false);

$pdf->SetCreator(PDF_CREATOR);
$pdf->SetTitle('Nómina: ' . $from_title . ' - ' . $to_title);
$pdf->SetHeaderData('', '', 'Nómina de Sueldos', '');
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
$pdf->SetDefaultMonospacedFont('helvetica');
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
$pdf->SetMargins(10, 10, 10); // Ajusta los márgenes si es necesario
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);
$pdf->SetAutoPageBreak(TRUE, 10);
$pdf->SetFont('helvetica', '', 8);
$pdf->AddPage();

// Contenido del PDF
$contents = '';

$contents .= '
<h2 align="center">NÓMINA DE SUELDOS</h2>
<p align="center"><b>Empresa: </b>ECOTRAPGUARD</p>
<p align="center"><b>Reporte del </b>' . $from_title . '<b> al </b>' . $to_title . '</p>
<table border="1" cellspacing="0" cellpadding="3">
    <thead>
        <tr>
            <th><b>Nombre Empleado</b></th>
            <th><b>ID Empleado</b></th>
            <th><b>Total horas</b></th>
            <th><b>Valor Festivo</b></th>
            <th><b>Horas contrato</b></th>
            <th><b>Gross</b></th>
            <th><b>Valor por Hora</b></th>
            <th><b>Horas Extras</b></th>
            <th><b>Valor por Hora Extra</b></th>
            <th><b>Monto Horas Extra</b></th>
            <th><b>Bonos</b></th>
            <th><b>Deducciones</b></th>
            <th><b>Comisiones</b></th>
            <th><b>Propinas</b></th>
            <th><b>Préstamos</b></th>
            <!-- Campos adicionales -->
            <th><b>Base ISPT</b></th>
            <th><b>Retenciones</b></th>
            <th><b>Horas Festivas</b></th>
            <th><b>Domingo</b></th>
            <th><b>Horas Dominicales</b></th>
            <th><b>Vacaciones</b></th>
            <th><b>ISR</b></th>
            <th><b>IMSS</b></th>
            <th><b>PTU</b></th>
            <th><b>Pago Neto</b></th>
            <th><b>Importe Total</b></th>
        </tr>
    </thead>
    <tbody>
';

while ($row = $query->fetch_assoc()) {
    $contents .= '
    <tr>
        <td>' . $row['lastname'] . ', ' . $row['firstname'] . '</td>
        <td>' . $row['employee_id'] . '</td>
        <td align="right">' . number_format($row['total_hours'], 2) . '</td>
        <td align="right">' . number_format($row['valor_festivo'], 2) . '</td>
        <td align="right">' . number_format($row['horas_contrato'], 2) . '</td>
        <td align="right">' . number_format($row['gross'], 2) . '</td>
        <td align="right">' . number_format($row['salario_hr'], 2) . '</td>
        <td align="right">' . number_format($row['horas_extras'], 2) . '</td>
        <td align="right">' . number_format($row['valor_hora_extra'], 2) . '</td>
        <td align="right">' . number_format($row['monto_horas_extra'], 2) . '</td>
        <td align="right">' . number_format($row['bonos'], 2) . '</td>
        <td align="right">' . number_format($row['deducciones'], 2) . '</td>
        <td align="right">' . number_format($row['comisiones'], 2) . '</td>
        <td align="right">' . number_format($row['propinas'], 2) . '</td>
        <td align="right">' . number_format($row['prestamos'], 2) . '</td>
        <!-- Campos adicionales -->
        <td align="right">' . number_format($row['base_ispt'], 2) . '</td>
        <td align="right">' . number_format($row['retenciones'], 2) . '</td>
        <td align="right">' . number_format($row['horas_festivas'], 2) . '</td>
        <td align="right">' . number_format($row['domingo'], 2) . '</td>
        <td align="right">' . number_format($row['horas_dominicales'], 2) . '</td>
        <td align="right">' . number_format($row['vacaciones'], 2) . '</td>
        <td align="right">' . number_format($row['isr'], 2) . '</td>
        <td align="right">' . number_format($row['imss'], 2) . '</td>
        <td align="right">' . number_format($row['ptu'], 2) . '</td>
        <td align="right">' . number_format($row['neto'], 2) . '</td>
        <td align="right">' . number_format($row['importe_total'], 2) . '</td>
    </tr>
    ';
}

$contents .= '
    </tbody>
</table>
';

$pdf->writeHTML($contents);
$pdf->Output('nomina.pdf', 'I');
?>
