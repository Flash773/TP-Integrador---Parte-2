<?php
require('fpdf/fpdf.php');
$conexion = new mysqli("localhost", "root", "", "visitas_db");
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

class PDF extends FPDF {
    function Header() {
        $this->SetFont('Arial', 'B', 14);
        $this->Cell(0, 10, 'Listado de Visitas', 0, 1, 'C');
        $this->Ln(5);
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(30, 8, 'Nombre', 1);
        $this->Cell(30, 8, 'Apellido', 1);
        $this->Cell(20, 8, 'DNI', 1);
        $this->Cell(40, 8, 'Motivo', 1);
        $this->Cell(40, 8, 'Persona Visita', 1);
        $this->Cell(35, 8, 'Ingreso', 1);
        $this->Cell(35, 8, 'Egreso', 1);
        $this->Ln();
    }
}

$pdf = new PDF('L');
$pdf->AddPage();
$pdf->SetFont('Arial', '', 10);

$sql = "SELECT nombre, apellido, dni, motivo, persona_visita, hora_ingreso, hora_egreso FROM visitas ORDER BY hora_ingreso DESC";
$resultado = $conexion->query($sql);

while ($row = $resultado->fetch_assoc()) {
    $pdf->Cell(30, 8, $row['nombre'], 1);
    $pdf->Cell(30, 8, $row['apellido'], 1);
    $pdf->Cell(20, 8, $row['dni'], 1);
    $pdf->Cell(40, 8, $row['motivo'], 1);
    $pdf->Cell(40, 8, $row['persona_visita'], 1);
    $pdf->Cell(35, 8, $row['hora_ingreso'], 1);
    $pdf->Cell(35, 8, $row['hora_egreso'], 1);
    $pdf->Ln();
}

ob_clean(); 
$pdf->Output('visitas_' . date('Y-m-d_H-i-s') . '.pdf', 'D'); 
?>
