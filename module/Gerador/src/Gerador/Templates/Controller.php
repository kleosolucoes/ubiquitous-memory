<?php
namespace ${nomeModulo}\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use ${nomeModulo}\Form\${nomeTabela}Form;   
use Doctrine\ORM\EntityManager;   
use ${nomeModulo}\Entity\${nomeTabela};          
         
/**             
 * Caminho module/${nomeModulo}/src/${nomeModulo}/Controller/${nomeTabela}Controller.php
 */ 
class ${nomeTabela}Controller extends AbstractActionController
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
	

	public function listAction()
	{
		return new ViewModel(array(
			'${nomeNoPlural}' => $this->getDoctrineORMEntityManager()->getRepository('${nomeModulo}\Entity\${nomeTabela}')->findAll()
                        
			)
		);
	}

	public function recoverAction()
	{
		$id = (int) $this->params()->fromRoute('id', 0);
		if (!$id) {			
			return $this->redirect()->toRoute('${nomeTabela}', array(
				'action' => 'index'
				));
		}

		try {
			$${nomeVariavel} = $this->getDoctrineORMEntityManager()->find('${nomeModulo}\Entity\${nomeTabela}', $id);
		}
		catch (\Exception $ex) {
			return $this->redirect()->toRoute('${nomeTabela}', array(
				'action' => 'index'
				));
		}	

		return array(
			'id' => $id,
			'${nomeVariavel}' => $${nomeVariavel},
			);
	}

	public function createAction()
	{
		$form = new ${nomeTabela}Form();
		$form->get('submit')->setValue('Add');

		$request = $this->getRequest();
		if ($request->isPost()) {
			$${nomeVariavel} = new ${nomeTabela}();
			$form->setInputFilter($${nomeVariavel}->getInputFilter());
			$form->setData($request->getPost());

			if ($form->isValid()) {
				$${nomeVariavel}->populate($form->getData());
				$this->getDoctrineORMEntityManager()->persist($${nomeVariavel});
				$this->getDoctrineORMEntityManager()->flush();

				return $this->redirect()->toRoute('${nomeTabela}');
			}
		}

		return array('form' => $form);
	}

	public function updateAction()
	{
		$id = (int) $this->params()->fromRoute('id', 0);
		if (!$id) {
			return $this->redirect()->toRoute('${nomeTabela}', array(
				'action' => 'create'
				));
		}

		try {
			$${nomeVariavel} = $this->getDoctrineORMEntityManager()->find('${nomeModulo}\Entity\${nomeTabela}', $id);
		}
		catch (\Exception $ex) {
			return $this->redirect()->toRoute('${nomeTabela}', array(
				'action' => 'index'
				));
		}

		$form  = new ${nomeTabela}Form();
		$form->bind($${nomeVariavel});
		$form->get('submit')->setAttribute('value', 'Edit');

		$request = $this->getRequest();
		if ($request->isPost()) {
			$form->setInputFilter($${nomeVariavel}->getInputFilter());
			$form->setData($request->getPost());

			if ($form->isValid()) {
				$this->getDoctrineORMEntityManager()->flush();

				return $this->redirect()->toRoute('${nomeTabela}');
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
			return $this->redirect()->toRoute('${nomeTabela}');
		}

		$request = $this->getRequest();
		if ($request->isPost()) {
			$del = $request->getPost('del', 'Não');

			if ($del == 'Sim') {
				$id = (int) $request->getPost('id');
				$${nomeVariavel} = $this->getDoctrineORMEntityManager()->find('${nomeModulo}\Entity\${nomeTabela}', $id);
				if ($${nomeVariavel}) {
					$this->getDoctrineORMEntityManager()->remove($${nomeVariavel});
					$this->getDoctrineORMEntityManager()->flush();
				}
			}

			return $this->redirect()->toRoute('${nomeTabela}');
		}

		return array(
			'id'    => $id,
			'${nomeVariavel}' => $this->getDoctrineORMEntityManager()->find('${nomeModulo}\Entity\${nomeTabela}', $id)
			);
	}
}