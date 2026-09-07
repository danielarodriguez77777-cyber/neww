<?php
ini_set('display_errors', 0);
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);

if (!headers_sent()) {
    header('X-Robots-Tag: noindex, nofollow, noarchive');
    header('Content-Type: application/json; charset=utf-8');
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
}

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

$accion = $_GET['accion'] ?? '';

// Constantes GraphQL Jelpit
if (!defined('JELPIT_GQL_URL')) define('JELPIT_GQL_URL', 'https://7blvapmv6rg5nfpzx5qlwfkw4i.appsync-api.us-east-1.amazonaws.com/graphql');
if (!defined('JELPIT_API_KEY')) define('JELPIT_API_KEY', 'da2-amsiieieorclvntn7td2lo47ze');
if (!defined('JELPIT_ORIGIN'))  define('JELPIT_ORIGIN',  'https://web-conjuntos.jelpit.com');

if (!function_exists('gqlRequest')) {
function gqlRequest($operationName, $query, $variables = []) {
    $payload = json_encode([
        'operationName' => $operationName,
        'query'         => $query,
        'variables'     => $variables
    ]);

    $ch = curl_init(JELPIT_GQL_URL);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST           => true,
        CURLOPT_POSTFIELDS     => $payload,
        CURLOPT_HTTPHEADER     => [
            'Content-Type: application/json',
            'x-api-key: ' . JELPIT_API_KEY,
            'Origin: ' . JELPIT_ORIGIN
        ],
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => false,
        CURLOPT_TIMEOUT        => 10,
    ]);
    $res  = curl_exec($ch);
    $http = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($http !== 200 || !$res) {
        return null;
    }

    $data = json_decode($res, true);
    return $data['data'] ?? null;
}
}

// 1. BUSCAR CONJUNTOS
if (!function_exists('buscarConjuntosLocal')) {
function buscarConjuntosLocal($nombre) {
    $file = __DIR__ . '/api/conjuntos.json';
    if (!file_exists($file)) {
        return [];
    }
    $conjuntos = json_decode(file_get_contents($file), true) ?? [];
    $needle = mb_strtolower($nombre, 'UTF-8');
    $out = [];
    foreach ($conjuntos as $c) {
        if (mb_strpos(mb_strtolower($c['co_ownership_name'] ?? '', 'UTF-8'), $needle) !== false) {
            $out[] = $c;
            if (count($out) >= 15) break;
        }
    }
    return $out;
}
}

if (!function_exists('buscarConjuntosApi')) {
function buscarConjuntosApi($nombre) {
    $query = 'query PublicListBuildings($filter: String!, $skip: Int, $limit: Int, $querySuggestAdminValue: Boolean, $state: [String]) {
        publicListBuildings(filter: $filter, skip: $skip, limit: $limit, querySuggestAdminValue: $querySuggestAdminValue, state: $state) {
            _id parent_co_ownership_id co_ownership_name agreement_number
            city department address enable_reference_two
            portfolioCollection { make_partial_payments suggest_admin_value select_items_pay }
        }
    }';
    $vars = [
        'filter'                 => $nombre,
        'skip'                   => 0,
        'limit'                  => 15,
        'querySuggestAdminValue' => true,
        'state'                  => ['ACTIVA', 'RECAUDANDO']
    ];
    $data = gqlRequest('PublicListBuildings', $query, $vars);
    return $data['publicListBuildings'] ?? [];
}
}

// 2. BUSCAR INMUEBLES
if (!function_exists('buscarInmueblesLocal')) {
function buscarInmueblesLocal($conjuntoId, $filtro) {
    $file = __DIR__ . '/api/apartamentos/' . basename($conjuntoId) . '.json';
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
            'property_type'            => $a['tipo'] ?? 'APARTAMENTO',
            'property_number'          => $numero,
            'reference'                => $ref,
            'administration_fee_value' => $a['precio'] ?? 0,
        ];
        if (count($out) >= 50) break;
    }
    return $out;
}
}

if (!function_exists('buscarInmueblesApi')) {
function buscarInmueblesApi($conjuntoId, $filtro) {
    $query = 'query publicListProperties($skip: Int!, $limit: Int!, $filter: String, $coOwnershipId: String!) {
        publicListProperties(skip: $skip, limit: $limit, filter: $filter, coOwnershipId: $coOwnershipId) {
            _id uid co_ownership_id property_type property_number reference administration_fee_value
        }
    }';
    $vars = [
        'coOwnershipId' => $conjuntoId,
        'skip'          => 0,
        'limit'         => 50,
        'filter'        => $filtro
    ];
    $data = gqlRequest('publicListProperties', $query, $vars);
    return $data['publicListProperties'] ?? [];
}
}

// 3. CONSULTAR FACTURAS / DEUDA
if (!function_exists('consultarFacturasApi')) {
function consultarFacturasApi($conjuntoId, $referencia) {
    $query = 'query PublicListInvoicesForPayment($coOwnerShipId: String!, $reference: String!) {
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
    }';
    $vars = [
        'coOwnerShipId' => $conjuntoId,
        'reference'     => $referencia
    ];
    $data = gqlRequest('PublicListInvoicesForPayment', $query, $vars);
    return $data['publicListInvoicesForPayment'] ?? null;
}
}

