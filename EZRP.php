<?php
/**
 * EZRP
 *
 * PHP Version 5.0.0+
 *
 * @category  Services
 * @package   EZRP
 * @author    Bill Shupp <hostmaster@shupp.org>
 * @copyright 2010-2011 Bill Shupp
 * @license   http://www.opensource.org/licenses/bsd-license.php FreeBSD
 * @link      http://github.com/shupp/ezrp
 */

/**
 * Required files
 */
require_once 'EZRP/Exception.php';

/**
 * EZRP provides a consistent flow between multiple authentication interfaces,
 * including OpenID (relying party), OAuth (consumer), and Facebook Connet.
 *
 * @category  Services
 * @package   EZRP
 * @author    Bill Shupp <hostmaster@shupp.org>
 * @copyright 2010-2011 Bill Shupp
 * @license   http://www.opensource.org/licenses/bsd-license.php FreeBSD
 * @link      http://github.com/shupp/ezrp
 */
class EZRP
{
    /**
     * Instance of the storage driver
     *
     * @var EZRP_Store_Interface
     */
    protected $store = null;

    /**
     * Instance of the auth driver
     *
     * @var EZRP_Common
     */
    protected $driver = null;

    /**
     * Instantiates the driver
     *
     * @param mixed $driver  Driver name
     * @param array $options Optional options array
     *
     * @return void
     */
    public function __construct($driver, array $options = array())
    {
        $this->driver = $this->getDriver($driver, $options);
    }

    /**
     * Sets an instance of a storage instance to use
     *
     * @param EZRP_Store_Interface $store The storage object
     *
     * @return void
     */
    public function setStore(EZRP_Store_Interface $store)
    {
        $this->store = $store;
    }

    /**
     * Returns the instance of the storage object.  If none is set,
     * {@link EZRP_Store_CacheLite} is instantiated and returned.
     *
     * @return EZRP_Store_Interface
     */
    public function getStore()
    {
        if ($this->store === null) {
            include_once 'EZRP/Store/CacheLite.php';
            $this->store = new EZRP_Store_CacheLite();
        }
        return $this->store;
    }

    /**
     * Preparation step for the auth driver.  Returns a URL
     *
     * @param array $options Optional set of options for the prepare step
     *
     * @return string (url)
     */
    public function prepare(array $options = array())
    {
        $this->getStore();
        return $this->driver->prepare($options);
    }

    /**
     * Verification step for the auth driver.  Returns an array currenty, which
     * sucks.
     *
     * @param array $options Optional set of options to pass to the driver
     *
     * @return array()
     */
    public function verify(array $options = array())
    {
        $this->getStore();
        return $this->driver->verify($options);
    }

    /**
     * Creates an instance of the auth driver and returns it.
     *
     * @param string $driver  The auth driver name
     * @param array  $options Optional array of options to use
     *
     * @return EZRP_Common
     */
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
