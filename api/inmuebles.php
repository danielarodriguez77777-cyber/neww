<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

$conjuntoId = basename($_GET['conjuntoId'] ?? ''); // evita path traversal
$filtro     = trim($_GET['filtro'] ?? '');

function buscarLocal($conjuntoId, $filtro) {
    $file = __DIR__ . '/apartamentos/' . $conjuntoId . '.json';
    if ($conjuntoId === '' || !is_file($file)) {
        return [];
    }

    $apartamentos = json_decode(file_get_contents($file), true) ?? [];

    $needle = mb_strtolower($filtro, 'UTF-8');
    $out = [];
    foreach ($apartamentos as $a) {
        $numero = (string)($a['inmueble'] ?? '');
        $ref    = (string)($a['referencia'] ?? '');
        if ($needle !== '' &&
            mb_strpos(mb_strtolower($numero, 'UTF-8'), $needle) === false &&
            mb_strpos(mb_strtolower($ref, 'UTF-8'), $needle) === false) {
            continue;
        }
        $out[] = [
            '_id'                      => $conjuntoId . '-' . $ref,
            'co_ownership_id'          => $conjuntoId,
            'property_type'            => $a['tipo'] ?? '',
            'property_number'          => $numero,
            'reference'                => $ref,
            'administration_fee_value' => $a['precio'] ?? 0,
        ];
        if (count($out) >= 100) break;
    }
    return $out;
}

function buscarApi($conjuntoId, $filtro) {
    $payload = json_encode([
        'operationName' => 'publicListProperties',
        'query' => 'query publicListProperties($skip: Int!, $limit: Int!, $filter: String, $coOwnershipId: String!) {
            publicListProperties(skip: $skip, limit: $limit, filter: $filter, coOwnershipId: $coOwnershipId) {
                _id uid co_ownership_id property_type property_number reference administration_fee_value
            }
        }',
        'variables' => [
            'coOwnershipId' => $conjuntoId,
            'skip'          => 0,
            'limit'         => 100,
            'filter'        => $filtro
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
    $res  = curl_exec($ch);
    curl_close($ch);
    $data = json_decode($res, true);
    return $data['data']['publicListProperties'] ?? [];
}

$out = buscarLocal($conjuntoId, $filtro);

if (empty($out)) {
    $out = buscarApi($conjuntoId, $filtro);
}

echo json_encode($out);
