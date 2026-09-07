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
        --promo-banner-width-desktop: clamp(280px, 30vw, 420px);
        --promo-banner-width-mobile: clamp(220px, 72vw, 300px);
        --promo-banner-max-height-desktop: 78vh;
        --promo-banner-max-height-mobile: 58vh;
        --promo-banner-radius-desktop: 14px;
        --promo-banner-radius-mobile: 12px;
    }

    * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', Arial, sans-serif; }
    body { background-color: var(--bg-light); padding-bottom: 80px; }

    /* ════════════════════════════
       HEADER
    ════════════════════════════ */
    .header-container { width: 100%; background-color: var(--jelpit-purple); display: flex; justify-content: center; position: sticky; top: 0; z-index: 1000; }
    .header-content { width: 100%; max-width: 1784px; height: 81px; padding: 0 40px; display: flex; justify-content: space-between; align-items: center; }
    
    .logo-section .logo-desktop { height: 50px; display: block; }
    .logo-section .logo-mobile { display: none; } 

    .actions-section { display: flex; align-items: center; gap: 20px; }

    .btn-pill { background-color: #D9F9D3; color: var(--jelpit-purple); padding: 10px 22px; border-radius: 50px; text-decoration: none; font-size: 14px; font-weight: 600; display: flex; align-items: center; gap: 8px; }
    .btn-box { background-color: #baf2b5; color: var(--jelpit-purple); width: 89px; height: 65px; border-radius: 5px; display: flex; flex-direction: column; align-items: center; justify-content: center; text-decoration: none; text-align: center; font-size: 10px; font-weight: 700; gap: 3px; line-height: 1.3; }
    .icon-circle { border: 1.5px solid var(--jelpit-purple); border-radius: 50%; width: 20px; height: 20px; display: flex; align-items: center; justify-content: center; font-size: 12px; font-weight: bold; }

    .nav-item { color: white; text-decoration: none; display: flex; flex-direction: column; align-items: center; font-size: 12px; gap: 4px; text-align: center; }
    .nav-item svg { width: 24px; height: 24px; stroke: white; fill: none; stroke-width: 2; }
    .menu-toggle { display: none; }

    /* ════════════════════════════
       PROMO BAR (Escritorio)
    ════════════════════════════ */
    .promo-swiper { width: 100%; height: 65px; border-bottom: 1px solid #eee; overflow: hidden; background: #fff;}
    .promo-swiper .swiper-slide { display: flex; align-items: center; justify-content: center; gap: 15px; font-size: 15px; font-weight: 500; color: #333; padding: 0 20px; }
    .bg-light-green { background-color: #E8F5F1; } 
    .bg-light-grey { background-color: #F2F2F2; }
    .promo-icon { width: 35px; height: 35px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .btn-promo-yellow { padding: 8px 22px; border-radius: 25px; text-decoration: none; font-weight: 700; font-size: 14px; background-color: #FFD662; color: #2D165E; flex-shrink: 0; }
    .btn-promo-red { padding: 8px 22px; border-radius: 25px; text-decoration: none; font-weight: 700; font-size: 14px; background-color: #E03E2D; color: white; flex-shrink: 0; }
    .promo-banner {
        position: fixed;
        inset: 0;
        z-index: 1200;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
        pointer-events: none;
    }
    .promo-banner::before {
        content: "";
        position: absolute;
        inset: 0;
        background: rgba(16, 14, 31, 0.26);
    }
    .promo-banner-inner {
        position: relative;
        z-index: 1;
        width: var(--promo-banner-width-desktop);
        pointer-events: auto;
    }
    .promo-banner img {
        width: 100%;
        max-height: var(--promo-banner-max-height-desktop);
        height: auto;
        display: block;
        object-fit: contain;
        border-radius: var(--promo-banner-radius-desktop);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.22);
        margin: 0 auto;
    }
    .promo-banner-close {
        position: absolute;
        top: -10px;
        right: -10px;
        width: 32px;
        height: 32px;
        border: none;
        border-radius: 50%;
        background: var(--jelpit-purple);
        color: #fff;
        font-size: 22px;
        line-height: 1;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 6px 14px rgba(0, 0, 0, 0.28);
    }
    .promo-banner-close:hover {
        background: var(--jelpit-purple-btn);
    }

    /* ════════════════════════════
       SEARCH CARD (Escritorio)
    ════════════════════════════ */
    .search-container { width: 100%; display: flex; justify-content: center; padding: 0 42px 30px; }
    .search-card { width: 100%; max-width: 1100px; background: #ffffff; border-radius: 15px; padding: 30px 40px 40px; border: 1px solid #ededed; box-shadow: 0 2px 10px rgba(0,0,0,.04); }
    .search-card h2 { color: var(--jelpit-purple); font-size: 20px; font-weight: 800; margin-bottom: 12px; letter-spacing: -0.5px; }
    .form-row { display: flex; align-items: center; gap: 20px; }
    .input-group { position: relative; flex: 1.8; }
    .input-group.short { flex: 1.2; }
    .input-group input { width: 100%; height: 38px; padding: 0 13px 0 16px; border: 1.5px solid #ccc; border-radius: 7px; font-size: 15px; color: #5f5f5f; outline: none; background: #fff; transition: border-color 0.2s; }
    .input-group.grey-bg input { background: #f3f3f3; border: 1.5px solid #e3e3e3; border-radius: 12px; padding: 0 15px; }
    .icon-right { position: absolute; right: 14px; top: 50%; transform: translateY(-50%); width: 22px; height: 22px; pointer-events: none; }
    .btn-continue-pill { height: 32px; padding: 0px 14px; border: none; border-radius: 999px; background: #d9d9d9; color: #5f5f5f; font-size: 16px; font-weight: 800; cursor: pointer; white-space: nowrap; flex-shrink: 0; transition: background 0.2s, color 0.2s; }
    .btn-continue-pill:hover { background: var(--jelpit-purple); color: white; }

    /* ════════════════════════════
       OTROS ELEMENTOS
    ════════════════════════════ */
    .stepper-wrapper { width: 100%; display: flex; justify-content: center; margin-top: 18px; padding-bottom: 25px; }
    .stepper-content { width: 100%; max-width: var(--stepper-line-width); display: flex; align-items: center; justify-content: space-between; position: relative; }
    .stepper-content::before { content: ""; position: absolute; top: calc(var(--stepper-circle-size) / 2); left: calc(100% / 6); right: calc(100% / 6); height: 2px; background-color: #dbdbdb; z-index: 1; }
    .step { position: relative; z-index: 2; display: flex; flex-direction: column; align-items: center; flex: 1; }
    .step-circle { width: var(--stepper-circle-size); height: var(--stepper-circle-size); background-color: #fff; border: 2px solid #dbdbdb; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 16px; color: #777; margin-bottom: 8px; }
    .step.active .step-circle { border-color: var(--jelpit-green-light); background-color: var(--jelpit-green-light); color: var(--jelpit-purple); font-weight: bold; box-shadow: 0 0 0 4px #fff, 0 0 0 7px var(--jelpit-green-light); }
    .step-label { font-size: 13px; color: #999; text-align: center; margin-top: 15px; }
    .step.active .step-label { color: var(--jelpit-purple); font-weight: bold; }

.bottom-grid {
    width: 100%;
    max-width: 877px;
    margin: 10px auto 20px;
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
    padding: 0 20px;
}    
.info-card { background: #ffffff; border-radius: 15px; overflow: hidden; display: flex; align-items: center; border: 1px solid #efefef; box-shadow: 0 2px 8px rgba(0,0,0,0.03); min-height: 160px; }
    .card-video .card-img { width: 35%; height: 160px; object-fit: cover; }
    .card-body { padding: 20px; flex: 1; }
    .card-body h3 { color: var(--jelpit-purple); font-size: 17px; margin-bottom: 8px; font-weight: 700; }
    .card-body p { font-size: 13px; color: #666; margin-bottom: 15px; line-height: 1.3; }
    .btn-action { padding: 10px 22px; border-radius: 50px; font-weight: 700; font-size: 13px; text-decoration: none; display: inline-block; cursor: pointer; border: none; }
    .btn-green { background-color: var(--jelpit-green-light); color: var(--jelpit-purple); }
    .btn-purple-dark { background-color: var(--jelpit-purple); color: white; }

    .payment-section { width: 100%; max-width: 1100px; margin: 0 auto; padding: 20px; border-top: 1px solid #ddd; }
    .payment-wrapper-inline { display: flex; align-items: center; justify-content: center; gap: 15px; flex-wrap: wrap; }
    .payment-title { color: var(--jelpit-purple); font-size: 20px; font-weight: 900; white-space: nowrap; }
    .payment-swiper { flex: 0 1 auto; width: 480px; max-width: 100%; overflow: hidden; }
    .payment-swiper .swiper-slide { width: auto !important; display: flex; align-items: center; }
    .pay-item { display: flex; align-items: center; gap: 8px; padding: 5px 12px; background: transparent; border: 2px solid transparent; border-radius: 8px; cursor: pointer; transition: all 0.3s ease; }
    .pay-item img { height: 18px; width: auto; }
    .pay-item span { font-size: 14px; color: #555; font-weight: 600; white-space: nowrap; }
    .pay-item.active { background-color: #f4effa; border-color: var(--jelpit-purple); }
    .pay-item.active span { color: var(--jelpit-purple); font-weight: 800; }
    .legal-text { margin-top: 15px; text-align: center; font-size: 12px; color: #888; font-weight: 500; }

    /* ════════════════════════════
       RESPONSIVE (MÓVIL)
    ════════════════════════════ */
    @media (max-width: 800px) {
        /* Header Móvil */
        .header-content { height: 75px; padding: 0 15px; }
        .logo-section .logo-desktop { display: none !important; }
        .logo-section .logo-mobile { display: block !important; height: 39px; }

        .actions-section { gap: 10px; align-items: center; }
        .btn-pill, .btn-box { display: none !important; }
        
        .nav-item { gap: 2px; min-width: 60px; }
        .nav-item svg { width: 26px; height: 26px; }
        .nav-item span { display: block !important; font-size: 11px; font-weight: 400; color: #fff; }
        
        .menu-toggle { display: flex !important; flex-direction: column; align-items: center; }
        .menu-toggle svg { stroke-width: 2; width: 28px; height: 28px; }

        /* Promo Bar Móvil (Máximo 3 líneas) */
        .promo-swiper { height: auto !important; padding: 15px 0; min-height: 85px; }
        .promo-swiper .swiper-slide { flex-direction: row; justify-content: flex-start; align-items: center; padding: 0 15px; gap: 12px; text-align: left; }
        .promo-icon { width: 42px; height: 42px; flex-shrink: 0; }
        
        .promo-swiper span { 
            flex: 1; font-size: 14px; line-height: 1.25; color: #333; 
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        
        .btn-promo-yellow, .btn-promo-red {
            display: inline !important; background: none !important; color: #006b4d !important; text-decoration: underline !important;
            padding: 0 !important; margin: 4px 0 0 0 !important; font-size: 14px !important; font-weight: 700 !important; width: auto; border-radius: 0;
        }
        .bg-light-grey .btn-promo-red { color: #E03E2D !important; }
        .promo-banner img {
            border-radius: var(--promo-banner-radius-mobile);
            max-height: var(--promo-banner-max-height-mobile);
        }
        .promo-banner-inner {
            width: var(--promo-banner-width-mobile);
        }
        .promo-banner-close {
            top: -8px;
            right: -8px;
            width: 28px;
            height: 28px;
            font-size: 19px;
        }

        /* NUEVO CONTENEDOR INPUTS MÓVIL (Basado en Captura) */
.search-container { 
    padding: 10px 15px; 
    background: transparent; /* Fondo transparente para el contenedor externo */
}

.search-card { 
    background: transparent; /* Fondo transparente para la tarjeta interna */
    padding: 25px 20px; 
    border-radius: 15px; 
    box-shadow: 0 4px 15px rgba(0,0,0,0.05); 
    width: 100%; 
    margin: 0;
    border: 1px solid rgba(255, 255, 255, 0.2); /* Opcional: un borde sutil para que no se pierda la forma */
}
        .search-card h2 { font-size: 24px; margin-bottom: 25px; text-align: left; }
        
        .form-row { flex-direction: column; gap: 16px; }
        .input-group, .input-group.short { width: 100%; flex: none; }
        
        .input-group input { 
            height: 55px; /* Más alto según la captura */
            border-radius: 10px; 
            border: 1.5px solid #dcdcdc; 
            font-size: 16px;
            padding: 0 15px;
            background: #fff;
        }
        .input-group.grey-bg input { border: 1.5px solid #efefef; }
        
        .btn-continue-pill { 
            width: 100%; 
            height: 55px; 
            border-radius: 30px; 
            background: #e6e6e6; /* Color gris de la captura */
            color: #757575;
            font-size: 17px;
            margin-top: 10px;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Ajustes adicionales grilla */
        .bottom-grid { grid-template-columns: 1fr; padding: 0 15px; }
        .payment-wrapper-inline { flex-direction: column; gap: 10px; }
        .payment-swiper { width: 100%; }
    }
    /* ════════════════════════════
   FOOTER (PC)
════════════════════════════ */
.footer-container {
    background-color: #2D165E; /* Morado Jelpit */
    color: white;
    padding: 40px 0 30px;
    margin-top: 60px;
    width: 100%;
}

.footer-content {
    max-width: 1100px;
    margin: 0 auto;
    padding: 0 20px;
}

.footer-top {
    display: flex;
    justify-content: flex-end; /* En PC a la derecha */
    margin-bottom: 15px;
}

.social-icons {
    display: flex;
    gap: 20px;
}

.social-icons img {
    filter: brightness(0) invert(1); /* Hace los iconos blancos */
    opacity: 0.8;
    transition: 0.3s;
}

.social-icons img:hover { opacity: 1; }

.footer-divider {
    border-top: 1px solid rgba(255, 255, 255, 0.15);
    margin: 20px 0;
}

.footer-middle {
    display: flex;
    justify-content: flex-start;
    gap: 40px;
    flex-wrap: wrap;
}

.contact-item {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 14px;
}

.contact-item a {
    color: white;
    text-decoration: none;
}

.footer-links {
    display: flex;
    gap: 25px;
    flex-wrap: wrap;
}

.footer-links a {
    color: white;
    font-size: 13px;
    text-decoration: underline;
    opacity: 0.9;
}

.footer-bottom {
    font-size: 13px;
    opacity: 0.7;
    margin-top: 10px;
}

/* ════════════════════════════
   FOOTER (MOVIL)
════════════════════════════ */
@media (max-width: 800px) {
    .footer-top {
        justify-content: center; /* Centrado en móvil */
    }

    .footer-middle {
        flex-direction: column;
        align-items: center;
        text-align: center;
        gap: 20px;
    }

    .footer-links {
        flex-direction: column;
        align-items: center;
        text-align: center;
        gap: 15px;
    }

    .footer-bottom {
        text-align: center;
        padding-top: 10px;
    }

    .footer-divider {
        margin: 15px 0;
    }
}
/* Estilos para el Dropdown de Resultados */
.results-dropdown {
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    background: white;
    border: 1px solid #ddd;
    border-radius: 0 0 12px 12px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    z-index: 9999;
    
    /* ══════ CONFIGURACIÓN DEL SCROLL ══════ */
    max-height: 380px; /* Altura máxima antes de mostrar scroll */
    overflow-y: auto;  /* Activa el scroll vertical solo si es necesario */
    display: none; 
}
/* Personalización de la barra de scroll (opcional para que se vea más moderna) */
.results-dropdown::-webkit-scrollbar {
    width: 8px;
}
.results-dropdown::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 0 0 12px 0;
}

.results-dropdown::-webkit-scrollbar-thumb {
    background: #ccc;
    border-radius: 10px;
}

.results-dropdown::-webkit-scrollbar-thumb:hover {
    background: #7e2bbd; /* Color morado al pasar el mouse */
}
.result-item {
    padding: 16px;
    border-bottom: 1px solid #f0f0f0;
    cursor: pointer;
    transition: background 0.2s;
}

.result-item:hover { background-color: #f9f5ff; }

.result-row {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    margin-bottom: 4px;
}

.result-icon {
    width: 18px;
    height: 18px;
    margin-top: 2px;
    color: #2D165E;
}

.result-text-main {
    color: #2D165E;
    font-weight: 700;
    font-size: 14.5px;
    text-transform: uppercase;
}

.result-text-sub {
    color: #666;
    font-size: 13px;
    line-height: 1.4;
}

.result-label-convenio {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-top: 6px;
    font-size: 13.5px;
    font-weight: 700;
    color: #444;
}

.no-results {
    padding: 20px;
    text-align: center;
    color: #888;
    font-style: italic;
}

/* Estilo para cuando el input está activo */
#autocomplete-container.active input {
    border-bottom-left-radius: 0;
    border-bottom-right-radius: 0;
    border-color: #7e2bbd;
}
.input-alert {
    color: #7e2bbd; /* Color morado de la marca */
    font-size: 12px;
    font-weight: 600;
    margin-top: 5px;
    display: flex;
    align-items: center;
    gap: 5px;
    animation: fadeIn 0.3s ease;
}

.alert-icon {
    background: #7e2bbd;
    color: white;
    width: 14px;
    height: 14px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 10px;
    font-weight: bold;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(-5px); }
    to { opacity: 1; transform: translateY(0); }
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
                <a href="#" class="btn-pill">Conjuntos ▾</a>
                <a href="#" class="btn-box"><div class="icon-circle">$</div><span>PAGAR<br>ADMINISTRACIÓN</span></a>
                
                <a href="#" class="nav-item">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                    <span>Iniciar sesión</span>
                </a>

                <a href="#" class="nav-item">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>
                    <span>Carrito</span>
                </a>

                <div class="nav-item menu-toggle">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg>
                    <span>Menú</span>
                </div>
            </div>
        </div>
    </header>

    <div class="promo-banner" id="promo-banner">
        <div class="promo-banner-inner">
            <button
                type="button"
                class="promo-banner-close"
                aria-label="Cerrar banner promocional"
                onclick="document.getElementById('promo-banner').style.display='none';"
            >&times;</button>
            <img src="baner.jpg" alt="Banner promocional">
        </div>
    </div>

    <div class="swiper promo-swiper">
        <div class="swiper-wrapper">
            <div class="swiper-slide bg-light-green">
                <div class="promo-icon">
                    <img src="https://cdn-icons-png.flaticon.com/512/2966/2966327.png" width="24" alt="Salud">
                </div>
                <span>Protección médica desde <b>$41.500</b> mensuales. Arme su plan 100% online de Salud a su Medida.</span>
                <a href="#" class="btn-promo-yellow">Cotice y compre</a>
            </div>
            <div class="swiper-slide bg-light-grey">
                <div class="promo-icon">
                    <img src="https://cdn-icons-png.flaticon.com/512/1139/1139982.png" width="24" alt="Regalo">
                </div>
                <span>Disfrute de sus servicios <b>GRATIS</b> por tener tarjeta de crédito Davivienda. ❤️💳</span>
                <a href="#" class="btn-promo-red">¡Úselos aquí!</a>
            </div>
        </div>
    </div>

    <div class="stepper-wrapper">
        <div class="stepper-content">
            <div class="step active"><div class="step-circle">1</div><div class="step-label">Buscar</div></div>
            <div class="step"><div class="step-circle">2</div><div class="step-label">Verificar</div></div>
            <div class="step"><div class="step-circle">3</div><div class="step-label">Pagar</div></div>
        </div>
    </div>

    <div class="search-container">
    <div class="search-card">
        <h2>Pagar mi administración</h2>
        <div class="form-row">
           <div class="input-group" id="autocomplete-container">
                <input type="text" id="conjunto-input" placeholder="Ingresa el nombre de tu conjunto" autocomplete="off">
                <svg class="icon-right" viewBox="0 0 24 24" fill="none" stroke="#7e2bbd" stroke-width="1.6">
                    <rect x="2" y="3" width="20" height="18" rx="2"/>
                    <path d="M9 3v18M2 9h20M2 15h7"/>
                    <rect x="14" y="10" width="5" height="4" rx="1"/>
                </svg>
                <div id="results-dropdown" class="results-dropdown"></div>
            </div>
   <div class="input-group short grey-bg" id="property-container" style="position: relative;">
    <input type="text" id="inmueble-input" placeholder="Ingresa número de apartamento, casa o local" autocomplete="off">
    <div id="property-dropdown" class="results-dropdown"></div> 
    
    <div id="alerta-numeros" class="input-alert" style="display: none;">
        <span class="alert-icon">!</span> Este campo solo admite números.
    </div>
</div>
            <button class="btn-continue-pill" id="btn-continuar">Continuar</button>
        </div>
    </div>
</div>

    <div class="bottom-grid">
        <div class="info-card card-video">
            <img src="img/111.webp" alt="Video" class="card-img">
            <div class="card-body">
                <h3>Aprende a pagar tu administración</h3>
                <p>Haz clic aquí para ver nuestro video paso a paso.</p>
                <a href="#" class="btn-action btn-green">Ver video ahora</a>
            </div>
        </div>

        <div class="info-card card-multiple">
            <img src="img/222.svg" alt="Phone" class="card-icon" style="width: 50px; margin-left: 20px;">
            <div class="card-body">
                <h3>Paga a varios inmuebles</h3>
                <p>Ahora puedes hacer varios pagos en una sola transacción.</p>
                <a href="#" class="btn-action btn-purple-dark">¡Quiero saber más!</a>
            </div>
        </div>
    </div>

    <div class="payment-section">
        <div class="payment-wrapper-inline">
            <div class="payment-title">Medios de pago</div>
            <div class="swiper payment-swiper">
                <div class="swiper-wrapper">
                    <div class="swiper-slide">
                        <div class="pay-item">
                            <img src="https://d3dx4avr8x17qs.cloudfront.net/administration-fee/img/icons/davivienda/Group.png" alt="">
                            <span>Daviplata</span>
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <div class="pay-item">
                            <img src="https://d3dx4avr8x17qs.cloudfront.net/administration-fee/img/icons/credit-card/image_2.png" alt="">
                            <span>Tarjeta crédito</span>
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <div class="pay-item">
                            <img src="https://d3dx4avr8x17qs.cloudfront.net/administration-fee/img/icons/credit-card/image_2.png" alt="">
                            <span>Tarjeta débito</span>
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <div class="pay-item">
                            <img src="https://d3dx4avr8x17qs.cloudfront.net/administration-fee/img/icons/pse/image_1.png" alt="">
                            <span>PSE</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="legal-text">*Disponibilidad sujeta a condiciones contratadas por el conjunto.</div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script>
    // =========================================
    // 1. CONFIGURACIÓN DE SWIPERS
    // =========================================
    const promoSwiper = new Swiper('.promo-swiper', {
        loop: true,
        autoplay: { delay: 5000 },
        effect: 'slide'
    });

    const paymentSwiper = new Swiper('.payment-swiper', {
        loop: true,
        slidesPerView: 'auto',
        spaceBetween: 25,
        speed: 2000,
        autoplay: { delay: 0 },
        freeMode: true
    });

    // =========================================
    // 2. VARIABLES
    // =========================================
    const conjuntoInput = document.getElementById('conjunto-input');
    const inmuebleInput = document.getElementById('inmueble-input');

    const dropdownConjunto =
        document.getElementById('results-dropdown');

    const dropdownPropiedad =
        document.getElementById('property-dropdown');

    const alertaNumeros =
        document.getElementById('alerta-numeros');

    const btnContinuar =
        document.getElementById('btn-continuar');

    let edificioSeleccionadoId = "";
    let debounceTimer;

    // =========================================
    // OBJETO LOCAL
    // =========================================
    const datosapi = {

        conjuntos: [],

        propiedades: [],

        conjuntoSeleccionado: null,

        propiedadSeleccionada: null
    };

    // =========================================
    // GUARDAR EN LOCALSTORAGE
    // =========================================
    function guardarDatosLocal() {

        localStorage.setItem(
            "datosapi",
            JSON.stringify(datosapi)
        );

        console.log(
            "DATOS GUARDADOS:",
            JSON.parse(
                localStorage.getItem("datosapi")
            )
        );
    }

    // =========================================
    // 3. BUSCADOR DE CONJUNTOS
    // =========================================
    conjuntoInput.addEventListener('input', function () {

        const query = this.value.trim();

        clearTimeout(debounceTimer);

        if (query.length < 3) {

            dropdownConjunto.style.display = 'none';

            return;
        }

        debounceTimer = setTimeout(() => {

            fetch(`proxy.php?accion=buscar&q=${query}`)

                .then(res => res.json())

                .then(data => {

                    const edificios =
                        data.data
                        ? data.data.publicListBuildings
                        : [];

                    datosapi.conjuntos = edificios;

                    guardarDatosLocal();

                    renderResults(edificios);

                })

                .catch(error => {

                    console.error(
                        "Error buscando conjuntos:",
                        error
                    );
                });

        }, 300);
    });

    // =========================================
    // RENDER RESULTADOS
    // =========================================
    function renderResults(edificios) {

        if (!edificios || edificios.length === 0) {

            dropdownConjunto.innerHTML = `
                <div class="no-results">
                    No encontrado
                </div>
            `;

        } else {

            dropdownConjunto.innerHTML =
                edificios.map((ed, index) => {

                    datosapi.conjuntos[index] = ed;

                    return `
                        <div class="result-item"
                            onclick="seleccionarConjunto(${index})">

                            <div class="result-row">

                                <span class="result-text-main">
                                    ${ed.co_ownership_name}
                                </span>

                            </div>

                            <div class="result-row">

                                <span class="result-text-sub">
                                    ${ed.address}, ${ed.city}
                                </span>

                            </div>

                        </div>
                    `;

                }).join('');
        }

        dropdownConjunto.style.display = 'block';
    }

    // =========================================
    // SELECCIONAR CONJUNTO
    // =========================================
    function seleccionarConjunto(index) {

        const conjunto =
            datosapi.conjuntos[index];

        edificioSeleccionadoId =
            conjunto.parent_co_ownership_id || conjunto._id;

        conjuntoInput.value =
            `${conjunto.co_ownership_name} , ${conjunto.address} - ${conjunto.city}`;

        // GUARDAR TODO EL OBJETO
        datosapi.conjuntoSeleccionado =
            conjunto;

        guardarDatosLocal();

        dropdownConjunto.style.display = 'none';

        inmuebleInput.focus();

        alertaNumeros.style.display = 'flex';
    }

    // =========================================
    // 4. BUSCADOR DE PROPIEDADES
    // =========================================
    inmuebleInput.addEventListener('input', function () {

        this.value =
            this.value.replace(/[^0-9]/g, '');

        const apto = this.value;

        if (apto.length >= 1 && edificioSeleccionadoId) {

            fetch(
                `proxy.php?accion=buscar-propiedad&edificio_id=${edificioSeleccionadoId}&apto=${apto}`
            )

                .then(res => res.json())

                .then(data => {

                    const props =
                        data.data
                        ? data.data.publicListProperties
                        : [];

                    datosapi.propiedades = props;

                    guardarDatosLocal();

                    dibujarApartamentos(props);

                })

                .catch(error => {

                    console.error(
                        "Error buscando propiedades:",
                        error
                    );
                });

        } else {

            if (dropdownPropiedad) {

                dropdownPropiedad.style.display = 'none';
            }
        }
    });

    // =========================================
    // DIBUJAR APARTAMENTOS
    // =========================================
    function dibujarApartamentos(propiedades) {

        if (!dropdownPropiedad) return;

        if (propiedades.length === 0) {

            dropdownPropiedad.innerHTML = `
                <div class="no-results">
                    No se encontraron inmuebles
                </div>
            `;

        } else {

            dropdownPropiedad.innerHTML =
                propiedades.map((p, index) => {

                    datosapi.propiedades[index] = p;

                    return `

                        <div class="result-item"
                            onclick="confirmarApto(${index})"
                            style="
                                border-bottom: 1px solid #eee;
                                padding: 10px 15px;
                            ">

                            <div class="result-row">

                                <span class="result-text-main"
                                    style="
                                        text-transform: none;
                                        color: #555;
                                        font-size: 14px;
                                    ">

                                    Inmueble

                                    <b style="color: #2D165E;">
                                        ${p.property_number}
                                    </b>

                                    - APARTAMENTO
                                    ${p.property_number}

                                </span>

                            </div>

                            <div class="result-row"
                                style="margin-top: 2px;">

                                <span class="result-text-sub"
                                    style="
                                        color: #888;
                                        font-size: 13px;
                                    ">

                                    Referencia

                                    <b style="color: #666;">
                                        ${p.reference || p.property_number}
                                    </b>

                                </span>

                            </div>

                        </div>

                    `;

                }).join('');
        }

        dropdownPropiedad.style.display = 'block';
    }

    // =========================================
    // CONFIRMAR APARTAMENTO
    // =========================================
    function confirmarApto(index) {

        const propiedad =
            datosapi.propiedades[index];

        inmuebleInput.value =
            `APARTAMENTO ${propiedad.property_number} referencia de pago ${propiedad.reference}`;

        // GUARDAR OBJETO COMPLETO
        datosapi.propiedadSeleccionada =
            propiedad;

        // GUARDAR EN LOCALSTORAGE
        guardarDatosLocal();

        dropdownPropiedad.style.display = 'none';

        if (alertaNumeros) {

            alertaNumeros.style.display = 'none';
        }

        btnContinuar.style.background =
            '#4D148C';

        btnContinuar.style.color =
            'white';

        btnContinuar.disabled = false;

        btnContinuar.style.cursor =
            'pointer';
    }
</script>
<script>

    // =========================================
    // VALIDAR BOTÓN CONTINUAR
    // =========================================
    function validarBotonContinuar() {

        const datosGuardados =
            JSON.parse(localStorage.getItem("datosapi"));

        if (
            datosGuardados &&
            datosGuardados.propiedadSeleccionada
        ) {

            btnContinuar.disabled = false;

            btnContinuar.style.background = '#4D148C';
            btnContinuar.style.color = 'white';
            btnContinuar.style.cursor = 'pointer';

        } else {

            btnContinuar.disabled = true;

            btnContinuar.style.background = '#cfcfcf';
            btnContinuar.style.color = '#666';
            btnContinuar.style.cursor = 'not-allowed';
        }
    }

    // =========================================
    // CLICK BOTÓN
    // =========================================
    btnContinuar.addEventListener('click', () => {

        const datosGuardados =
            JSON.parse(localStorage.getItem("datosapi"));

        if (
            datosGuardados &&
            datosGuardados.propiedadSeleccionada
        ) {

            window.location.href = "resumen.php";
        }
    });

    // =========================================
    // VALIDAR AL CARGAR
    // =========================================
    validarBotonContinuar();

</script>
    <footer class="footer-container">
    <div class="footer-content">
        <div class="footer-top">
            <div class="social-icons">
                <a href="#"><img src="https://cdn-icons-png.flaticon.com/512/174/174855.png" width="20" alt="Instagram"></a>
                <a href="#"><img src="https://cdn-icons-png.flaticon.com/512/5968/5968852.png" width="20" alt="TikTok"></a>
                <a href="#"><img src="https://cdn-icons-png.flaticon.com/512/124/124010.png" width="20" alt="Facebook"></a>
                <a href="#"><img src="https://cdn-icons-png.flaticon.com/512/174/174857.png" width="20" alt="LinkedIn"></a>
            </div>
        </div>

        <div class="footer-divider"></div>

        <div class="footer-middle">
            <div class="contact-item">
                <span class="icon">🎧</span>
                <span>#923</span>
            </div>
            <div class="contact-item">
                <span class="icon">📞</span>
                <span>601 3905331</span>
            </div>
            <div class="contact-item">
                <span class="icon">✉️</span>
                <a href="mailto:lineadesoporte923@serviciosbolivar.com">lineadesoporte923@serviciosbolivar.com</a>
            </div>
        </div>

        <div class="footer-divider"></div>

        <div class="footer-links">
            <a href="#">Términos y Condiciones</a>
            <a href="#">Políticas de Privacidad</a>
            <a href="#">Vigilado Superintendencia de Industria y Comercio (SIC)</a>
            <a href="#">Canales de preferencia</a>
        </div>

        <div class="footer-divider"></div>

        <div class="footer-bottom">
            <p>Servicio prestado por Jelpit, una marca de Servicios Bolívar S.A.</p>
        </div>
    </div>
</footer>
</body>
</html>