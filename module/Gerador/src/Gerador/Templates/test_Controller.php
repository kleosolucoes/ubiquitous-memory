<?php

namespace ${nomeModulo}Test\Controller;

use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;

class ${nomeTabela}ControllerTest extends AbstractHttpControllerTestCase
{
    protected $traceError = true;

    public function setUp()
    {
        $this->setApplicationConfig(
            include dirname(dirname(dirname(dirname(dirname(__DIR__))))).'/config/application.config.php'
        );
        $this->entityMock = $this->getMockBuilder('Doctrine\ORM\EntityManager')
            ->disableOriginalConstructor()
            ->getMock();
        parent::setUp();
    }

    public function testListActionCanBeAccessed()
    {
        $this->dispatch('/${nomeTabela}');
        $this->assertResponseStatusCode(200);
        $this->assertnomeModulo('${nomeModulo}');
        $this->assertControllerName('${nomeModulo}\Controller\${nomeTabela}');
        $this->assertControllerClass('${nomeTabela}Controller');
        $this->assertMatchedRouteName('${nomeTabela}');
    }

    public function testCreateActionCanBeAccessed()
    {
        $this->dispatch('/${nomeTabela}/create');
        $this->assertResponseStatusCode(200);
        $this->assertnomeModulo('${nomeModulo}');
        $this->assertControllerName('${nomeModulo}\Controller\${nomeTabela}');
        $this->assertControllerClass('${nomeTabela}Controller');
        $this->assertActionName('create');
        $this->assertMatchedRouteName('${nomeTabela}');
    }

    public function testCreateActionCanInsertNewData()
    {
        $entityMock = $this->entityMock;

        $entityMock->expects($this->once())
            ->method('persist')
            ->will($this->returnValue(null));
            // ->with($this->attributeEqualTo('descricao', 'Led Zeppelin III'));
        $entityMock->expects($this->once())
            ->method('flush')
            ->will($this->returnValue(null));

        $serviceManager = $this->getApplicationServiceLocator();
        $serviceManager->setAllowOverride(true);
        $serviceManager->setService('Doctrine\ORM\EntityManager', $entityMock);

        $postData = array(${arrayData});
        $this->dispatch('/${nomeTabela}/create', 'POST', $postData);
        $this->assertResponseStatusCode(302);
        $this->assertRedirectTo('/${nomeTabela}/');
    }
    
    public function testCreateActionCannotInsertInvalidData()
    {
        $post = array(${arrayData1});
        $this->dispatch('/${nomeTabela}/create', 'POST', $post);

        $this->assertQueryContentContains('form ul li', "Value is required and can't be empty");
        $this->assertResponseStatusCode(200);
        $this->assertnomeModulo('${nomeModulo}');
        $this->assertControllerName('${nomeModulo}\Controller\${nomeTabela}');
        $this->assertControllerClass('${nomeTabela}Controller');
        $this->assertActionName('create');
        $this->assertMatchedRouteName('${nomeTabela}');
    }

    public function testUpdateActionRedirectWithoutId()
    {
        $this->dispatch('/${nomeTabela}/update/0', 'GET');
        $this->assertResponseStatusCode(302);
        $this->assertRedirectTo('/${nomeTabela}/create');
    }

    public function testUpdateActionShowsData()
    {

        $${nomeVariavel} = new \${nomeModulo}\Entity\${nomeTabela}();
        $${nomeVariavel}->populate(array(${arrayData2}));

        $entityMock = $this->entityMock;
        $entityMock->expects($this->once())
            ->method('find')
            ->with('${nomeModulo}\Entity\${nomeTabela}', 7)
            // ->will($this->returnValue(null));
            ->will($this->returnValue($${nomeVariavel}));

        $serviceManager = $this->getApplicationServiceLocator();
        $serviceManager->setAllowOverride(true);
        $serviceManager->setService('Doctrine\ORM\EntityManager', $entityMock);

        $this->dispatch('/${nomeTabela}/update/7', 'GET');
        $this->assertResponseStatusCode(200);
        $this->assertnomeModulo('${nomeModulo}');
        $this->assertControllerName('${nomeModulo}\Controller\${nomeTabela}');
        $this->assertControllerClass('${nomeTabela}Controller');
        $this->assertActionName('update');
        $this->assertMatchedRouteName('${nomeTabela}');

        $this->assertContains('7', $this->getResponse()->getContent());

    }

    public function testRecoverActionRedirectWithoutId()
    {
        $this->dispatch('/${nomeTabela}/recover/0', 'GET');
        $this->assertResponseStatusCode(302);
        $this->assertRedirectTo('/${nomeTabela}/');
    }

    public function testRecoverActionCanBeAccessed()
    {

        $${nomeVariavel} = new \${nomeModulo}\Entity\${nomeTabela}();
        $${nomeVariavel}->populate(array(${arrayData2}));

        $entityMock = $this->entityMock;

        $entityMock->expects($this->once())
            ->method('find')
            ->with('${nomeModulo}\Entity\${nomeTabela}', 7)
            // ->will($this->returnValue(null));
            ->will($this->returnValue($${nomeVariavel}));

        $serviceManager = $this->getApplicationServiceLocator();
        $serviceManager->setAllowOverride(true);
        $serviceManager->setService('Doctrine\ORM\EntityManager', $entityMock);

        $this->dispatch('/${nomeTabela}/recover/7', 'GET');
        $this->assertResponseStatusCode(200);
        $this->assertnomeModulo('${nomeModulo}');
        $this->assertControllerName('${nomeModulo}\Controller\${nomeTabela}');
        $this->assertControllerClass('${nomeTabela}Controller');
        $this->assertActionName('recover');
        $this->assertMatchedRouteName('${nomeTabela}');

        $this->assertContains('7', $this->getResponse()->getContent());

    }

    public function testDeleteActionRedirectWithoutId()
    {
        $this->dispatch('/${nomeTabela}/delete/0', 'GET');
        $this->assertResponseStatusCode(302);
        $this->assertRedirectTo('/${nomeTabela}/');
    }

    public function testDeleteActionCanRemoveData()
    {
        $${nomeVariavel} = new \${nomeModulo}\Entity\${nomeTabela}();
        $${nomeVariavel}->populate(array(${arrayData2}));

        $entityMock = $this->entityMock;

        $entityMock->expects($this->once())
            ->method('find')
            ->with('${nomeModulo}\Entity\${nomeTabela}', 7)
            ->will($this->returnValue($${nomeVariavel}));

        $serviceManager = $this->getApplicationServiceLocator();
        $serviceManager->setAllowOverride(true);
        $serviceManager->setService('Doctrine\ORM\EntityManager', $entityMock);

        $this->dispatch('/${nomeTabela}/delete/7', 'GET');
        $this->assertResponseStatusCode(200);
        $this->assertnomeModulo('${nomeModulo}');
        $this->assertControllerName('${nomeModulo}\Controller\${nomeTabela}');
        $this->assertControllerClass('${nomeTabela}Controller');
        $this->assertActionName('delete');
        $this->assertMatchedRouteName('${nomeTabela}');

    }
}