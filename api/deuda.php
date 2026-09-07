<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

$conjuntoId = basename($_GET['conjuntoId'] ?? ''); // evita path traversal
$referencia = trim($_GET['referencia'] ?? '');

function buscarApi($conjuntoId, $referencia) {
    $payload = json_encode([
        'operationName' => 'PublicListInvoicesForPayment',
        'query' => 'query PublicListInvoicesForPayment($coOwnerShipId: String!, $reference: String!) {
            publicListInvoicesForPayment(coOwnerShipId: $coOwnerShipId, reference: $reference) {
                property { _id property_type property_number }
                invoices {
                    _id invoice_status invoice_number creation_date payment_date
                    description total_amount paid_amount balance interests
                    timely_discounts { discount_value maximum_discount_date }
                    invoice_items { amount collection_concept { concept_name } }
                }
                noInvoicesData { balance total_amount amountDiscount }
            }
        }',
        'variables' => [
            'coOwnerShipId' => $conjuntoId,
            'reference'     => $referencia
        ]
    ]);

    $ch = curl_init('https://7blvapmv6rg5nfpzx5qlwfkw4i.appsync-api.us-east-1.amazonaws.com/graphql');
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST           => true,
        CURLOPT_POSTFIELDS     => $payload,
        CURLOPT_HTTPHEADER     => [
            'Content-Type: application/json',
            'x-api-key: da2-amsiieieorclvntn7td2lo47ze',
            'Origin: https://web-conjuntos.jelpit.com'
        ],
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_TIMEOUT        => 10,
    ]);
    $res      = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode !== 200) {
        return null; // API bloqueada (WAF) o caída
    }

    $data = json_decode($res, true);
    return $data['data']['publicListInvoicesForPayment'] ?? null;
}

// Respaldo: usa el valor de administración del JSON local cuando la API no responde.
function estimarDesdeLocal($conjuntoId, $referencia) {
    $file = __DIR__ . '/apartamentos/' . $conjuntoId . '.json';
    if (!is_file($file)) {
        return [
            'property'      => null,
            'invoices'      => [],
            'noInvoicesData'=> ['balance' => 0, 'total_amount' => 0, 'amountDiscount' => 0],
            'fuente'        => 'sin_datos'
        ];
    }

    $apartamentos = json_decode(file_get_contents($file), true) ?? [];
    $match = null;
    foreach ($apartamentos as $a) {
        if ((string)($a['referencia'] ?? '') === $referencia) {
            $match = $a;
            break;
        }
    }

    $valor = $match['precio'] ?? 0;

    return [
        'property' => $match ? [
            '_id'             => $conjuntoId . '-' . $referencia,
            'property_type'   => $match['tipo'] ?? '',
            'property_number' => $match['inmueble'] ?? '',
        ] : null,
        'invoices'       => [],
        'noInvoicesData' => ['balance' => $valor, 'total_amount' => $valor, 'amountDiscount' => 0],
        'fuente'         => 'estimado_local' // el frontend puede usar esto para avisar que es un valor sugerido, no la deuda real
    ];
}

if ($conjuntoId === '' || $referencia === '') {
    echo json_encode(['invoices' => [], 'noInvoicesData' => ['balance' => 0]]);
    exit;
}

$resultado = buscarApi($conjuntoId, $referencia);

if ($resultado === null) {
    $resultado = estimarDesdeLocal($conjuntoId, $referencia);
}

echo json_encode($resultado);
