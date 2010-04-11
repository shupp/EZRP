<?php
/**
 * EZRP_Store_Interface 
 * 
 * PHP Version 5.0.0+ 
 * 
 * @uses      OpenID_Store_Interface
 * @uses      HTTP_OAuth_Store_Consumer_Interface
 * @category  Auth
 * @package   EZRP
 * @author    Bill Shupp <hostmaster@shupp.org> 
 * @copyright 2010 Bill Shupp
 * @license   http://www.opensource.org/licenses/bsd-license.php FreeBSD
 * @link      http://github.com/shupp/ezrp
 */

require_once 'OpenID/Store/Interface.php';
require_once 'HTTP/OAuth/Store/Consumer/Interface.php';

/**
 * A single interface for all EZRP storage needs.
 * 
 * @uses      OpenID_Store_Interface
 * @uses      HTTP_OAuth_Store_Consumer_Interface
 * @category  Auth
 * @package   EZRP
 * @author    Bill Shupp <hostmaster@shupp.org> 
 * @copyright 2010 Bill Shupp
 * @license   http://www.opensource.org/licenses/bsd-license.php FreeBSD
 * @link      http://github.com/shupp/ezrp
 */
interface EZRP_Store_Interface
extends OpenID_Store_Interface, HTTP_OAuth_Store_Consumer_Interface
{
}
?>
