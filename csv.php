<?php
$conexion = new mysqli("localhost", "root", "", "visitas_db");
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}


header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=visitas.csv');

$output = fopen('php://output', 'w');

fputcsv($output, ['Nombre', 'Apellido', 'DNI', 'Motivo', 'Persona Visita', 'Ingreso', 'Egreso']);

$sql = "SELECT nombre, apellido, dni, motivo, persona_visita, hora_ingreso, hora_egreso FROM visitas";
$resultado = $conexion->query($sql);

while ($row = $resultado->fetch_assoc()) {
    fputcsv($output, $row);
}

fclose($output);
?>
