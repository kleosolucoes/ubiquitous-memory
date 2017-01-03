<?php

namespace ${nomeModulo}\Controller\Factory;

use ${nomeModulo}\Controller\${nomeTabela}Controller;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Validator\Exception\ExtensionNotLoadedException;

/**
 * Nome: ${nomeTabela}ControllerFactory.php
 * Descricao: Classe para inicializar o controle
 */
class ${nomeTabela}ControllerFactory implements FactoryInterface {

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

        return new ${nomeTabela}Controller($doctrineORMEntityManager);
    }

}
