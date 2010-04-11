<?php

require_once 'config.php';

if (!isset($services[$_GET['service']])) {
    throw new EZRP_Exception('Unknown service');
}

$service = $services[$_GET['service']];

?>
<html>
<body>
    <center>
        <p>Please wait while we contact <?php echo $service ?></p>
        <img src="spinner.gif" border="0" alt="spinner image">
    </center>
</body>
</html>
