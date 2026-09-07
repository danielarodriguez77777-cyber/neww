<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

$nombre = trim($_GET['nombre'] ?? '');

if ($nombre === '') {
    echo json_encode([]);
    exit;
}

function buscarLocal($nombre) {
    $conjuntos = json_decode(file_get_contents(__DIR__ . '/conjuntos.json'), true) ?? [];
    $needle = mb_strtolower($nombre, 'UTF-8');
    $out = [];
    foreach ($conjuntos as $c) {
        if (mb_strpos(mb_strtolower($c['co_ownership_name'] ?? '', 'UTF-8'), $needle) !== false) {
            $out[] = $c;
            if (count($out) >= 10) break;
        }
    }
    return $out;
}

function buscarApi($nombre) {
    $payload = json_encode([
        'operationName' => 'PublicListBuildings',
        'query' => 'query PublicListBuildings($filter: String!, $skip: Int, $limit: Int, $querySuggestAdminValue: Boolean, $state: [String]) {
            publicListBuildings(filter: $filter, skip: $skip, limit: $limit, querySuggestAdminValue: $querySuggestAdminValue, state: $state) {
                _id parent_co_ownership_id co_ownership_name agreement_number
                city department address enable_reference_two
                portfolioCollection { make_partial_payments suggest_admin_value select_items_pay }
            }
        }',
        'variables' => [
            'filter'                 => $nombre,
            'skip'                   => 0,
            'limit'                  => 10,
            'querySuggestAdminValue' => true,
            'state'                  => ['ACTIVA', 'RECAUDANDO']
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
    return $data['data']['publicListBuildings'] ?? [];
}

$out = buscarLocal($nombre);

if (empty($out)) {
    $out = buscarApi($nombre);
}

echo json_encode($out);
