<?php

require_once 'EZRP/Common.php';
require_once 'HTTP/OAuth/Consumer.php';

class EZRP_Twitter extends EZRP_Common
{
    protected $driver = 'twitter';
    protected $consumer = null;
    protected $key = null;
    protected $secret = null;
    protected $accessToken = null;
    protected $accessTokenSecret = null;
    protected $twitterID = null;
    protected $twitterScreeName = null;

    public function init(array $options)
    {
        parent::init($options);
        $this->key    = $options['twitter']['key'];
        $this->secret = $options['twitter']['secret'];
        $this->consumer = new HTTP_OAuth_Consumer($this->key, $this->secret);
    }
    public function prepare(array $options = array())
    {
        try {
            $this->consumer->getRequestToken('http://twitter.com/oauth/request_token',
                                             $this->verifyURL);

            // Store the request token and secret
            $this->ezrp->getStore()->setRequestToken($this->consumer->getToken(),
                                                     $this->consumer->getTokenSecret(),
                                                     $this->driver,
                                                     $this->sessionID);

            return $this->consumer->getAuthorizeURL('http://twitter.com/oauth/authenticate');
        } catch (HTTP_OAuth_Exception $e) {
            throw new Exception('Could not obtain request token');
        }
    }

    public function verify(array $options)
    {
        // Collect request tokens
        $data = $this->ezrp->getStore()->getRequestToken($this->driver,
                                                         $this->sessionID);
        if ($data === false) {
            throw new Exception('Request token expired');
        }


        // Make sure it's sane data
        if (!isset($data['token']) || !isset($data['tokenSecret'])) {
            throw new Exception('Request token expired');
        }

        // Extract oauth_verifier from the query
        $verifier = null;
        $qsArray  = explode('?', $_SERVER['REQUEST_URI']);
        if (isset($qsArray[1])) {
            parse_str($qsArray[1], $parsed);
            if (isset($parsed['oauth_verifier'])) {
                $verifier = $parsed['oauth_verifier'];
            }
        }
        if ($verifier === null) {
            throw new Exception('Invalid request');
        }

        try {
            $this->consumer->setToken($data['token']);
            $this->consumer->setTokenSecret($data['tokenSecret']);
            $this->consumer->getAccessToken('http://twitter.com/oauth/access_token',
                                            $verifier);
            $this->accessToken       = $this->consumer->getToken();
            $this->accessTokenSecret = $this->consumer->getTokenSecret();

            // Get user_id and screen_name from response body
            $body = $this->consumer->getLastResponse()->getBody();
            parse_str($body, $parsed);
            foreach ($parsed as $key => $value) {
                if ($key === 'user_id') {
                    $this->twitterID = $value;
                    continue;
                }

                if ($key === 'screen_name') {
                    $this->twitterScreenName = $value;
                    continue;
                }
            }
        } catch (HTTP_OAuth_Exception $e) {
            throw new Exception('Twitter login failed');
        }
        return array($this->twitterID, $this->twitterScreenName);
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
}
?>
