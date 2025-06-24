<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}
include('conexion.php');

$id = 0; 
$visita = null; 

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $result = $conexion->query("SELECT * FROM visitas WHERE id = $id");
    if ($result->num_rows == 1) {
        $visita = $result->fetch_assoc();
    } else {
        echo "¡Registro no encontrado!";
        
    }
}

if (isset($_POST['guardar'])) {
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $dni = $_POST['dni'];
    $motivo = $_POST['motivo'];
    $persona = $_POST['persona_visita'];
    $id = intval($_POST['id']); 

    $stmt = $conexion->prepare("UPDATE visitas SET nombre=?, apellido=?, dni=?, motivo=?, persona_visita=? WHERE id=?");
    $stmt->bind_param("ssisss", $nombre, $apellido, $dni, $motivo, $persona, $id); 
    $stmt->execute();
    header("Location: index.php");
    exit();
}
?>
<?php $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); ?>
<input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Visita</title>
</head>
<body>
    <h2>Editar visita</h2>
    <form method="POST">
        <input type="hidden" name="id" value="<?php echo $id; ?>"> <input type="text" name="nombre" value="<?php echo htmlspecialchars($visita['nombre'] ?? ''); ?>" required>
        <input type="text" name="apellido" value="<?php echo htmlspecialchars($visita['apellido'] ?? ''); ?>" required>
        <input type="text" name="dni" value="<?php echo htmlspecialchars($visita['dni'] ?? ''); ?>" required>
        <input type="text" name="motivo" value="<?php echo htmlspecialchars($visita['motivo'] ?? ''); ?>" required>
        <input type="text" name="persona_visita" value="<?php echo htmlspecialchars($visita['persona_visita'] ?? ''); ?>" required>
        <button type="submit" name="guardar">Guardar cambios</button>
    </form>
</body>
</html>