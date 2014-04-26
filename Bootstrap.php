<?php
/**
 * Bootstrap.php
 *
 * Creation date: 2014-04-24
 * Creation time: 19:02
 *
 * @author Arkadiusz Moskwa <a.moskwa@gmail.com>
 */

class Phonebook_Bootstrap extends Zend_Application_Module_Bootstrap
{
    /**
     * @param Zend_Application|Zend_Application_Bootstrap_Bootstrapper $application
     */
    public function __construct($application)
    {
        parent::__construct($application);
        $this->_loadModuleConfig();
    }

    /**
     * Ładuje konfig modułu z pliku
     */
    protected function _loadModuleConfig()
    {
        $iniFile = APPLICATION_PATH . '/modules/' . strtolower($this->getModuleName()) . '/configs/module.ini';

        if(!file_exists($iniFile))
            return;

        $config = new Zend_Config_Ini($iniFile, $this->getEnvironment());
        $this->setOptions($config->toArray());
    }

    /**
     * @return Zend_Registry
     */
    protected function _initRegistry()
    {
        $registry = Zend_Registry::getInstance();
        return $registry;
    }

    /**
     *
     *
     * @return Zend_Application_Module_Autoloader
     */
    protected function _initAutoload()
    {
        require_once ('vendor/autoload.php');

        $autoloader = new Zend_Application_Module_Autoloader(array(
            'namespace' =>  'Phonebook_',
            'basePath'  =>  dirname(__FILE__)
        ));
        return $autoloader;
    }

    /**
     * @return Zwig_View
     */
    public function _initView()
    {

        $view = new Zwig_View(array(
            'encoding'     =>  'UTF-8',
            'helperPath'    =>  array()
        ));

        $loader = new Twig_Loader_Filesystem(array());

        $zwig = new Zwig_Environment($view, $loader, array(
            'cache'         =>  APPLICATION_PATH . '/cache/twig',
            'auto_reload'   =>  true,
            'debug'         =>  true,
        ));
        $zwig->addExtension(new Twig_Extension_Debug());

        $view->setEngine($zwig);
        $view->doctype(Zend_View_Helper_Doctype::HTML5);

        $viewRenderer = new Zend_Controller_Action_Helper_ViewRenderer($view, array(
            'viewSuffix'    =>  'twig'
        ));
        Zend_Controller_Action_HelperBroker::addHelper($viewRenderer);

        Zend_Paginator::setDefaultScrollingStyle('Sliding');
        Zend_View_Helper_PaginationControl::setDefaultViewPartial(
            'pagination.twig'
        );
        $paginator = Zend_Paginator::factory(array());
        $paginator->setView($view);

        return $view;
    }

    /**
     * @return \Doctrine\ORM\EntityManager
     */
    public function _initDoctrine()
    {
        $config = new \Doctrine\ORM\Configuration();

        $cache = new \Doctrine\Common\Cache\ArrayCache();
        $config->setMetadataCacheImpl($cache);
        $config->setQueryCacheImpl($cache);

        $driver = $config->newDefaultAnnotationDriver(
            array(APPLICATION_PATH.'/modules/phonebook/models/Entity')
        );

        $config->setMetadataDriverImpl($driver);
        $config->setProxyDir(APPLICATION_PATH.'/modules/phonebook/models/Proxy');
        $config->setAutoGenerateProxyClasses(true);
        $config->setProxyNamespace('Phonebook\\Proxy');
        $connectionSettings = $this->getOption('doctrine');

        $connection = array(
            'driver'    =>  $connectionSettings['connection']['driver'],
            'user'      =>  $connectionSettings['connection']['user'],
            'password'  =>  $connectionSettings['connection']['password'],
            'dbname'    =>  $connectionSettings['connection']['dbname'],
            'host'      =>  $connectionSettings['connection']['host'],
        );

        $entityManager = \Doctrine\ORM\EntityManager::create($connection, $config);
        $registry = Zend_Registry::getInstance();
        $registry->entityManager = $entityManager;

        return $entityManager;
    }
} 