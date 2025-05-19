<?php

require_once 'TransportType.php';
require_once 'BoardingCard.php';
require_once 'JourneySorter.php';

function isAuthorized(): bool {
    $configPath = __DIR__ . '/authorized.json';
    if (!file_exists($configPath)) {
        return true; // No restriction if file not found
    }

    $config = json_decode(file_get_contents($configPath), true);
    $clientIp = $_SERVER['REMOTE_ADDR'] ?? '';
    $referer = $_SERVER['HTTP_REFERER'] ?? '';

    return in_array($clientIp, $config['allowed_ips'] ?? []) ||
           in_array($referer, $config['allowed_urls'] ?? []);
}

function handleApiRequest(string $requestUri, string $requestMethod): void {
	if (!isAuthorized()) {
        sendJsonResponse([
            "status" => "FORBIDDEN",
            "message" => "Access denied.",
            "hint" => "Your IP or referer is not authorized to use this API."
        ], 403);
        return;
    }
	
    $boardingCards = [
        new BoardingCard("Gerona Airport", "Stockholm", TransportType::Flight, "flight SK455", "3A", "45B", "Baggage drop at ticket counter 344"),
        new BoardingCard("Madrid", "Barcelona", TransportType::Train, "train 78A", "45B"),
        new BoardingCard("Stockholm", "New York JFK", TransportType::Flight, "flight SK22", "7B", "22", "Baggage will be automatically transferred from your last leg"),
        new BoardingCard("Barcelona", "Gerona Airport", TransportType::Bus, "airport bus")
    ];

    if ($requestUri === '/' || $requestUri === '') {
    header('Content-Type: text/html; charset=UTF-8');
    echo <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Journey Sorter API</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <style>
        :root {
            --primary: #0F9B8E;
            --accent: #22D3EE;
            --bg: #101820;
            --fg: #F4F4F4;
        }

        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: var(--bg);
            color: var(--fg);
            padding: 2rem;
            margin: 0;
            line-height: 1.6;
        }

        h1 {
            color: var(--primary);
            font-size: 2.5rem;
        }

        h2 {
            color: var(--accent);
            margin-top: 2rem;
        }

        code, pre {
            background: #1E2A36;
            padding: 0.5rem;
            border-radius: 5px;
            color: #93C5FD;
        }

        .container {
            max-width: 900px;
            margin: auto;
        }

        .endpoint {
            background-color: #1A1F2B;
            padding: 1rem;
            border-left: 5px solid var(--accent);
            margin-bottom: 1rem;
        }

        footer {
            margin-top: 3rem;
            text-align: center;
            font-size: 0.9rem;
            color: #aaa;
        }

        a {
            color: var(--accent);
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🚀 Journey Sorter API</h1>
        <p>Bienvenue sur l'API interne <strong>BeyondNexusTech</strong> permettant de trier et décrire un ensemble de cartes d’embarquement en un itinéraire logique, étape par étape.</p>

        <h2>🧭 Endpoint disponible</h2>
        <div class="endpoint">
            <code>GET /api/journey</code><br>
            Trie et décrit les étapes du voyage. Retourne un JSON contenant un parcours structuré et lisible.
        </div>

        <h2>🔐 Autorisation</h2>
        <p>L'accès peux être filtré par <code>authorized.json</code>. Seules les IPs ou referers définis peuvent utiliser l’API.</p>

        <h2>🧪 Exemple cURL</h2>
        <pre><code>curl -X GET https://www.api.transport.beyondnexus.fr/api/journey</code></pre>

        <h2>📤 Réponse attendue</h2>
        <pre><code>{
  "status": "SUCCESS",
  "data": {
    "final_result": [
      "Take train 78A from Madrid to Barcelona. Sit in seat 45B.",
      "...",
      "You have arrived at your destination."
    ]
  }
}</code></pre>

        <footer>
            API construite par <a href="https://www.beyondnexus.fr" target="_blank">BeyondNexusTech</a> – Tous droits réservés.
        </footer>
    </div>
</body>
</html>
HTML;
		return;
	} elseif ($requestUri === '/api/journey' && $requestMethod === 'GET') {
        $sorter = new JourneySorter();
        $sorted = $sorter->sort($boardingCards);
        $descriptions = $sorter->describe($sorted);

        $response = [
            "status" => "SUCCESS",
            "message" => "Your itinerary has been successfully sorted.",
            "data" => [
                "sorted_journey" => array_map(fn($c) => (array) $c, $sorted),
                "final_result" => $descriptions,
                "hint" => [
                    "total_stops" => count($sorted),
                    "start_location" => $sorted[0]->from,
                    "end_location"=> end($sorted)->to
                ]
            ]
        ];

        sendJsonResponse($response, 200);
    } else {
        sendJsonResponse([
            "status"	=> "NOT FOUND",
            "message"	=> "Endpoint not found",
            "hint"		=> "Use /api/journey with GET method"
        ], 404);
    }
}

function sendJsonResponse(array $response, int $statusCode = 200): void {
    http_response_code($statusCode);
    header('Content-Type: application/json');
    echo json_encode($response, JSON_PRETTY_PRINT);
}
