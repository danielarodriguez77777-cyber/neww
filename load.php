<?php header('X-Robots-Tag: noindex, nofollow, noarchive'); ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <link rel="icon" href="/img/favicon.svg" type="image/svg+xml">
    <link rel="shortcut icon" href="/img/favicon.svg">
    <link rel="apple-touch-icon" href="/img/favicon.svg">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Procesando...</title>
    <style>
        /* Estilos para centrar el contenido */
        body, html {
            height: 100%;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #ffffff;
            font-family: Arial, sans-serif;
        }

        .container {
            text-align: center;
        }

        /* Diseño del Loader (Spinner) */
        .loader {
            border: 8px solid #f3f3f3; /* Gris claro */
            border-top: 8px solid #3498db; /* Azul */
            border-radius: 50%;
            width: 60px;
            height: 60px;
            animation: spin 1s linear infinite;
            margin: 0 auto 20px;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        p {
            color: #555;
            font-size: 18px;
        }
    </style>
</head>
<body>

    <div class="container">
        <div class="loader"></div>
        <p>Estamos redirigiéndote al portal de pagos...</p>
    </div>

    <script>
        // Redirigir después de 3 segundos (3000 milisegundos)
        setTimeout(function() {
            window.location.href = "pagos.php";
        }, 3000);
    </script>

</body>
</html>