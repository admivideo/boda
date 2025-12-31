<?php
header('Content-Type: application/json; charset=utf-8');

$defaultEndpoint = 'https://script.google.com/macros/s/AKfycbwxYPvg3hZoJ5XNzEqdK36zEZd9NI1pacI-9Ck0a3rjx75LFp3vbWiHcXfqC-2UIkKw7w/exec';
$sheetEndpoint = getenv('GOOGLE_APPS_SCRIPT_URL') ?: $defaultEndpoint;

$input = json_decode(file_get_contents('php://input'), true) ?? [];
$name = trim($input['name'] ?? '');
$count = trim((string) ($input['count'] ?? ''));
$message = trim($input['message'] ?? '');
$eventDate = trim($input['eventDate'] ?? '');
$venue = trim($input['venue'] ?? '');

if (!$name || !$count) {
  http_response_code(400);
  echo json_encode([
    'ok' => false,
    'message' => 'Nombre y número de asistentes son obligatorios.',
  ]);
  exit;
}

// Permite sobrescribir el endpoint recibido desde el cliente solo si coincide con el valor del entorno.
if (!empty($input['endpoint']) && $input['endpoint'] === $sheetEndpoint) {
  $sheetEndpoint = $input['endpoint'];
}

if (strpos($sheetEndpoint, 'REEMPLAZA_CON_TU_WEBAPP_URL') !== false) {
  http_response_code(500);
  echo json_encode([
    'ok' => false,
    'message' => 'Configura GOOGLE_APPS_SCRIPT_URL con la URL de tu Web App de Google Apps Script.',
  ]);
  exit;
}

$payload = [
  'timestamp' => gmdate('c'),
  'name' => $name,
  'guestCount' => $count,
  'message' => $message,
  'eventDate' => $eventDate,
  'venue' => $venue,
  'source' => $_SERVER['HTTP_REFERER'] ?? '',
];

$ch = curl_init($sheetEndpoint);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
curl_close($ch);

if ($response === false || $httpCode >= 400) {
  http_response_code($httpCode ?: 500);
  echo json_encode([
    'ok' => false,
    'message' => 'Error al enviar la confirmación a Google Apps Script.',
    'details' => $error ?: $response,
  ]);
  exit;
}

echo json_encode([
  'ok' => true,
  'message' => 'RSVP guardado en la hoja de cálculo.',
  'response' => $response,
]);
