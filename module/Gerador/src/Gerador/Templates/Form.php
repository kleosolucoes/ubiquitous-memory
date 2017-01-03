<?php 
namespace ${nomeModulo}\Form;

use Zend\Form\Form;

/**             
 * Caminho module/${nomeModulo}/src/${nomeModulo}/Form/${nomeTabela}Form.php
 */
class ${nomeTabela}Form extends Form
{
	public function __construct($name = null)
	{
         // we want to ignore the name passed
		parent::__construct('${nomeTabela}');

		$this->add(array(
			'name' => 'id',
			'type' => 'Hidden',
			));

		/* Todo adicionar campos do formulario*/

		$this->add(array(
           'name' => 'submit',
           'type' => 'Submit',
          'attributes' => array(
               'value' => 'Enviar',
               'id' => 'submitbutton',
               ),
           ));
	}
}