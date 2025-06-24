<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}
include('conexion.php'); 
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Visitas</title>
    <style>
        /* Estilos generales */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #71b7e6, #9b59b6); 
            margin: 0;
            padding: 20px;
            color: #333;
            min-height: 100vh; 
            box-sizing: border-box; 
        }

        .container {
            max-width: 1200px;
            margin: 20px auto;
            background-color: #ffffff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        }

        /* Encabezado */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            flex-wrap: wrap; 
        }

        h2 {
            color: #333;
            font-size: 2.2em;
            font-weight: 600;
            margin: 0;
        }

        .logout-link {
            background-color: #e74c3c; 
            color: white;
            padding: 8px 15px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: background-color 0.3s ease;
        }

        .logout-link:hover {
            background-color: #c0392b;
        }

        
        form {
            background: #f8f8f8; 
            padding: 25px;
            margin-bottom: 25px;
            border-radius: 10px; 
            box-shadow: 0px 4px 10px rgba(0,0,0,0.08); 
            display: grid; 
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); 
            gap: 15px; 
            align-items: end; 
        }

        form.form-single-column { 
            grid-template-columns: 1fr;
            max-width: 400px; 
            margin-left: 0;
            margin-right: auto;
        }

        form.form-filter { 
            max-width: 800px;
            margin-left: 0;
            margin-right: auto;
        }

        form label {
            display: block;
            margin-bottom: 5px;
            font-weight: 600;
            color: #555;
        }

        form input[type="text"],
        form input[type="date"],
        form button[type="submit"] {
            width: 100%;
            padding: 12px;
            border-radius: 8px;
            border: 1px solid #ddd;
            font-size: 1em;
            box-sizing: border-box;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        form input[type="text"]:focus,
        form input[type="date"]:focus {
            border-color: #71b7e6;
            outline: none;
            box-shadow: 0 0 5px rgba(113, 183, 230, 0.5);
        }

        form button[type="submit"] {
            background-color: #71b7e6; 
            color: white;
            border: none;
            cursor: pointer;
            font-weight: 600;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        form button[type="submit"]:hover {
            background-color: #5a9ed2;
            transform: translateY(-2px);
        }
        form button[type="submit"]:active {
            transform: translateY(0);
        }

        
        p.message {
            background-color: #e6ffe6;
            color: #28a745; 
            padding: 10px 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            border: 1px solid #28a745;
            font-weight: 600;
        }
        p.warning-message {
            background-color: #fff3cd;
            color: #856404; 
            padding: 10px 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            border: 1px solid #856404;
            font-weight: 600;
        }

    
        table {
            border-collapse: collapse;
            width: 100%;
            background-color: white;
            border-radius: 10px;
            overflow: hidden; 
            box-shadow: 0px 4px 10px rgba(0,0,0,0.08);
            margin-top: 30px;
        }

        table th, table td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #eee; 
        }

        table th {
            background-color: #f2f2f2; 
            color: #555;
            font-weight: 700;
            text-transform: uppercase; 
            font-size: 0.9em;
        }

        table tr:last-child td {
            border-bottom: none; 
        }

        table tbody tr:hover {
            background-color: #f5f5f5; 
        }

        
        .acciones a {
            margin: 0 8px;
            color: #71b7e6; 
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease;
        }

        .acciones a:hover {
            color: #5a9ed2;
        }

        
        .export-buttons {
            margin-top: 20px;
            margin-bottom: 20px;
            text-align: right;
            display: flex;
            justify-content: flex-end; 
            gap: 15px; 
            flex-wrap: wrap; 
        }

        .export-buttons a {
            background-color: #2ecc71; 
            color: white;
            padding: 10px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: background-color 0.3s ease, transform 0.2s ease;
            display: inline-flex; 
            align-items: center;
            gap: 5px; 
        }

        .export-buttons a.export-pdf {
            background-color: #e74c3c; 
        }
        .export-buttons a.export-pdf:hover {
            background-color: #c0392b;
            transform: translateY(-2px);
        }

        .export-buttons a.export-csv {
            background-color: #f39c12; 
        }
        .export-buttons a.export-csv:hover {
            background-color: #e67e22;
            transform: translateY(-2px);
        }



        .form-reset-system {
            margin-top: 30px; 
            max-width: 300px;
            margin-left: 0;
            margin-right: auto;
        }

        .form-reset-system button {
            background-color: #e74c3c;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1.1em;
            font-weight: 600;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }
        .form-reset-system button:hover {
            background-color: #c0392b;
            transform: translateY(-2px);
        }
        .form-reset-system button:active {
            transform: translateY(0);
        }

       
        @media (max-width: 768px) {
            body {
                padding: 15px;
            }
            .container {
                padding: 20px;
            }
            h2 {
                font-size: 1.8em;
            }
            .header {
                flex-direction: column;
                align-items: flex-start;
            }
            .logout-link {
                margin-top: 15px;
            }
            form {
                grid-template-columns: 1fr; 
            }
            table th, table td {
                padding: 10px;
                font-size: 0.9em;
            }
            .acciones a {
                margin: 0 3px;
            }
            .export-buttons {
                justify-content: center; 
                flex-direction: column; 
                align-items: stretch; 
            }
        }
        @media (max-width: 480px) {
            h2 {
                font-size: 1.5em;
            }
            .logout-link {
                width: 100%;
                text-align: center;
            }
            .export-buttons a {
                width: 100%; 
                text-align: center;
                justify-content: center; 
            }
        }
    </style>
</head>
<body>

<div class="container">
    <div class="header">
        <h2>REGISTRO DE PERSONAS</h2>
        <a href="logout.php" class="logout-link">Cerrar sesión</a>
    </div>

    <?php
    $message = '';
    $message_type = '';

    if (isset($_POST['registrar'])) {
        $nombre = $_POST['nombre'];
        $apellido = $_POST['apellido'];
        $dni = $_POST['dni'];
        $motivo = $_POST['motivo'];
        $persona = $_POST['persona_visita'];
        $hora_ingreso = date('Y-m-d H:i:s');

     
        $stmt = $conexion->prepare("INSERT INTO visitas (nombre, apellido, dni, motivo, persona_visita, hora_ingreso) VALUES (?, ?, ?, ?, ?, ?)");
        
        $stmt->bind_param("ssisss", $nombre, $apellido, $dni, $motivo, $persona, $hora_ingreso);
        if ($stmt->execute()) {
            $message = "Ingreso registrado correctamente.";
            $message_type = 'success';
        } else {
            $message = "Error al registrar el ingreso: " . $stmt->error;
            $message_type = 'error'; 
        }
        $stmt->close();
    }

    if (isset($_POST['salida'])) {
        $dni = $_POST['dni'];
        $hora_egreso = date('Y-m-d H:i:s');
        $stmt = $conexion->prepare("UPDATE visitas SET hora_egreso=? WHERE dni=? AND hora_egreso IS NULL");
        
        $stmt->bind_param("si", $hora_egreso, $dni);
        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                $message = "Salida registrada correctamente para DNI: $dni.";
                $message_type = 'success';
            } else {
                $message = "No se encontró un registro de ingreso activo para el DNI: $dni. Asegúrate de que no haya registrado su salida ya.";
                $message_type = 'warning';
            }
        } else {
            $message = "Error al registrar la salida: " . $stmt->error;
            $message_type = 'error';
        }
        $stmt->close();
    }

    if (isset($_POST['reiniciar'])) {
        
        if ($conexion->query("DELETE FROM visitas")) {
            $message = "Todos los registros han sido eliminados del sistema.";
            $message_type = 'warning'; 
        } else {
            $message = "Error al reiniciar el sistema: " . $conexion->error;
            $message_type = 'error';
        }
    }

    // Mostrar el mensaje si existe
    if (!empty($message)) {
        echo '<p class="message ' . ($message_type == 'warning' ? 'warning-message' : '') . '">' . htmlspecialchars($message) . '</p>';
    }
    ?>

    <h3>Registrar Nuevo Ingreso</h3>
    <form method="POST">
        <input type="text" name="nombre" placeholder="Nombre" required>
        <input type="text" name="apellido" placeholder="Apellido" required>
        <input type="text" name="dni" placeholder="DNI" required>
        <input type="text" name="motivo" placeholder="Motivo de la visita" required>
        <input type="text" name="persona_visita" placeholder="Persona a la que visita" required>
        <button type="submit" name="registrar">Registrar Ingreso</button>
    </form>

    <h3>Registrar Salida</h3>
    <form method="POST" class="form-single-column">
        <input type="text" name="dni" placeholder="DNI para marcar salida" required>
        <button type="submit" name="salida">Registrar Salida</button>
    </form>

    <h3>Filtrar y Listar Visitas</h3>
    <form method="GET" class="form-filter">
        <label for="fecha_filtro">Fecha:</label>
        <input type="date" name="fecha" id="fecha_filtro">
        <label for="persona_filtro">Persona Visitada:</label>
        <input type="text" name="persona" id="persona_filtro" placeholder="Nombre de la persona visitada">
        <button type="submit">Filtrar Visitas</button>
    </form>

    <?php
    $where = [];
    $params = [];
    $types = '';

    if (!empty($_GET['fecha'])) {
        $where[] = "DATE(hora_ingreso) = ?";
        $params[] = $_GET['fecha'];
        $types .= 's';
    }
    if (!empty($_GET['persona'])) {
        $where[] = "persona_visita LIKE ?";
        $params[] = '%' . $_GET['persona'] . '%';
        $types .= 's';
    }

    $condiciones = count($where) ? 'WHERE ' . implode(' AND ', $where) : '';
    $sql_select = "SELECT * FROM visitas $condiciones ORDER BY hora_ingreso DESC";

    $stmt_select = $conexion->prepare($sql_select);
    if (!empty($params)) {
        $stmt_select->bind_param($types, ...$params);
    }
    $stmt_select->execute();
    $resultado_select = $stmt_select->get_result();

    if ($resultado_select->num_rows > 0) {
        
        echo '<div class="export-buttons">';
       
        echo '<a href="pdf.php" target="_blank" class="export-pdf">📄 Exportar a PDF</a>';
       
        echo '<a href="csv.php" target="_blank" class="export-csv">📤 Exportar a CSV</a>';
        echo '</div>';
        

        echo "<table>
        <thead>
            <tr>
                <th>Nombre Completo</th>
                <th>DNI</th>
                <th>Motivo</th>
                <th>Persona Visitada</th>
                <th>Ingreso</th>
                <th>Egreso</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>";

        while ($row = $resultado_select->fetch_assoc()) {
            echo "<tr>
                <td>" . htmlspecialchars($row['nombre'] . ' ' . $row['apellido']) . "</td>
                <td>" . htmlspecialchars($row['dni']) . "</td>
                <td>" . htmlspecialchars($row['motivo']) . "</td>
                <td>" . htmlspecialchars($row['persona_visita']) . "</td>
                <td>" . htmlspecialchars($row['hora_ingreso']) . "</td>
                <td>" . htmlspecialchars($row['hora_egreso'] ?? 'PENDIENTE') . "</td>
                <td class='acciones'>
                    <a href='editar.php?id=" . htmlspecialchars($row['id']) . "'>Editar</a>
                    <a href='eliminar.php?id=" . htmlspecialchars($row['id']) . "' onclick=\"return confirm('¿Eliminar este registro?')\">Eliminar</a>
                </td>
            </tr>";
        }
        echo "</tbody></table>";
    } else {
        echo "<p style='text-align: center; color: #777; margin-top: 20px;'>No hay registros de visitas que coincidan con el filtro.</p>";
        
    }
	
	if (isset($_POST['registrar_ingreso'])) {
    $nombre = trim($_POST['nombre']);
    $apellido = trim($_POST['apellido']);
    $dni = trim($_POST['dni']);
    $motivo = trim($_POST['motivo']);
    $persona = trim($_POST['persona_visita']);

    // Validaciones
    if (empty($nombre) || empty($apellido) || empty($dni) || empty($motivo) || empty($persona)) {
        $error_message = "Todos los campos son obligatorios.";
    } elseif (!ctype_alpha(str_replace(' ', '', $nombre))) { 
        $error_message = "El nombre solo debe contener letras.";
    } elseif (!is_numeric($dni) || strlen($dni) < 7 || strlen($dni) > 9) { 
        $error_message = "El DNI debe ser un número válido de 7 a 9 dígitos.";
    } else {
        
    }
}
    $stmt_select->close();
    ?>
	<?php $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); ?>
<input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

    <h3>Reiniciar Sistema</h3>
    <p style="color: #e74c3c; font-weight: bold;">¡CUIDADO! Esta acción eliminará TODOS los registros de visitas de forma permanente.</p>
    <form method="POST" class="form-reset-system">
        <button type="submit" name="reiniciar" onclick="return confirm('¿Estás SEGURO/A que quieres borrar TODOS los registros? Esta acción es irreversible.')">Reiniciar Sistema</button>
    </form>

</div> </body>
</html>