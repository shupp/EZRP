<?php

require_once 'EZRP/OpenID.php';

class EZRP_Google extends EZRP_OpenID
{
    protected $driver = 'google';
    public function setIdentifier($options)
    {
        $this->identifier = 'https://www.google.com/accounts/o8/id';
    }
}
?>
