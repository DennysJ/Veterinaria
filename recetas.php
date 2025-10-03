<?php
require('fpdf.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recoger datos del formulario
    $mascota = $_POST['txtmascota'];
    $fecha = $_POST['txtfecha'];
    $diagnostico = $_POST['txtdiagnostico'];
    $tratamiento = $_POST['txttratamiento'];
    $medicamento = $_POST['txtmedicamento'];
    $observaciones = $_POST['txtobservaciones'];
    $costo = $_POST['txtcosto'];
    
    // Datos adicionales que podrías agregar al formulario
    $veterinario = isset($_POST['txtveterinario']) ? $_POST['txtveterinario'] : 'Dr. [Nombre del Veterinario]';
    $cedula = isset($_POST['txtcedula']) ? $_POST['txtcedula'] : '12345678';
    $clinica = isset($_POST['txtclinica']) ? $_POST['txtclinica'] : 'Hospital Veterinario MoritosPet';
    $direccion = isset($_POST['txtdireccion']) ? $_POST['txtdireccion'] : 'UMB. Huixquilican';
    $telefono = isset($_POST['txttelefono']) ? $_POST['txttelefono'] : '(555) 123-4567';
    $propietario = isset($_POST['txtpropietario']) ? $_POST['txtpropietario'] : 'Propietario';
    
    // Conexión a la base de datos
    $conn = new mysqli("localhost", "root", "", "vete");
    if ($conn->connect_error) {
        die("Error en conexión: " . $conn->connect_error);
    }
    
    $stmt = $conn->prepare("INSERT INTO recetas (mascota, diagnostico, tratamiento, medicamentos, observaciones, fecha, costo_consulta) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $mascota, $diagnostico, $tratamiento, $medicamento, $observaciones, $fecha, $costo);
    $stmt->execute();
    $stmt->close();
    $conn->close();
    
    // Crear PDF con diseño de receta médica
    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetMargins(20, 15, 20);
    
    // ENCABEZADO DE LA CLÍNICA
    $pdf->SetFont('Arial', 'B', 18);
    $pdf->SetTextColor(0, 100, 150); // Azul profesional
    $pdf->Cell(0, 12, utf8_decode($clinica), 0, 1, 'C');
    
    $pdf->SetFont('Arial', '', 10);
    $pdf->SetTextColor(80, 80, 80);
    $pdf->Cell(0, 5, utf8_decode($direccion), 0, 1, 'C');
    $pdf->Cell(0, 5, 'Tel: ' . $telefono, 0, 1, 'C');
    
    // Línea separadora
    $pdf->Ln(5);
    $pdf->SetDrawColor(0, 100, 150);
    $pdf->Line(20, $pdf->GetY(), 190, $pdf->GetY());
    $pdf->Ln(8);
    
    // TÍTULO DE RECETA
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->Cell(0, 10, 'RECETA MEDICA VETERINARIA', 0, 1, 'C');
    $pdf->Ln(5);
    
    
    
    $pdf->Ln(3);
    
    // INFORMACIÓN DEL PACIENTE Y PROPIETARIO
    $pdf->SetFillColor(240, 240, 240);
    $pdf->SetFont('Arial', 'B', 11);
    $pdf->Cell(0, 8, 'INFORMACION DEL PACIENTE', 1, 1, 'C', true);
    
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(30, 8, 'MASCOTA:', 1, 0);
    $pdf->SetFont('Arial', '', 10);
    $pdf->Cell(70, 8, utf8_decode($mascota), 1, 0);
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(30, 8, 'FECHA:', 1, 0);
    $pdf->SetFont('Arial', '', 10);
    $pdf->Cell(40, 8, $fecha, 1, 1);
    
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(30, 8, 'PROPIETARIO:', 1, 0);
    $pdf->SetFont('Arial', '', 10);
    $pdf->Cell(140, 8, utf8_decode($propietario), 1, 1);
    
    $pdf->Ln(5);
    
    // DIAGNÓSTICO
    $pdf->SetFont('Arial', 'B', 11);
    $pdf->Cell(0, 8, 'DIAGNOSTICO', 1, 1, 'C', true);
    $pdf->SetFont('Arial', '', 10);
    $pdf->MultiCell(0, 6, utf8_decode($diagnostico), 1);
    
    $pdf->Ln(3);
    
    // TRATAMIENTO
    $pdf->SetFont('Arial', 'B', 11);
    $pdf->Cell(0, 8, 'TRATAMIENTO PRESCRITO', 1, 1, 'C', true);
    $pdf->SetFont('Arial', '', 10);
    $pdf->MultiCell(0, 6, utf8_decode($tratamiento), 1);
    
    $pdf->Ln(3);
    
    // MEDICAMENTOS
    $pdf->SetFont('Arial', 'B', 11);
    $pdf->Cell(0, 8, 'MEDICAMENTOS', 1, 1, 'C', true);
    $pdf->SetFont('Arial', '', 10);
    $pdf->MultiCell(0, 6, utf8_decode($medicamento), 1);
    
    $pdf->Ln(3);
    
    // OBSERVACIONES
    $pdf->SetFont('Arial', 'B', 11);
    $pdf->Cell(0, 8, 'OBSERVACIONES E INDICACIONES', 1, 1, 'C', true);
    $pdf->SetFont('Arial', '', 10);
    $pdf->MultiCell(0, 6, utf8_decode($observaciones), 1);
    
    $pdf->Ln(5);
    
    // COSTO
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->SetTextColor(0, 100, 0);
    $pdf->Cell(0, 8, 'COSTO DE CONSULTA: $' . number_format($costo, 2), 0, 1, 'R');
    
    // FIRMA
    $pdf->Ln(15);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->SetFont('Arial', '', 10);
    $pdf->Cell(0, 5, '________________________________', 0, 1, 'R');
    $pdf->Cell(0, 5, 'Firma del Veterinario', 0, 1, 'R');
    
    
    // PIE DE PÁGINA
    $pdf->Ln(10);
    $pdf->SetFont('Arial', 'I', 8);
    $pdf->SetTextColor(100, 100, 100);
    $pdf->Cell(0, 5, 'Esta receta medica es valida unicamente con la prescripción del veterinario autorizado', 0, 1, 'C');
    $pdf->Cell(0, 5, 'Fecha de emisión: ' . date('d/m/Y H:i:s'), 0, 1, 'C');
    
    // Generar el PDF
    $pdf->Output('I', 'receta_medica_' . str_replace(' ', '_', $mascota) . '_' . date('Y-m-d') . '.pdf');
}
?>
