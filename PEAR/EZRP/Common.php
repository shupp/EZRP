<?php

require_once 'EZRP/Exception.php';

abstract class EZRP_Common
{
    protected $ezrp = null;

    public function __construct(EZRP $ezrp, array $options)
    {
        $this->ezrp = $ezrp;
        $this->init($options);
    }

    abstract public function init(array $options);
    abstract public function prepare(array $options = array());
    abstract public function verify(array $options);
    abstract public function getProfileData(array $options);
    abstract public function getMap(array $options);
    abstract public function addMap(array $options);
}
?>
