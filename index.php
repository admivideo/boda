<?php
$sheetEndpoint = getenv('GOOGLE_APPS_SCRIPT_URL') ?: 'https://script.google.com/macros/s/REEMPLAZA_CON_TU_WEBAPP_URL/exec';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Invitación de boda</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Inter:wght@400;500;600&display=swap"
 rel="stylesheet">
  <link rel="stylesheet" href="styles.css">
</head>
<body data-sheet-endpoint="<?php echo htmlspecialchars($sheetEndpoint, ENT_QUOTES, 'UTF-8'); ?>">
  <header class="hero">
    <div class="overlay"></div>
    <div class="hero-content container">
      <p class="kicker">¡Nos casamos!</p>
      <h1 id="coupleNames">Ana &amp; Luis</h1>
      <p class="subtitle" id="weddingDate">Sábado 20 de junio de 2026, 11:00 a.m.</p>
      <p class="greeting" id="guestGreeting"></p>
      <div class="cta-group">
        <a class="button primary" href="#rsvp">Confirmar asistencia</a>
        <a class="button ghost" id="mapLink" target="_blank" rel="noreferrer">Cómo llegar</a>
      </div>
    </div>
  </header>

  <main>
    <section class="container grid stats">
      <div class="stat-card">
        <p class="label">Día</p>
        <p class="value" id="dateValue">20 de junio de 2026</p>
      </div>
      <div class="stat-card">
        <p class="label">Hora</p>
        <p class="value" id="timeValue">11:00 a.m.</p>
      </div>
      <div class="stat-card">
        <p class="label">Lugar</p>
        <p class="value" id="venueValue">Parque de los Castillos - Alcorcón (Madrid)</p>
      </div>
      <div class="stat-card countdown">
        <p class="label">Faltan</p>
        <p class="value" id="countdown">-- días</p>
      </div>
    </section>

    <section class="container details">
      <div class="detail-card">
        <h2>Nuestro gran día</h2>
        <p id="introText">
          Queremos compartir contigo este momento tan especial. Acompáñanos a celebrar con una ceremonia íntima
          seguida de una fiesta llena de música, risas y mucho amor.
        </p>
        <ul class="detail-list" id="detailList">
          <li><span>Ubicación:</span> <a id="mapsUrl" target="_blank" rel="noreferrer">Ver en mapa</a></li>
          <li><span>Dirección:</span> <span id="addressText">Parque de los Castillos - Alcorcón (Madrid)</span></li>
          <li><span>Código de vestimenta:</span> <span id="dressCode">Formal de verano</span></li>
          <li><span>Niños:</span> <span id="kidsNote">Niños bienvenidos</span></li>
        </ul>
      </div>
      <div class="timeline">
        <h3>Agenda</h3>
        <div class="timeline-item">
          <div class="time">11:00</div>
          <div class="info">
            <p class="title">Ceremonia</p>
            <p class="desc">Nos diremos “sí, acepto” a media mañana.</p>
          </div>
        </div>
        <div class="timeline-item">
          <div class="time">12:30</div>
          <div class="info">
            <p class="title">Recepción</p>
            <p class="desc">Brindis de bienvenida y aperitivos al aire libre.</p>
          </div>
        </div>
        <div class="timeline-item">
          <div class="time">14:00</div>
          <div class="info">
            <p class="title">Almuerzo &amp; baile</p>
            <p class="desc">Abrimos pista después de nuestro primer baile.</p>
          </div>
        </div>
      </div>
    </section>

    <section id="rsvp" class="rsvp">
      <div class="container rsvp-card">
        <div>
          <p class="kicker">Confirma por WhatsApp</p>
          <h2>¿Vienes a celebrar?</h2>
          <p class="muted">Cuéntanos quién asistirá y cuántas personas te acompañan. Usaremos este mensaje para
            tener tu confirmación en nuestra lista.</p>
          <p class="muted" id="personalizedInfo" hidden></p>
          <ul class="notes">
            <li>Si compartes el enlace desde tu Google Sheet puedes agregar <code>?invitado=Nombre</code> y
              <code>&amp;acompanantes=Persona1,Persona2</code> para personalizar saludo, acompañantes y asistentes.</li>
            <li>También puedes editar el número de anfitrión y la fecha del evento en <code>main.js</code>.</li>
          </ul>
        </div>
        <form id="rsvpForm" class="form">
          <div class="field">
            <label for="guestName">Nombre</label>
            <input id="guestName" name="guestName" type="text" placeholder="Ej. Mariana López" required>
          </div>
          <div class="field">
            <label for="guestCount">Número de asistentes</label>
            <select id="guestCount" name="guestCount" required>
              <option value="" disabled selected>Selecciona</option>
              <option value="1">1</option>
              <option value="2">2</option>
              <option value="3">3</option>
              <option value="4">4</option>
            </select>
          </div>
          <div class="field">
            <label for="message">Mensaje (opcional)</label>
            <textarea id="message" name="message" rows="3" placeholder="Alguna nota especial o restricción alimentaria"></textarea>
          </div>
          <button class="button primary full" type="submit">Enviar confirmación</button>
          <p class="helper" id="formHelper">Se abrirá WhatsApp con un mensaje listo para enviar.</p>
        </form>
      </div>
    </section>
  </main>

  <footer class="footer">
    <p>Con cariño, Ana &amp; Luis · <span id="footerDate">20.06.2025</span></p>
  </footer>

  <script src="main.js"></script>
</body>
</html>
