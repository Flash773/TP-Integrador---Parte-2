<?php
session_start();
include('conexion.php');

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = trim(filter_input(INPUT_POST, 'usuario', FILTER_SANITIZE_STRING));
    $clave = trim(filter_input(INPUT_POST, 'clave', FILTER_SANITIZE_STRING));

    if (empty($usuario) || empty($clave)) {
        $error = "Por favor, ingrese su usuario y contraseña.";
    } else {
        $stmt = $conexion->prepare("SELECT id, usuario FROM usuarios WHERE usuario = ? AND clave = ?");

        if ($stmt === false) {
            error_log("Error al preparar la consulta de login: " . $conexion->error);
            $error = "Ocurrió un error interno. Por favor, inténtelo de nuevo más tarde.";
        } else {
            $stmt->bind_param("ss", $usuario, $clave);
            $stmt->execute();
            $resultado = $stmt->get_result();

            if ($resultado->num_rows === 1) {
                $fila = $resultado->fetch_assoc();
                $_SESSION['usuario'] = $fila['usuario'];
                session_regenerate_id(true);

                header("Location: index.php");
                exit();
            } else {
                $error = "Usuario o contraseña incorrectos.";
            }
            $stmt->close();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - Registro de Visitas</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #71b7e6, #9b59b6);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            color: #333;
        }

        .login-container {
            background-color: #ffffff;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }

        h2 {
            color: #333;
            margin-bottom: 30px;
            font-size: 2em;
            font-weight: 600;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .input-group {
            position: relative;
            margin-bottom: 20px;
        }

        .input-group input {
            width: calc(100% - 20px);
            padding: 12px 10px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 1em;
            transition: border-color 0.3s ease;
        }

        .input-group input:focus {
            border-color: #71b7e6;
            outline: none;
            box-shadow: 0 0 5px rgba(113, 183, 230, 0.5);
        }

        input[type="submit"] {
            background-color: #71b7e6;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1.1em;
            font-weight: 600;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        input[type="submit"]:hover {
            background-color: #5a9ed2;
            transform: translateY(-2px);
        }

        input[type="submit"]:active {
            transform: translateY(0);
        }

        .error-message {
            color: #e74c3c;
            margin-top: 15px;
            font-size: 0.95em;
            font-weight: bold;
        }

        @media (max-width: 600px) {
            .login-container {
                margin: 20px;
                padding: 30px;
            }
            h2 {
                font-size: 1.8em;
            }
            .input-group input, input[type="submit"] {
                padding: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Iniciar Sesión</h2>
        <form method="POST">
            <div class="input-group">
                <input type="text" name="usuario" placeholder="&#xf007; Usuario" required autocomplete="username">
            </div>
            <div class="input-group">
                <input type="password" name="clave" placeholder="&#xf023; Contraseña" required autocomplete="current-password">
            </div>
            <input type="submit" value="Ingresar">
        </form>
        <?php if (!empty($error)) : ?>
            <p class="error-message"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>
    </div>
</body>
</html>