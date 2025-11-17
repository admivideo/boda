# Invitación de boda

Pequeña web responsive para compartir la invitación de boda por WhatsApp. Muestra los datos del evento, la cuenta
regresiva y un botón para confirmar asistencia enviando un mensaje directo a los anfitriones.

## Cómo usar
1. Sube el proyecto a un hosting con PHP habilitado (Apache, Nginx+PHP-FPM, etc.) y apunta tu dominio a `index.php`.
2. Ajusta los datos del evento en [`main.js`](./main.js): nombres, fecha/hora, ubicación, enlace de mapas y número de
   WhatsApp anfitrión.
3. Configura la variable de entorno `GOOGLE_APPS_SCRIPT_URL` con la URL de tu Web App de Google Apps Script que escriba en
   tu Google Sheet (ejemplo de payload en [`submit.php`](./submit.php)).
4. Comparte el enlace por WhatsApp. Si usas una hoja de cálculo con tu lista de invitados, añade
   `?invitado=Nombre Apellido` al URL que envías para personalizar el saludo y precargar el formulario.
5. Cada invitado podrá pulsar **Enviar confirmación** para registrar su asistencia en la hoja y abrir WhatsApp con su
   mensaje de asistencia listo.

## Personalización rápida
- Colores y tipografías pueden ajustarse en [`styles.css`](./styles.css).
- Los textos de agenda y notas se encuentran en [`index.php`](./index.php).
- El mensaje de confirmación se arma en [`main.js`](./main.js) dentro de `buildWhatsAppUrl`.

## Contenido
- `index.php`: estructura y secciones (hero, detalles, agenda, RSVP) con configuración del endpoint de Google Apps Script.
- `styles.css`: estilos responsivos.
- `main.js`: lógica de cuenta regresiva, personalización por query string, envío a Google Sheet y armado del mensaje de
  WhatsApp.
- `submit.php`: endpoint en PHP que reenvía las confirmaciones a tu Web App de Google Apps Script.
