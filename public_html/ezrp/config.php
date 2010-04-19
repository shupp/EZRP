<?php

ini_set('include_path', dirname(__FILE__) . '/../../PEAR:' . get_include_path());
require_once 'EZRP.php';
session_start();

$configOptions = array(
    'baseURL'   => 'http://ezrp.local',
    'sessionID' => session_id(),
    'twitter'   => array(
        'key'    => 'njcJd2TnApObgVtutSCTpQ',
        'secret' => 'eF5NMtqAjPzd9rvPQWRIbbBTGx8qo6woXuyJy8cQBDs',
    )
);

$services = array(
    'twitter'  => 'Twitter',
    'google'   => 'Google',
    'yahoo'    => 'Yahoo!',
    'facebook' => 'Facebook',
    'openid'   => 'your OpenID provider'
);

?>
