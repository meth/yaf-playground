<?php

// TODO: Document

namespace Afera\View;

class Twig implements \Yaf\View_Interface
{
    /**
     * @var \Twig_Environment
     */
    protected $_twig;

    protected $_vars = array();

    protected $_path;

    // TODO: Add some better exception handling and verbosity for the user
    public function __construct()
    {
        $defaults = array(
            'path' => APPLICATION_PATH . '/views',
            'options' => array(
                  'charset' => 'UTF-8',
                  'debug' => 'true'
            )
        );
        $twig = \Yaf\Registry::get(\Bootstrap::CONFIG_REGISTRY_KEY)->twig;
        $options = $twig->options->toArray() + $defaults['options'];

        $loader = new \Twig_Loader_Filesystem($twig->path);
        $this->_twig = new \Twig_Environment($loader, $options);
        $this->_twig->addGlobal('app', \Yaf\Application::app());

        if ($twig->options->debug) {
            $this->_twig->addExtension(new \Twig_Extension_Debug());
        }
    }

    public function __get($name)
    {
        return $this->_vars[$name];
    }

    public function __set($name, $value)
    {
        $this->assign($name, $value);
    }

    public function __isset($key)
    {
        return ($this->_vars[$key] !== null);
    }

    public function __unset($key)
    {
        unset($this->_vars[$key]);
    }

    /**
     * @param $name
     * @param $value
     *
     * @return void
     */
    public function assign ( $name = null, $value = null )
    {
        $this->_vars[$name] = $value;
    }

    /**
     * @param $name
     * @param $value
     *
     * @return string
     */
    public function display ( $name = null, $value = null )
    {
        echo $this->_twig->render($name, $this->_vars);
    }

    /**
     * @param $name
     * @param $value
     *
     * @return string
     */
    public function render ( $name = null, $value = null )
    {
        return $this->_twig->render($name, $this->_vars);
    }

    /**
     * @param string $path
     *
     * @return void
     */
    public function setScriptPath ( $path = null )
    {
        $this->_twig->getLoader()->addPath($path);
        $this->_path = $path;
    }

    /**
     * @return string
     */
    public function getScriptPath ()
    {
        return $this->_path;
    }

}