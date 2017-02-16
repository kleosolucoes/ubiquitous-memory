<?php

/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Application\View\Helper\InputFormulario;
use Application\View\Helper\Botao;
use Application\View\Helper\FuncaoOnClick;
use Application\View\Helper\FuncaoOnClickSubmeterFormulario;
use Application\View\Helper\Splash;
use Application\View\Helper\CabecalhoPagina;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\Validator\AbstractValidator;
use Zend\Mvc\I18n\Translator;
use Zend\Session\Config\SessionConfig;
use Zend\Session\Container;
use Zend\Session\SessionManager;

class Module {

    public function onBootstrap(MvcEvent $e) {
        $eventManager = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
      
 
                //Pega o serviço translator definido no arquivo module.config.php (aliases)
		$translator = $e->getApplication ()->getServiceManager ()->get ( 'translator' );
 
                //Define o local onde se encontra o arquivo de tradução de mensagens
                $translator->addTranslationFile ( 'phpArray', './vendor/zendframework/zend-i18n-resources/languages/pt_BR/Zend_Validate.php' );
 
		//Define o local (você também pode definir diretamente no método acima
		$translator->setLocale ( 'pt_BR' );
                //Define a tradução padrão do Validator
		AbstractValidator::setDefaultTranslator ( new Translator ( $translator ) );
	
    }

    public function getConfig() {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig() {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function getViewHelperConfig() {
        return array(
            'factories' => array(
                'inputFormulario' => function($sm) {
                    return new InputFormulario();
                },
                'botao' => function($sm) {
                    return new Botao();
                },
                'funcaoOnClick' => function($sm) {
                    return new FuncaoOnClick();
                },
                'funcaoOnClickSubmeterFormulario' => function($sm) {
                    return new FuncaoOnClickSubmeterFormulario();
                },
                'splash' => function($sm) {
                    return new Splash();
                },
                'cabecalhoPagina' => function($sm) {
                    return new CabecalhoPagina();
                },
            )
        );
    }
	
	public function initSession($config) {
        $sessionConfig = new SessionConfig();
        $sessionConfig->setOptions($config);
        $sessionManager = new SessionManager($sessionConfig);
        $sessionManager->start();
        Container::setDefaultManager($sessionManager);
    }

}
