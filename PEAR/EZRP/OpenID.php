<?php

require_once 'EZRP/Common.php';
require_once 'OpenID/RelyingParty.php';
require_once 'Net/URL2.php';

class EZRP_OpenID extends EZRP_Common
{
    protected $driver = 'openid';
    protected $identifier = null;
    protected $assertedID = null;

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
        $this->setIdentifier($options);

        try {
            $verifyResults = $this->getVerifyResultsAndMessage();

            if ($verifyResults['result']->success()) {
                // $this->extractedProfileData = $this->extractProfileData($verifyResults['result']->getDiscover(),
                                                                        // $verifyResults['message']);
                $this->assertedID = $verifyResults['message']->get('openid.claimed_id');
                return $this->assertedID;
            }
            if ($verifyResults['result']->getAssertionMethod() == OpenID::MODE_CANCEL) {
                $message = "OpenID assertion canceled.  Thank you, come again!";
            } else {
                $message = "OpenID assertion unsuccessful.  This is probably Bill's fault.";
            }
            throw new EZRP_Exception($message);
        } catch (OpenID_Exception $e) {
            throw new EZRP_Exception($e->getMessage(), $e->getCode());
        }
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
        return new OpenID_RelyingParty($this->verifyURL,
                                       $this->baseURL,
                                       $this->identifier);
        // @codeCoverageIgnoreEnd
    }

    /**
     * Creates an assertion object and tries to verify its validity.
     *
     * @return array of the OpenID_Assertion_Result and OpenID_Message objects
     */
    public function getVerifyResultsAndMessage()
    {
        $rp = $this->getORPInstance();

        $url = 'http';
        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') {
            $url .= 's';
        }
        $url .= '://' . $_SERVER['SERVER_NAME'];
        if ($_SERVER['SERVER_PORT'] != '80') {
            $url .= ':' . $_SERVER['SERVER_PORT'];
        }
        $url .= $_SERVER['PHP_SELF'];

        // Get the message contents
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Can't use $_POST, it fucks with the keys (. -> _)
            $queryString = $this->getPHPInput();
        } else if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            $netURL      = new Net_URL2($url . '?' . $_SERVER['QUERY_STRING']);
            $queryString = $netURL->getQuery();
            if ($queryString === false) {
                throw new OpenID_Exception('Invalid request');
            }
        } else {
            throw new OpenID_Exception('Unknown HTTP method');
        }

        $requestedURL = $url. '?' . $queryString;
        $message      = new OpenID_Message($queryString,
                                           OpenID_Message::FORMAT_HTTP);

        $result = $rp->verify(new Net_URL2($requestedURL), $message);

        return array(
            'result'      => $result,
            'message'     => $message
        );
    }

    /**
     * Gets the contents of php://input.  Abstracted for testing, as you can't
     * write to this resource.
     *
     * @return void
     */
    protected function getPHPInput()
    {
        // @codeCoverageIgnoreStart
        static $input = null;
        if ($input === null) {
            $input = file_get_contents('php://input');
        }
        return $input;
        // @codeCoverageIgnoreEnd
    }
}
?>
