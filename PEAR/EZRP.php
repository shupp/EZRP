<?php

require_once 'EZRP/Exception.php';

class EZRP
{
    protected $store  = null;
    protected $driver = null;

    public function __construct($driver, array $options = array())
    {
        $this->driver = $this->getDriver($driver, $options);
    }

    public function setStore(EZRP_Store_Interface $store)
    {
    }

    public function getStore()
    {
        if ($this->store === null) {
            include_once 'EZRP/Store/CacheLite.php';
            $this->store = new EZRP_Store_CacheLite();
        }
        return $this->store;
    }

    public function prepare(array $options = array())
    {
        return $this->driver->prepare($options);
    }

    public function verify(array $options = array())
    {
        return $this->driver->verify($options);
    }

    public function getDriver($driver, array $options)
    {
        static $drivers = array(
            'twitter'  => 'Twitter',
            'openid'   => 'OpenID',
            'facebook' => 'Facebook',
            'yahoo'    => 'Yahoo',
            'google'   => 'Google'
        );

        if (!isset($drivers[$driver])) {
            throw new EZRP_Exception('Invalid driver');
        }

        $file = 'EZRP/' . $drivers[$driver] . '.php';
        $class = 'EZRP_' . $drivers[$driver];

        include_once $file;
        return new $class($this, $options);
    }
}
?>
