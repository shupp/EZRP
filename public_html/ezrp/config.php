<?php

ini_set('include_path', dirname(__FILE__) . '/../../PEAR:/Users/bill/git/HTTP_OAuth:' . get_include_path());
require_once 'EZRP/Exception.php';

$services = array(
    'twitter'  => 'Twitter',
    'google'   => 'Google',
    'yahoo'    => 'Yahoo!',
    'facebook' => 'Facebook',
    'openid'   => 'your OpenID provider'
);

?>
