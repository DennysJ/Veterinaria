<?php
require('fpdf/fpdf.php');

// Conexión a la base de datos
$mysqli = new mysqli("localhost", "root", "", "vete");
if ($mysqli->connect_error) {
    die("Error de conexión: " . $mysqli->connect_error);
}

// Recibe los datos del formulario
$id_consulta = $_POST['id_consulta'];
$id_dueno = $_POST['id_dueno'];
$id_mascota = $_POST['id_mascota'];
$total = $_POST['total'];
$metodo_pago = $_POST['metodo_pago'];
$fecha = date("Y-m-d H:i:s");

// Inserta la factura
$stmt = $mysqli->prepare("INSERT INTO facturas (id_consulta, id_dueno, id_mascota, fecha, total, metodo_pago) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param("iiidss", $id_consulta, $id_dueno, $id_mascota, $fecha, $total, $metodo_pago);
$stmt->execute();

// Obtén el ID de la nueva factura
$id_factura = $stmt->insert_id;
$stmt->close();

// Consulta datos opcionales para mostrar nombres (si tienes esas tablas)
$nombre_dueño = "Dueño #$id_dueno";
$nombre_mascota = "Mascota #$id_mascota";

// Crear el PDF
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial','B',16);

$pdf->Cell(0,10,"Factura #$id_factura",0,1,'C');
$pdf->SetFont('Arial','',12);
$pdf->Ln(5);

$pdf->Cell(0,10,"Fecha: $fecha",0,1);
$pdf->Cell(0,10,"ID Consulta: $id_consulta",0,1);
$pdf->Cell(0,10,"Dueño: $nombre_dueño (ID: $id_dueno)",0,1);
$pdf->Cell(0,10,"Mascota: $nombre_mascota (ID: $id_mascota)",0,1);
$pdf->Cell(0,10,"Total: $$total",0,1);
$pdf->Cell(0,10,"Método de pago: $metodo_pago",0,1);

// Mostrar o guardar
$pdf->Output("I", "Factura_$id_factura.pdf"); // Muestra en navegador
?>