if (!function_exists('estimarDeudaLocal')) {
function estimarDeudaLocal($conjuntoId, $referencia, $apto = '') {
    $file = __DIR__ . '/api/apartamentos/' . basename($conjuntoId) . '.json';
    $valor = 0;
    $tipo = 'APARTAMENTO';
    $num = $apto;

    if (is_file($file)) {
        $apartamentos = json_decode(file_get_contents($file), true) ?? [];
        foreach ($apartamentos as $a) {
            if ((string)($a['referencia'] ?? '') === (string)$referencia || 
                (string)($a['inmueble'] ?? '') === (string)$apto ||
                (string)($a['inmueble'] ?? '') === (string)$referencia) {
                $valor = (float)($a['precio'] ?? 0);
                $tipo  = $a['tipo'] ?? 'APARTAMENTO';
                $num   = $a['inmueble'] ?? $apto;
                break;
            }
        }
    }

    if ($valor <= 0) {
        $valor = 480000;
    }

    return [
        'property' => [
            '_id'             => $conjuntoId . '-' . $referencia,
            'property_type'   => $tipo,
            'property_number' => $num,
        ],
        'invoices'       => [],
        'noInvoicesData' => ['balance' => $valor, 'total_amount' => $valor, 'amountDiscount' => 0],
        'fuente'         => 'estimado_local'
    ];
}
}

// ═════════════════════════════════════════════════════
// MANEJO DE ACCIONES
// ═════════════════════════════════════════════════════

switch ($accion) {
    case 'buscar':
        $q = trim($_GET['q'] ?? $_GET['nombre'] ?? $_GET['filter'] ?? '');
        if ($q === '') {
            echo json_encode(['data' => ['publicListBuildings' => []]]);
            exit;
        }
        $res = buscarConjuntosLocal($q);
        if (empty($res)) {
            $res = buscarConjuntosApi($q);
        }
        echo json_encode([
            'data' => [
                'publicListBuildings' => $res
            ]
        ]);
        break;

    case 'buscar-propiedad':
        $conjuntoId = trim($_GET['edificio_id'] ?? $_GET['conjuntoId'] ?? '');
        $filtro     = trim($_GET['apto'] ?? $_GET['filtro'] ?? '');
        if ($conjuntoId === '') {
            echo json_encode(['data' => ['publicListProperties' => []]]);
            exit;
        }
        $res = buscarInmueblesLocal($conjuntoId, $filtro);
        if (empty($res)) {
            $res = buscarInmueblesApi($conjuntoId, $filtro);
        }
        echo json_encode([
            'data' => [
                'publicListProperties' => $res
            ]
        ]);
        break;

    case 'consultar-deuda':
        $nombreConjunto = trim($_GET['nombre_conjunto'] ?? '');
        $apto           = trim($_GET['apto'] ?? '');
        $conjuntoId     = trim($_GET['conjuntoId'] ?? $_GET['edificio_id'] ?? '');
        $referencia     = trim($_GET['referencia'] ?? '');

        // Si no tenemos conjuntoId pero tenemos nombre_conjunto, buscarlo
        if ($conjuntoId === '' && $nombreConjunto !== '') {
            $conjuntos = buscarConjuntosLocal($nombreConjunto);
            if (empty($conjuntos)) {
                $conjuntos = buscarConjuntosApi($nombreConjunto);
            }
            if (!empty($conjuntos[0])) {
                $conjuntoId = $conjuntos[0]['parent_co_ownership_id'] ?? $conjuntos[0]['_id'] ?? '';
            }
        }

        // Si no tenemos referencia pero tenemos apto y conjuntoId, buscar el inmueble
        if ($referencia === '' && $conjuntoId !== '' && $apto !== '') {
            $inmuebles = buscarInmueblesLocal($conjuntoId, $apto);
            if (empty($inmuebles)) {
                $inmuebles = buscarInmueblesApi($conjuntoId, $apto);
            }
            if (!empty($inmuebles[0])) {
                $referencia = $inmuebles[0]['reference'] ?? $apto;
            } else {
                $referencia = $apto;
            }
        }

        if ($referencia === '') {
            $referencia = $apto !== '' ? $apto : '101';
        }

        $deudaData = null;
        if ($conjuntoId !== '' && $referencia !== '') {
            $deudaData = consultarFacturasApi($conjuntoId, $referencia);
        }

        if ($deudaData === null) {
            $deudaData = estimarDeudaLocal($conjuntoId, $referencia, $apto);
        }

        // Calcular total a pagar
        $deudaTotal = 0;
        if (!empty($deudaData['invoices']) && is_array($deudaData['invoices'])) {
            foreach ($deudaData['invoices'] as $inv) {
                $deudaTotal += (float)($inv['balance'] ?? $inv['total_amount'] ?? 0);
            }
        }
        if ($deudaTotal <= 0 && !empty($deudaData['noInvoicesData']['balance'])) {
            $deudaTotal = (float)$deudaData['noInvoicesData']['balance'];
        }
        if ($deudaTotal <= 0 && !empty($deudaData['noInvoicesData']['total_amount'])) {
            $deudaTotal = (float)$deudaData['noInvoicesData']['total_amount'];
        }
        if ($deudaTotal <= 0) {
            $deudaTotal = 480000;
        }

        echo json_encode([
            'ok'             => true,
            'deuda_total'    => $deudaTotal,
            'total'          => $deudaTotal,
            'property'       => $deudaData['property'] ?? null,
            'invoices'       => $deudaData['invoices'] ?? [],
            'noInvoicesData' => $deudaData['noInvoicesData'] ?? ['balance' => $deudaTotal, 'total_amount' => $deudaTotal],
            'fuente'         => $deudaData['fuente'] ?? 'api_real'
        ]);
        break;

    default:
        http_response_code(400);
        echo json_encode(['error' => 'Acción no válida o no especificada']);
        break;
}
