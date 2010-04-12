<?php
/**
 * EZRP_Store_CacheLite 
 * 
 * PHP Version 5.0.0+
 * 
 * @uses      EZRP_Store_Interface
 * @category  Auth
 * @package   EZRP
 * @author    Bill Shupp <hostmaster@shupp.org> 
 * @copyright 2010 Bill Shupp
 * @license   http://www.opensource.org/licenses/bsd-license.php FreeBSD
 * @link      http://github.com/shupp/ezrp
 */

require_once 'EZRP/Exception.php';
require_once 'EZRP/Store/Interface.php';
require_once 'OpenID/Store/CacheLite.php';
require_once 'OpenID.php';
require_once 'HTTP/OAuth/Store/Consumer/CacheLite.php';

/**
 * A CacheLite implemetation of EZRP_Store_Interface.  Just decorates the related
 * drivers in HTTP_OAuth and OpenID.
 * 
 * @uses      EZRP_Store_Interface
 * @category  Auth
 * @package   EZRP
 * @author    Bill Shupp <hostmaster@shupp.org> 
 * @copyright 2010 Bill Shupp
 * @license   http://www.opensource.org/licenses/bsd-license.php FreeBSD
 * @link      http://github.com/shupp/ezrp
 */
class EZRP_Store_CacheLite implements EZRP_Store_Interface
{
    /**
     * Local store of OpenID_Store_CacheLite
     * 
     * @var OpenID_Store_CacheLite
     */
    protected $openid = null;

    /**
     * Local store of HTTP_OAuth_Store_Consumer_CacheLite
     * 
     * @var Local store of HTTP_OAuth_Store_Consumer_CacheLite
     */
    protected $oauth = null;

    /**
     * Gets the local instance of OpenID_Store_CacheLite
     * 
     * @return OpenID_Store_CacheLite
     */
    protected function getOpenIDStore()
    {
        if ($this->openid === null) {
            $this->openid = new OpenID_Store_CacheLite();
            OpenID::setStore($this->openid);
        }
        return $this->openid;
    }

    /**
     * Gets the local instance of HTTP_OAuth_Store_Consumer_CacheLite
     * 
     * @return HTTP_OAuth_Store_Consumer_CacheLite
     */
    protected function getOAuthStore()
    {
        if ($this->oauth === null) {
            $this->oauth = new HTTP_OAuth_Store_Consumer_CacheLite();
        }
        return $this->oauth;
    }

    /**
     * Accepts custom instances of OpenID_Store_CacheLite or
     * HTTP_OAuth_Store_Consumer_CacheLite
     * 
     * @param mixed $object The store object to set
     * 
     * @see getOAuthStore()
     * @see getOpenIDStore()
     * @thows EZRP_Exception on failure
     * @return void
     */
    public function accept($object)
    {
        if ($object instanceof HTTP_OAuth_Consumer_Store_CacheLite) {
            $this->oauth = $object;
        } else if ($object instanceof OpenID_Store_CacheLite) {
            $this->openid = $object;
        } else {
            throw new EZRP_Exception('Invalid storage object');
        }
    }

    /**
     * Sets a request token
     * 
     * @param string $token        The request token
     * @param string $tokenSecret  The request token secret
     * @param string $providerName The name of the provider (i.e. 'twitter')
     * @param string $sessionID    A string representing this user's session
     * 
     * @return true on success, false or failure
     */
    public function setRequestToken($token, $tokenSecret, $providerName, $sessionID)
    {
        return $this->getOAuthStore()->setRequestToken($token,
                                                       $tokenSecret,
                                                       $providerName,
                                                       $sessionID);
    }

    /**
     * Gets a request token as an array of the token, tokenSecret, providerName,
     * and sessionID (array key names)
     * 
     * @param string $providerName The provider name (i.e. 'twitter')
     * @param string $sessionID    A string representing this user's session
     * 
     * @return array on success, false on failure
     */
    public function getRequestToken($providerName, $sessionID)
    {
        return $this->getOAuthStore()->getRequestToken($providerName, $sessionID);
    }

