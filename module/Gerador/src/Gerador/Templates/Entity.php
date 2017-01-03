<?php
namespace ${nomeModulo}\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="${nomeTabela}")
 *
 */

class ${nomeTabela} extends BaseEntity
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer");
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
   
    public setId($id){
        $this->id = $id;
    }
    
    public getId(){
        $this->id;
    }
}