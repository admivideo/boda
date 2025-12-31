# Invitación de boda

Pequeña web responsive para compartir la invitación de boda por WhatsApp. Incluye una cuenta regresiva, detalles del
evento y un formulario que guarda cada RSVP en Google Sheets antes de abrir WhatsApp con el mensaje listo para enviar.

## Características
- Página 100% estática con estilos responsive y tipografía combinada (Playfair Display + Inter).
- Cuenta regresiva automática hacia la fecha configurada.
- Personalización por query string (`?invitado=`) que muestra un saludo y precarga el nombre en el formulario.
- Envío del formulario a un endpoint de Google Apps Script y redirección a WhatsApp con el mensaje prellenado.
- Copia de los detalles clave del evento (fecha, hora, venue, dress code, notas) centralizada en `main.js`.

## Requisitos previos
- Hosting con PHP 7.4+ (o superior) y extensión cURL habilitada.
- Cuenta de Google para crear una Google Sheet y desplegar un Web App en Google Apps Script.
- (Opcional) Dominio o subdominio apuntando al servidor donde se desplegará `index.php`.

## Puesta en marcha rápida
1. **Clona o copia** el proyecto a tu hosting con PHP y apunta el sitio a `index.php`.
2. **Configura la URL de Apps Script** exportando la variable de entorno `GOOGLE_APPS_SCRIPT_URL` con la Web App
   desplegada (ver sección siguiente). Sin esta variable el backend usará un marcador y mostrará un error controlado.
3. **Edita los datos del evento** en [`main.js`](./main.js): nombres, fecha/hora (`weddingDate` con zona horaria),
   lugar, dirección, enlace de mapas y número de WhatsApp del anfitrión.
4. **Personaliza estilos y textos** en [`styles.css`](./styles.css) y los bloques de contenido de [`index.php`](./index.php).
5. **Comparte el enlace**. Si usas una hoja de cálculo con tu lista de invitados, añade `?invitado=Nombre Apellido` al
   enlace que envías para mostrar un saludo personalizado y precargar el nombre en el formulario.

### Ejecución local para pruebas
```bash
# Desde la raíz del proyecto
export GOOGLE_APPS_SCRIPT_URL="https://script.google.com/macros/s/XXXX/exec"
php -S 0.0.0.0:8000
# Visita http://localhost:8000 en tu navegador
```

## Configuración de Google Apps Script
1. Crea una hoja de cálculo con columnas: `timestamp`, `name`, `guestCount`, `message`, `eventDate`, `venue`, `source`.
2. En **Extensiones > Apps Script**, pega un código como este y publícalo como Web App (acceso: “Cualquiera con el
   enlace”):

```js
function doPost(e) {
  const payload = JSON.parse(e.postData.contents);
  const sheet = SpreadsheetApp.getActive().getSheetByName('Sheet1');
  sheet.appendRow([
    payload.timestamp,
    payload.name,
    payload.guestCount,
    payload.message,
    payload.eventDate,
    payload.venue,
    payload.source,
  ]);
  return ContentService.createTextOutput(
    JSON.stringify({ ok: true, received: payload })
  ).setMimeType(ContentService.MimeType.JSON);
}
```

3. Copia la URL del despliegue y asígnala a `GOOGLE_APPS_SCRIPT_URL`. El backend en [`submit.php`](./submit.php) solo
   aceptará reenviar formularios al endpoint que coincida con este valor.

## Flujo de datos
1. El usuario envía el formulario en `index.php`.
2. [`main.js`](./main.js) publica el payload a [`submit.php`](./submit.php).
3. `submit.php` valida el origen, envía la confirmación al Web App de Apps Script y devuelve el resultado.
4. Si todo es correcto, se abre WhatsApp con la URL generada en `buildWhatsAppUrl` para que el invitado envíe su mensaje.

## Personalización
- **Textos y agenda**: secciones en [`index.php`](./index.php).
- **Datos del evento**: objeto `eventConfig` en [`main.js`](./main.js) (fecha/hora en ISO 8601 con zona horaria).
- **Mensaje de WhatsApp**: función `buildWhatsAppUrl` en `main.js` (se incluye nombre, número de asistentes y mensaje
  opcional).
- **Estilos**: variables de color, tipografías y layout en [`styles.css`](./styles.css).

## Estructura del proyecto
- `index.php`: estructura y secciones (hero, detalles, agenda, RSVP) con lectura del endpoint desde la variable de
  entorno.
- `styles.css`: estilos responsivos y tokens de diseño.
- `main.js`: lógica de cuenta regresiva, personalización por query string, envío a Google Sheet y armado del mensaje de
  WhatsApp.
- `submit.php`: endpoint PHP que valida datos, reenvía a Google Apps Script y responde en JSON.

## Consejos finales
- Mantén la fecha (`weddingDate`) y el teléfono (`hostPhone`) siempre actualizados para evitar confusión entre invitados.
- Si cambias el nombre de la pestaña en la hoja de cálculo, actualiza `getSheetByName` en el snippet de Apps Script.
- Puedes duplicar la página para otros eventos cambiando únicamente `eventConfig` y la URL de Apps Script.
