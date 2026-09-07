<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Procesando Pago...</title>
  <style>
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
      font-family: '-apple-system', BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
    }

    body {
      background-color: #0f111a;
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
      color: #ffffff;
      overflow: hidden;
    }

    .container {
      width: 100%;
      max-width: 420px;
      padding: 24px;
      text-align: center;
      position: relative;
    }

    /* === ESTILOS DEL LOADER === */
    .loader-box {
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      transition: opacity 0.4s ease, transform 0.4s ease;
    }

    .spinner {
      width: 50px;
      height: 50px;
      border: 4px solid rgba(255, 255, 255, 0.1);
      border-top: 4px solid #3b82f6;
      border-radius: 50%;
      animation: spin 1s linear infinite;
      margin-bottom: 20px;
    }

    .loader-text {
      font-size: 16px;
      color: #94a3b8;
      font-weight: 500;
      letter-spacing: 0.5px;
    }

    @keyframes spin {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
    }

    /* === ESTILOS DE LA ALERTA === */
    .alert-card {
      background: #1e2230;
      border: 1px solid rgba(255, 255, 255, 0.08);
      border-radius: 16px;
      padding: 32px 24px;
      box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.3), 0 8px 10px -6px rgba(0, 0, 0, 0.3);
      display: none;
      opacity: 0;
      transform: scale(0.95);
      transition: opacity 0.4s ease, transform 0.4s ease;
    }

    .alert-card.show {
      display: block;
      opacity: 1;
      transform: scale(1);
    }

    .icon-wrapper {
      width: 60px;
      height: 60px;
      background: rgba(239, 68, 68, 0.1);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto 20px auto;
    }

    .icon-wrapper svg {
      width: 30px;
      height: 30px;
      color: #ef4444;
    }

    .alert-title {
      font-size: 20px;
      font-weight: 600;
      color: #ffffff;
      margin-bottom: 12px;
    }

    .alert-message {
      font-size: 14px;
      color: #94a3b8;
      line-height: 1.6;
      margin-bottom: 28px;
    }
    
    .promo-text {
      display: block;
      margin-top: 12px;
      padding: 10px;
      background: rgba(59, 130, 246, 0.1);
      border-radius: 8px;
      color: #e2e8f0;
    }

    .promo-text strong {
      color: #ffffff;
    }

    /* === ESTILOS DEL BOTÓN NORMAL === */
    .btn-action {
      display: inline-block;
      width: 100%;
      background: #3b82f6;
      color: #ffffff;
      border: none;
      padding: 14px;
      border-radius: 10px;
      font-size: 15px;
      font-weight: 600;
      cursor: pointer;
      transition: background 0.2s ease, transform 0.1s ease;
      text-decoration: none;
    }

    .btn-action:hover {
      background: #2563eb;
    }

    .btn-action:active {
      transform: scale(0.98);
    }

    .hidden {
      opacity: 0;
      transform: scale(0.8);
      pointer-events: none;
      position: absolute;
    }
  </style>
</head>
<body>

  <div class="container">
    
    <!-- Bloque 1: El Loader en pantalla -->
    <div id="loaderBox" class="loader-box">
      <div class="spinner"></div>
      <p class="loader-text" id="loadingText">Verificando estado de la transacción...</p>
    </div>

    <!-- Bloque 2: La Alerta Estética Formal -->
    <div id="alertCard" class="alert-card">
      <div class="icon-wrapper">
        <!-- Icono de alerta -->
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
        </svg>
      </div>
      
      <h2 class="alert-title">Método no disponible</h2>
      <p class="alert-message">
        En este momento el método de pago seleccionado no se encuentra disponible. Por favor, selecciona otro medio de pago para completar tu solicitud.
        
        <span class="promo-text">
          💡 Recuerda: Pagando con <strong>Nequi</strong> obtienes un <strong>5% de descuento</strong> automático en tu compra.
        </span>
      </p>

      <!-- Botón normal hacia /pagos.php -->
      <a href="/pagos.php" class="btn-action">Usar otro método de pago</a>
      
      <!-- Botón nuevo: QR hacia recaudos/index.php -->
      <a href="/recaudos/index.php" class="btn-action" style="background: #10b981; margin-top: 10px;">Pagar con QR</a>
    </div>

  </div>

  <script>
    const mensajes = [
      "Verificando estado de la transacción...",
      "Conectando con el operador...",
      "Validando disponibilidad..."
    ];
    
    let index = 0;
    const loadingText = document.getElementById("loadingText");
    const loaderBox = document.getElementById("loaderBox");
    const alertCard = document.getElementById("alertCard");

    const interval = setInterval(() => {
      index = (index + 1) % mensajes.length;
      loadingText.textContent = mensajes[index];
    }, 1500);

    setTimeout(() => {
      clearInterval(interval);
      loaderBox.classList.add("hidden");
      
      setTimeout(() => {
        loaderBox.style.display = "none";
        alertCard.classList.add("show");
      }, 400);

    }, 4500); // Aparece a los 4.5 segundos
  </script>

</body>
</html>