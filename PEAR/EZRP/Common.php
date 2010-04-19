<?php

require_once 'EZRP/Exception.php';

abstract class EZRP_Common
{
    protected $driver = null;
    protected $ezrp = null;
    protected $baseURL = '';
    protected $identifier = null;
    protected $ezrpPath = '/ezrp';
    protected $verifyURL = null;
    protected $sessionID = null;


    public function init(array $options)
    {
        // base url
        if (isset($options['baseURL'])) {
            $this->baseURL = $options['baseURL'];
        } else {
            $this->baseURL = 'http://' . $_SERVER['SERVER_NAME'];
        }

        if (isset($options['ezrpPath'])) {
            $this->ezrpPath = $options['ezrpPath'];
        }

        $this->sessionID = $options['sessionID'];

        $this->verifyURL = rtrim($this->baseURL, '/') . $this->ezrpPath
                          . '/verify.php?ezrpd=' . $this->driver;
    }

    public function __construct(EZRP $ezrp, array $options)
    {
        $this->ezrp = $ezrp;
        $this->init($options);
    }

    abstract public function prepare(array $options = array());
    abstract public function verify(array $options);
    abstract public function getProfileData(array $options);
    abstract public function getMap(array $options);
    abstract public function addMap(array $options);
}
?>
