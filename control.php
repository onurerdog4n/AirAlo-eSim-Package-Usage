<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

header('Access-Control-Allow-Origin: *'); // Bu tüm domainlere izin verir
header('Access-Control-Allow-Methods: GET, POST, OPTIONS'); // Hangi HTTP metodlarının kabul edileceğini belirtir
header('Access-Control-Allow-Headers: Content-Type'); // Hangi başlıkların kabul edileceğini belirtir


$iccid = isset($_GET['iccid']) ? $_GET['iccid'] : null;
if (!$iccid) {
    echo json_encode([
        'status' => 'failed',
        'message' => 'ICCID parametresi bulunamadı.'
    ]);
    exit;
}

require __DIR__ . '/vendor/autoload.php';

use Airalo\Airalo;

$alo = new Airalo([
    'client_id' => 'xxxxx',  //  client_id
    'client_secret' => 'xxxxxx',  //  client_secret
    'env' => 'production', // Ortam: production veya sandbox  // optional, defaults to `production`
]);

$simUsage = $alo->simUsage($iccid);

// Check if simUsage is empty or invalid
if (empty($simUsage)) {
    echo json_encode([
        'status' => 'failed',
        'message' => 'ICCID bulunamadı.'
    ]);
    exit;
}

// Extract the relevant data

$usageData = $simUsage['data'];  // Get the inner 'data' array


// If simUsage is valid, output the data in the desired structure
echo json_encode([
    'status' => 'success',
    'data' => [
        'remaining' => $usageData['remaining'],
        'total' => $usageData['total'],
        'expired_at' => $usageData['expired_at'],
        'is_unlimited' => $usageData['is_unlimited'],
        'status' => $usageData['status'],
        'remaining_voice' => $usageData['remaining_voice'],
        'remaining_text' => $usageData['remaining_text'],
        'total_voice' => $usageData['total_voice'],
        'total_text' => $usageData['total_text']
    ]
]);
?>
