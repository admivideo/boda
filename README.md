# Invitación de boda

Pequeña web responsive para compartir la invitación de boda por WhatsApp. Muestra los datos del evento, la cuenta
regresiva y un botón para confirmar asistencia enviando un mensaje directo a los anfitriones.

## Cómo usar
1. Abre `index.html` en cualquier hosting estático (GitHub Pages, Netlify, Vercel o incluso Google Drive publicado).
2. Ajusta los datos del evento en [`main.js`](./main.js): nombres, fecha/hora, ubicación, enlace de mapas y número de
   WhatsApp anfitrión.
3. Comparte el enlace por WhatsApp. Si usas una hoja de cálculo con tu lista de invitados, añade
   `?invitado=Nombre Apellido` al URL que envías para personalizar el saludo y precargar el formulario.
4. Cada invitado podrá pulsar **Enviar confirmación** para abrir WhatsApp con su mensaje de asistencia listo.

## Personalización rápida
- Colores y tipografías pueden ajustarse en [`styles.css`](./styles.css).
- Los textos de agenda y notas se encuentran en [`index.html`](./index.html).
- El mensaje de confirmación se arma en [`main.js`](./main.js) dentro de `buildWhatsAppUrl`.

## Contenido
- `index.html`: estructura y secciones (hero, detalles, agenda, RSVP).
- `styles.css`: estilos responsivos.
- `main.js`: lógica de cuenta regresiva, personalización por query string y armado del mensaje de WhatsApp.
