<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{

protected function _initResourceAutoloader()
{
     $autoloader = new Zend_Loader_Autoloader_Resource(array(
        'basePath'  => 'path/to/application/models',
        'namespace' => 'Application_',
     ));

     return $autoloader;
}

}

