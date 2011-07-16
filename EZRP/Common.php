<?php

require_once 'EZRP/Exception.php';

abstract class EZRP_Common
{
    protected $driver = null;
    protected $ezrp = null;
    protected $baseURL = '';
    protected $identifier = null;
    protected $ezrpPath = '/ezrp/verify.php';
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

        $verifyURL = new Net_URL2(rtrim($this->baseURL, '/') . $this->ezrpPath);
        $verifyURL->setQueryVariable('ezrpd', $this->driver);
        $this->verifyURL = $verifyURL->getURL();
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
