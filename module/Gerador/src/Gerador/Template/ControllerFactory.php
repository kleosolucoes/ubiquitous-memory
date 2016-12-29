<?php

namespace Application\Controller\Factory;

use Application\Controller\${CtrlName}Controller;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Validator\Exception\ExtensionNotLoadedException;

/**
 * Nome: ${CtrlName}ControllerFactory.php
 * Descricao: Classe para inicializar o controle
 */
class ${CtrlName}ControllerFactory implements FactoryInterface {

    public function createService(ServiceLocatorInterface $serviceLocator) {
        $sm = $serviceLocator->getServiceLocator();

        // Serviço de Manipulação de entidade Doctrine    
        try {
            $doctrineORMEntityManager = $sm->get('Doctrine\ORM\EntityManager');
        } catch (ServiceNotCreatedException $e) {
            $doctrineORMEntityManager = null;
        } catch (ExtensionNotLoadedException $e) {
            $doctrineORMEntityManager = null;
        }

        return new ${CtrlName}Controller($doctrineORMEntityManager);
    }

}
