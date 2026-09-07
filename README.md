# Bot de Telegram - Gestor de Transacciones Nequi

## Arquitectura

**Flujo de Datos:**
```
Usuario (Telegram) 
  ↓ (callback_query)
Bot de Telegram 
  ↓ (webhook/polling)
Servidor PHP (bot.php)
  ↓ (cURL + API oficial)
API Telegram (getUpdates, answerCallbackQuery)
```

## Componentes Técnicos

1. **Integración con Telegram API**
   - Método de comunicación: cURL requests a `api.telegram.org`
   - Endpoints utilizados: `getUpdates` (polling), `answerCallbackQuery`, `sendMessage`
   - Autenticación: Token de bot incorporado en headers

2. **Sistema de Callbacks**
   - Patrón de identificación: `accion:{transaction_id}`
   - Ejemplo: `accion:tid_12345` → recarga confirmada
   - Los callbacks son procesados de forma síncrona

3. **Generación de Botones Dinámicos**
   - Formato: `reply_markup` en JSON (inline_keyboard)
   - Los botones se generan en tiempo de ejecución según el estado de la transacción
   - Estructura: `[["Botón" → "accion:tid"]]`

4. **Persistencia de Estado**
   - Almacenamiento local: archivos `.txt` por transacción
   - Propósito: evitar doble procesamiento de callbacks
   - Ubicación: `/data-ps/recargas/transaction/nequi-1/`

5. **Formato de Respuestas**
   - Modo de parseo: `parse_mode='HTML'`
   - Permite: `<b>`, `<i>`, `<code>`, `<pre>` para enriquecimiento visual
   - No es solo texto plano

## Seguridad

- Los callbacks se validan contra el patrón esperado
- Las transacciones duplicadas se previenen mediante archivos de estado
- No hay procesamiento de datos sin validación de callback_query
