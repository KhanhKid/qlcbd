<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace QLCBDService\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
//
use Zend\Soap\Client;
use Zend\Soap\Server;
use Zend\Soap\AutoDiscover;

require_once 'e:\wamp\www\ZF2WebService\module\Mysoap\src\Services\servicesAPI.php';


class IndexController extends AbstractActionController
{
    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {

        return new ViewModel();
    }

    public function soapAction()
    {

        // initialize server and set URI
        $server = new Server(null,
            array('uri' => 'http://localhost/ZF2WebService/public/mysoap/index/wsdl'));

        // set SOAP service class
        $server->setClass('servicesAPI');

        // handle request
        $server->handle();

        return $this->getResponse();
    }

    public function wsdlAction()
    {
        // set up WSDL auto-discovery
        $wsdl = new AutoDiscover();

        // attach SOAP service class
        $wsdl->setClass('servicesAPI');

        // set SOAP action URI
        $wsdl->setUri('http://localhost/ZF2WebService/public/mysoap/index/soap');

        // handle request
        $wsdl->handle();

        return $this->getResponse();
    }

    public function clientAction()
    {
        $url = 'http://localhost/ZF2WebService/public/mysoap/index/wsdl';

        $client = new Client($url);

        print_r($client->getProducts());


        return $this->getResponse();
    }


}
