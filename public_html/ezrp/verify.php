<?php

require_once 'config.php';

if (!isset($_GET['ezrpd']) || !isset($services[$_GET['ezrpd']])) {
    throw new EZRP_Exception('Invalid request');
}
$ezrp = new EZRP($_GET['ezrpd'], $configOptions);
$options = array();

try {
    $url = $ezrp->verify($_POST);
    $response = array(
        'success' => true,
        'url' => $url,
    );
} catch (Exception $e) {
    $response = array(
        'success' => false,
        'message' => $e->getMessage()
    );
}

echo json_encode($response);
