<?php

namespace Bone\Mvc;

/**
 * Class Request
 * @package Bone\Mvc
 */
class Request
{


    /**
     * here be the $_POST super global
     *
     * @var array
     */
    protected $_post = array();


    /**
     * garrr! and the $_GET super global
     *
     * @var array
     */
    protected $_get = array();


    /**
     * If yer hungry this is where ye find th' $_COOKIE super global
     *
     * @var array
     */
    protected $_cookie = array();


    /**
     * This be the $_SERVER superglobal
     *
     * @var array $_server
     */
    protected $_server;

    private $controller;
    private $action;
    private $params;



    /**
     *  Cap'n! Incoming vessel!
     *                   Garrrr! What ship?
     *  It be the HTTP Request Cap'n!
     *                   Blustering barnacles, Prepare th' crew!
     *  Aye aye, cap'n!
     */
    public function __construct(array $get, array $post, array $cookie, array $server)
    {
        $this->_get =  $get;
        $this->_post = $post;
        $this->_cookie = $cookie;
        $this->_server = $server;
        $this->_clean();
    }
    /**
     * Allow access t' data stored in GET, POST and COOKIE super globals.
     *
     * @param string $var
     * @param string $key
     * @return mixed
     */
    public function getRawData($var, $key)
    {
        switch(strtolower($var)) {
            case 'get':
                $array = $this->_get;
                break;

            case 'post':
                $array = $this->_post;
                break;

            case 'cookie':
                $array = $this->_cookie;
                break;

            case 'server':
                $array = $this->_server;
                break;

            default:
                $array = array();
                break;
        }

        if(isset($array[$key])) {
            return $array[$key];
        }
        return null;
    }

    /**
     * Internally clean request data by handlin' magic_quotes_gpc and then addin' slashes.
     *
     */
    protected function _clean()
    {
        $this->_post = $this->_stripSlashes($this->_post);
        $this->_get = $this->_stripSlashes($this->_get);
    }

    /**
     * @param string $value
     * @return array
     */
    protected function _stripSlashes($value)
    {
        return array_map(array($this,'_stripSlashes'), $value);
    }

    /**
     *  Where th' feck are we?
     *
     * @return string
     */
    public function getURI()
    {
        return $this->_server['REQUEST_URI'];
    }

    /**
     *  We be wantin' the GET variables
     * @return array
     */
    public function getGet()
    {
        return $this->_get;
    }



    /**
     *  We be wantin' the COOKIE variables
     * @return array
     */
    public function getCookie()
    {
        return $this->_cookie;
    }



    /**
     *  We be wantin' the SERVER variables
     * @return array
     */
    public function getServer()
    {
        return $this->_server;
    }


    /**
     *  We be wantin' the POST variables
     * @return array
     */
    public function getPost()
    {
        return $this->_post;
    }


    /**
     *  Set the action name
     * @param $action
     * @return $this
     */
    public function setAction($action)
    {
        $this->action = $action;
        return $this;
    }

    /**
     * Give us the action name ya scurvy seadog
     */
    public function getAction()
    {
        return $this->action;
    }


    /**
     * set the controls for th' heart of the sun
     *
     * @param $controller
     * @return $this
     */
    public function setController($controller)
    {
        $this->controller = $controller;
        return $this;
    }


    /**
     *   What be the controller we have?
     */
    public function getController()
    {
        return $this->controller;
    }


    /**
     * set th' params
     * @param $params
     * @return $this
     */
    public function setParams($params)
    {
        $this->params = $params;
        return $this;
    }

    /**
     * @param $key
     * @param $value
     * @return $this
     */
    public function setParam($key, $value)
    {
        $this->params[$key] = $value;
        return $this;
    }


    /**
     * give us th' params
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * give us th' params
     * @param param
     */
    public function getParam($key)
    {
        return $this->params[$key];
    }



    /**
     * What type o' request be we havin' here?
     * @return mixed
     */
    public function getMethod()
    {
        return $this->_server["REQUEST_METHOD"];
    }
}