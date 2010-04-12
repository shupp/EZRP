<?php

require_once 'EZRP/Common.php';
require_once 'OpenID/RelyingParty.php';

class EZRP_OpenID extends EZRP_Common
{
    protected $driver = 'openid';
    protected $realm = null;
    protected $returnTo = null;
    protected $identifier = null;
    protected $ezrpPath = '/ezrp';

    public function init(array $options)
    {
        // openid realm
        if (isset($options['realm'])) {
            $this->realm = $options['realm'];
        } else {
            $this->realm = 'http://' . $_SERVER['SERVER_NAME'];
        }

        if (isset($options['ezrpPath'])) {
            $this->ezrpPath = $options['ezrpPath'];
        }

        $this->returnTo = rtrim($this->realm, '/') . $this->ezrpPath
                          . '/verify.php?ezrpd=' . $this->driver;
    }

    public function prepare(array $options = array())
    {
        try {
            $this->setIdentifier($options);
            $rp = $this->getORPInstance();

            $authRequest = $rp->prepare();
            // $this->addExtensions($authRequest);
            return $authRequest->getAuthorizeURL();
        } catch (OpenID_Exception $e) {
            throw new EZRP_Exception($e->getMessage(), $e->getCode());
        }
    }
    public function verify(array $options)
    {
    }
    public function getProfileData(array $options)
    {
    }
    public function getMap(array $options)
    {
    }
    public function addMap(array $options)
    {
    }

    protected function setIdentifier(array $options)
    {
        if (isset($options['openid-identifier'])) {
            $this->identifier = $options['openid-identier'];
        } else if (isset($_POST['openid-identifier'])) {
            $this->identifier = $_POST['openid-identier'];
        }
        return $this;
    }

    /**
     * Gets an instance of OpenID_RelyingParty.  Abstracted for testing.
     *
     * @return OpenID_RelyingParty
     */
    protected function getORPInstance()
    {
        // @codeCoverageIgnoreStart
        return new OpenID_RelyingParty($this->returnTo,
                                       $this->realm,
                                       $this->identifier);
        // @codeCoverageIgnoreEnd
    }
}
?>
