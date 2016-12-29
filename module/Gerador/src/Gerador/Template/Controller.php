<?php
namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Form\${CtrlName}Form;   
use Doctrine\ORM\EntityManager;   
use Application\Entity\${CtrlName};          
         
/**             
 * Caminho module/Modulo/src/Modulo/Controller/${CtrlName}Controller.php
 */ 
class ${CtrlName}Controller extends AbstractActionController
{
    
    private $_doctrineORMEntityManager;

    /**
     * Contrutor sobrecarregado com os serviços de ORM
     */
    public function __construct(EntityManager $doctrineORMEntityManager = null) {

        if (!is_null($doctrineORMEntityManager)) {
            $this->_doctrineORMEntityManager = $doctrineORMEntityManager;
        }
    }
    
      /**
     * Recupera ORM
     * @return EntityManager
     */
    public function getDoctrineORMEntityManager() {
        return $this->_doctrineORMEntityManager;
    }
	

	public function indexAction()
	{
		return new ViewModel(array(
//			'${ctrlNames}' => $this->getDoctrineORMEntityManager()->getRepository('Application\Entity\${CtrlName}')->findAll()
                        '${ctrlNames}' => null
			)
		);
	}

	public function viewAction()
	{
		$id = (int) $this->params()->fromRoute('id', 0);
		if (!$id) {			
			return $this->redirect()->toRoute('${ctrlName}', array(
				'action' => 'index'
				));
		}

		try {
			$${ctrlName} = $this->getDoctrineORMEntityManager()->find('Application\Entity\${CtrlName}', $id);
		}
		catch (\Exception $ex) {
			return $this->redirect()->toRoute('${ctrlName}', array(
				'action' => 'index'
				));
		}	

		return array(
			'id' => $id,
			'${ctrlName}' => $${ctrlName},
			);
	}

	public function addAction()
	{
		$form = new ${CtrlName}Form();
		$form->get('submit')->setValue('Add');

		$request = $this->getRequest();
		if ($request->isPost()) {
			$${ctrlName} = new ${CtrlName}();
			$form->setInputFilter($${ctrlName}->getInputFilter());
			$form->setData($request->getPost());

			if ($form->isValid()) {
				$${ctrlName}->populate($form->getData());
				$this->getDoctrineORMEntityManager()->persist($${ctrlName});
				$this->getDoctrineORMEntityManager()->flush();

				return $this->redirect()->toRoute('${ctrlName}');
			}
		}

		return array('form' => $form);
	}

	public function editAction()
	{
		$id = (int) $this->params()->fromRoute('id', 0);
		if (!$id) {
			return $this->redirect()->toRoute('${ctrlName}', array(
				'action' => 'add'
				));
		}

		try {
			$${ctrlName} = $this->getDoctrineORMEntityManager()->find('Application\Entity\${CtrlName}', $id);
		}
		catch (\Exception $ex) {
			return $this->redirect()->toRoute('${ctrlName}', array(
				'action' => 'index'
				));
		}

		$form  = new ${CtrlName}Form();
		$form->bind($${ctrlName});
		$form->get('submit')->setAttribute('value', 'Edit');

		$request = $this->getRequest();
		if ($request->isPost()) {
			$form->setInputFilter($${ctrlName}->getInputFilter());
			$form->setData($request->getPost());

			if ($form->isValid()) {
				$this->getDoctrineORMEntityManager()->flush();

				return $this->redirect()->toRoute('${ctrlName}');
			}
		}

		return array(
			'id' => $id,
			'form' => $form,
			);
	}

	public function deleteAction()
	{
		$id = (int) $this->params()->fromRoute('id', 0);
		if (!$id) {
			return $this->redirect()->toRoute('${ctrlName}');
		}

		$request = $this->getRequest();
		if ($request->isPost()) {
			$del = $request->getPost('del', 'Não');

			if ($del == 'Sim') {
				$id = (int) $request->getPost('id');
				$${ctrlName} = $this->getDoctrineORMEntityManager()->find('Application\Entity\${CtrlName}', $id);
				if ($${ctrlName}) {
					$this->getDoctrineORMEntityManager()->remove($${ctrlName});
					$this->getDoctrineORMEntityManager()->flush();
				}
			}

			return $this->redirect()->toRoute('${ctrlName}');
		}

		return array(
			'id'    => $id,
			'${ctrlName}' => $this->getDoctrineORMEntityManager()->find('Application\Entity\${CtrlName}', $id)
			);
	}
}