    /**
     * Gets access token data in the form of an HTTP_OAuth_Store_Data object
     * 
     * @param string $consumerUserID The end user's ID at the consumer
     * @param string $providerName   The provider name (i.e. 'twitter')
     * 
     * @return HTTP_OAuth_Data_Store
     */
    public function getAccessToken($consumerUserID, $providerName)
    {
        return $this->getOAuthStore()->getAccessToken($consumerUserID,
                                                      $providerName);
    }

    /**
     * Sets access token data from an HTTP_OAuth_Store_Data object
     * 
     * @param HTTP_OAuth_Store_Data $data The access token data
     * 
     * @return bool true on success, false on failure
     */
    public function setAccessToken(HTTP_OAuth_Store_Data $data)
    {
        return $this->getOAuthStore()->setAccessToken($data);
    }

    /**
     * Removes an access token
     * 
     * @param HTTP_OAuth_Store_Data $data The access token data
     * 
     * @return bool true on success, false or PEAR_Error on failure
     */
    public function removeAccessToken(HTTP_OAuth_Store_Data $data)
    {
        return $this->getOAuthStore()->removeAccessToken($data);
    }

    /**
     * Gets an OpenID_Discover object from storage
     * 
     * @param string $identifier The normalized identifier that discovery was 
     *                           performed on
     * 
     * @return OpenID_Discover
     */
    public function getDiscover($identifier)
    {
        return $this->getOpenIDStore()->getDiscover($identifier);
    }

    /**
     * Stores an instance of OpenID_Discover
     * 
     * @param OpenID_Discover $discover Instance of OpenID_Discover
     * @param int             $expire   How long to cache it for, in seconds
     * 
     * @return void
     */
    public function setDiscover(OpenID_Discover $discover, $expire = null)
    {
        return $this->getOpenIDStore()->setDiscover($discover, $expire);
    }

    /**
     * Gets an OpenID_Assocation instance from storage
     * 
     * @param string $uri    The OP endpoint URI to get an association for
     * @param string $handle The association handle if available
     * 
     * @return OpenID_Association
     */
    public function getAssociation($uri, $handle = null)
    {
        return $this->getOpenIDStore()->getAssociation($uri, $handle);
    }

    /**
     * Stores an OpenID_Association instance.  Details (such as endpoint url and 
     * exiration) are retrieved from the object itself.
     * 
     * @param OpenID_Association $association Instance of OpenID_Association
     * 
     * @return void
     */
    public function setAssociation(OpenID_Association $association)
    {
        return $this->getOpenIDStore()->setAssociation($association);
    }

    /**
     * Deletes an association from storage
     * 
     * @param string $uri OP Endpoint URI
     * 
     * @return void
     */
    public function deleteAssociation($uri)
    {
        return $this->getOpenIDStore()->deleteAssociation($uri);
    }

    /**
     * Gets a nonce from storage
     * 
     * @param string $nonce The nonce itself
     * @param string $opURL The OP Endpoint URL it was used with
     * 
     * @return string
     */
    public function getNonce($nonce, $opURL)
    {
        return $this->getOpenIDStore()->getNonce($nonce, $opURL);
    }

    /**
     * Stores a nonce for an OP endpoint URL
     * 
     * @param string $nonce The nonce itself
     * @param string $opURL The OP endpoint URL it was associated with
     * 
     * @return void
     */
    public function setNonce($nonce, $opURL)
    {
        return $this->getOpenIDStore->setNonce($nonce, $opURL);
    }

    /**
     * Deletes a nonce from storage
     * 
     * @param string $nonce The nonce to delete
     * @param string $opURL The OP endpoint URL it is associated with
     * 
     * @return void
     */
    public function deleteNonce($nonce, $opURL)
    {
        return $this->getOpenIDStore()->deleteNonce($nonce, $opURL);
    }
}
?>
