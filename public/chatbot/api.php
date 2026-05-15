<?php
// public/chatbot/api.php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

require_once __DIR__ . '/../../src/Core/IAChatbot.php';

$ia = new IAChatbot();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $pregunta = $data['pregunta'] ?? '';
    
    if (empty($pregunta)) {
        echo json_encode(['error' => 'Por favor, escribe una pregunta.']);
        exit();
    }
    
    $respuesta = $ia->responder($pregunta);
    
    echo json_encode([
        'pregunta' => $pregunta,
        'respuesta' => $respuesta,
        'timestamp' => date('H:i:s')
    ]);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['sugerencias'])) {
    echo json_encode(['sugerencias' => $ia->sugerirPreguntas()]);
    exit();
}

echo json_encode(['error' => 'Método no permitido']);
?>