<?php
require('fpdf/fpdf.php');

// Conexión a base de datos
$mysqli = new mysqli("localhost", "root", "", "vete");
if ($mysqli->connect_error) {
    die("Error de conexión: " . $mysqli->connect_error);
}

// Recibir datos
$id_mascota = $_POST['id_mascota'];
$diagnostico = $_POST['diagnostico'];
$tratamiento = $_POST['tratamiento'];
$medicamentos = $_POST['medicamentos'];
$observaciones = $_POST['observaciones'];

// Guardar en la base de datos
$stmt = $mysqli->prepare("INSERT INTO recetas (id_mascota, diagnostico, tratamiento, medicamentos, observaciones) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("issss", $id_mascota, $diagnostico, $tratamiento, $medicamentos, $observaciones);
$stmt->execute();
$stmt->close();

// Crear PDF
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 10, 'Receta Veterinaria', 0, 1, 'C');

$pdf->SetFont('Arial', '', 12);
$pdf->Ln(10);
$pdf->MultiCell(0, 10, "Diagnóstico:\n$diagnostico\n\nTratamiento:\n$tratamiento\n\nMedicamentos:\n$medicamentos\n\nObservaciones:\n$observaciones");

$pdf->Output('I', 'receta.pdf'); // Mostrar en navegador

$mysqli->close();
?>
