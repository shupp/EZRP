<?php

require_once 'config.php';

if (!isset($_POST['ezrpd']) || !isset($services[$_POST['ezrpd']])) {
    throw new EZRP_Exception('Invalid request');
}
$ezrp = new EZRP($_POST['ezrpd'], $configOptions);
$options = array();

try {
    $url = $ezrp->prepare($_POST);
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
