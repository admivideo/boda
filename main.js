const eventConfig = {
  coupleNames: 'Ana & Luis',
  weddingDate: '2026-06-20T17:00:00+02:00',
  venue: 'Parque de los Castillos',
  address: 'Parque de los Castillos, Alcorcón (Madrid)',
  mapsUrl: 'https://www.google.com/maps/search/?api=1&query=Parque+de+los+Castillos+Alcorc%C3%B3n',
  dressCode: 'Formal de verano',
  kidsNote: 'Niños bienvenidos',
  introText:
    'Queremos compartir contigo este momento tan especial. Acompáñanos a celebrar en el Parque de los Castillos con una ceremonia íntima seguida de una fiesta llena de música, risas y mucho amor.',
  hostPhone: '+34900111222',
};

const qs = new URLSearchParams(window.location.search);
const guestFromQuery = qs.get('invitado');

function formatDate(date) {
  const options = { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' };
  return new Intl.DateTimeFormat('es-ES', options).format(date);
}

function formatTime(date) {
  const options = { hour: 'numeric', minute: '2-digit' };
  return new Intl.DateTimeFormat('es-ES', options).format(date);
}

function updateStaticText() {
  const eventDate = new Date(eventConfig.weddingDate);
  const formattedDate = formatDate(eventDate);
  const formattedTime = formatTime(eventDate);

  document.getElementById('coupleNames').textContent = eventConfig.coupleNames;
  document.getElementById('weddingDate').textContent = `${formattedDate} · ${formattedTime}`;
  document.getElementById('dateValue').textContent = formattedDate;
  document.getElementById('timeValue').textContent = formattedTime;
  document.getElementById('venueValue').textContent = eventConfig.venue;
  document.getElementById('introText').textContent = eventConfig.introText;
  document.getElementById('addressText').textContent = eventConfig.address;
  document.getElementById('mapsUrl').href = eventConfig.mapsUrl;
  document.getElementById('mapLink').href = eventConfig.mapsUrl;
  document.getElementById('dressCode').textContent = eventConfig.dressCode;
  document.getElementById('kidsNote').textContent = eventConfig.kidsNote;
  document.getElementById('footerDate').textContent = eventDate.toISOString().slice(0, 10).split('-').reverse().join('.');

  const greetingEl = document.getElementById('guestGreeting');
  if (guestFromQuery) {
    greetingEl.textContent = `Hola ${guestFromQuery}, no podemos esperar para verte.`;
    const nameInput = document.getElementById('guestName');
    nameInput.value = guestFromQuery;
  } else {
    greetingEl.textContent = 'Nos hará mucha ilusión contar contigo.';
  }
}

function updateCountdown() {
  const now = new Date();
  const eventDate = new Date(eventConfig.weddingDate);
  const diff = eventDate - now;
  const days = Math.max(0, Math.ceil(diff / (1000 * 60 * 60 * 24)));
  const countdownEl = document.getElementById('countdown');
  countdownEl.textContent = `${days} día${days === 1 ? '' : 's'}`;
}

function buildWhatsAppUrl(name, count, message) {
  const base = 'https://wa.me/';
  const phone = eventConfig.hostPhone.replace(/\D/g, '');
  const greeting = guestFromQuery ? guestFromQuery : 'Hola';
  const text = `${greeting}, soy ${name}. Confirmo mi asistencia a la boda. Seremos ${count} persona(s). ${message ? `Nota: ${message}` : ''}`.trim();
  const url = `${base}${phone}?text=${encodeURIComponent(text)}`;
  return url;
}

function handleSubmit(event) {
  event.preventDefault();
  const name = document.getElementById('guestName').value.trim();
  const count = document.getElementById('guestCount').value;
  const message = document.getElementById('message').value.trim();

  if (!name || !count) {
    document.getElementById('formHelper').textContent = 'Agrega tu nombre y el número de asistentes.';
    return;
  }

  const helper = document.getElementById('formHelper');
  helper.textContent = 'Guardando confirmación...';

  const payload = {
    name,
    count,
    message,
    eventDate: eventConfig.weddingDate,
    venue: eventConfig.venue,
  };

  const endpoint = document.body.dataset.sheetEndpoint;

  fetch('submit.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ ...payload, endpoint }),
  })
    .then((response) => response.json())
    .then((data) => {
      if (!data.ok) {
        throw new Error(data.message || 'No se pudo guardar en Google Sheet');
      }

      helper.textContent = 'Confirmación guardada en la hoja. Abriendo WhatsApp...';
      const whatsappUrl = buildWhatsAppUrl(name, count, message);
      window.open(whatsappUrl, '_blank');
    })
    .catch((error) => {
      console.error(error);
      helper.textContent =
        'No pudimos guardar en Google Sheet. Intenta de nuevo o revisa el endpoint en submit.php.';
    });
}

updateStaticText();
updateCountdown();
setInterval(updateCountdown, 1000 * 60 * 60);
document.getElementById('rsvpForm').addEventListener('submit', handleSubmit);
