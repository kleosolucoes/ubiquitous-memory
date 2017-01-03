<?php

namespace Gerador\Controller;

use Exception;

/**
 * Class para gerar arquivos de um CRUD self::baseado no Doctrine no zend framework 2
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 */
class Gerador {

    private $nomeModulo;
    private $nomeNoPlural;
    private $nomeTabela;
    private $nomeVariavel;
    private $diretorios;
    private $templates;

    /* Diretorios */

    const base = 'base';
    const template = 'template';
    const modulo = 'modulo';
    const src = 'src';
    const view = 'view';
    const model = 'model';
    const entity = 'entity';
    const base_entity = 'base_entity';
    const form = 'form';
    const controller = 'controller';
    const controller_factory = 'controller_factory';

    /* Templates */
    const view_create = 'view_create';
    const view_recover = 'view_recover';
    const view_update = 'view_update';
    const view_delete = 'view_delete';
    const view_list = 'view_list';
    const test_bootstrap = 'test_bootstrap';
    const test_phpunit = 'test_phpunit';
    const test_controller = 'test_controller';
    const nome_tabela = '${nomeTabela}';
    const nome_no_plural = '${nomeNoPlural}';
    const nome_modulo = '${nomeModulo}';
    const nome_variavel = '${nomeVariavel}';

    /* Mensagens */
    const success = 'success';
    const error = 'error';

    /**
     * Contrutor sobrecarregado
     * Recebe o nome da tabela para gerar o modulo
     * @param string $nomeTabela
     * @param string $nomeModulo
     */
    public function __construct($nomeTabela, $nomeModulo) {
        $nomeModuloAjustado = strtolower($nomeModulo);
        $this->setNomeVariavel(strtolower($nomeTabela));
        $this->setNomeTabela(ucfirst($this->getNomeVariavel()));
        $this->setNomeNoPlural($this->getNomeTabela() . 's');
        $this->setNomeModulo(ucfirst($nomeModuloAjustado));
    }

    /**
     * Gera os arquivos do CRUD
     */
    public function gerarCRUD() {
        session_start();
        $this->criarDiretorios();
        $this->carregarTemplates();
        $this->escreverNovosArquivos();

        if (!empty($_SESSION[self::success])) {
            array_unshift($_SESSION[self::success], '<strong>Arquivos gerados para ' . $name . ':</strong>');
        }
        if (!empty($_SESSION[self::error])) {
            array_unshift($_SESSION[self::error], '<strong>Erros gerados para ' . $name . ':</strong>');
        }
    }

    /**
     * Carrega os diretorios
     */
    private function carregarDiretorios() {
        $diretorios = [];
//        $diretorios[self::base] = 'diretorio do projeto';
        $diretorios[self::base] = '/var/www/html/ubiquitous-memory';
        $diretorios[self::template] = $diretorios[self::base] . '/module/Gerador/src/Gerador/Templates';
        $diretorios[self::modulo] = $diretorios[self::base] . '/module/' . $this->getNomeModulo();
        $diretorios[self::src] = $diretorios[self::modulo] . '/src/' . $this->getNomeModulo();
        $diretorios[self::view] = $diretorios[self::modulo] . '/view/' . strtolower($this->getNomeModulo()) . '/' . $this->getNomeVariavel();
        $diretorios[self::model] = $diretorios[self::src] . '/Model';
        $diretorios[self::entity] = $diretorios[self::model] . '/Entity';
        $diretorios[self::form] = $diretorios[self::src] . '/Form';
        $diretorios[self::controller] = $diretorios[self::src] . '/Controller';
        $diretorios[self::controller_factory] = $diretorios[self::src] . '/Controller/Factory';
        $this->setDiretorios($diretorios);
    }

    /**
     * Cria os diretorios caso não existam
     */
    private function criarDiretorios() {
        $diretorios = $this->getDiretorios();
        if (!file_exists($diretorios[self::model])) {
            mkdir($diretorios[self::model]);
        }
        if (!file_exists($diretorios[self::view])) {
            mkdir($diretorios[self::view]);
        }
        if (!file_exists($diretorios[self::entity])) {
            mkdir($diretorios[self::entity]);
        }
        if (!file_exists($diretorios[self::form])) {
            mkdir($diretorios[self::form]);
        }
        if (!file_exists($diretorios[self::controller])) {
            mkdir($diretorios[self::class]);
        }
        if (!file_exists($diretorios[self::controller_factory])) {
            mkdir($diretorios[self::controller_factory]);
        }
    }

    /**
     * Carrega os templates
     */
    private function carregarTemplates() {
        $templates = [];
        $diretorios = $this->getDiretorios();
        $templates[self::controller] = $this->lerArquivo($diretorios[self::template] . '/Controller.php');
        $templates[self::controller_factory] = $this->lerArquivo($diretorios[self::template] . '/ControllerFactory.php');
        $templates[self::form] = $this->lerArquivo($diretorios[self::template] . '/Form.php');
        $templates[self::model] = $this->lerArquivo($diretorios[self::template] . '/Model.php');
        $templates[self::entity] = $this->lerArquivo($diretorios[self::template] . '/Entity.php');
        $templates[self::base_entity] = $this->lerArquivo($diretorios[self::template] . '/BaseEntity.php');
        $templates[self::view_create] = $this->lerArquivo($diretorios[self::template] . '/view_create.phtml');
        $templates[self::view_recover] = $this->lerArquivo($diretorios[self::template] . '/view_recover.phtml');
        $templates[self::view_update] = $this->lerArquivo($diretorios[self::template] . '/view_update.phtml');
        $templates[self::view_delete] = $this->lerArquivo($diretorios[self::template] . '/view_delete.phtml');
        $templates[self::view_list] = $this->lerArquivo($diretorios[self::template] . '/view_list.phtml');
        $this->setTemplates($templates);
    }

    /**
     * Tenta gravar o arquivo caso nao exista
     * @param string $arquivo
     */
    private function gravarArquivo($caminho, $template) {
        if (!file_exists($caminho)) {
            $handle = fopen($caminho, 'w') or die('Cannot write file:  ' . $caminho);
            fwrite($handle, $template);

            $_SESSION[self::success][] = $caminho;
        } else {
            $_SESSION[self::error][] = $caminho . ' já criado!';
        }
    }

    /**
     * Tenta gravar os arquivos e preenche as mensagens
     */
    private function escreverNovosArquivos() {
        $templates = $this->getTemplates();
        $diretorios = $this->getDiretorios();

        /* CRIA O ARQUIVO DO CONTROLLER */
        $caminhoNovoController = $diretorios[self::controller] . '/' . $this->getNomeTabela() . 'Controller.php';
        $this->gravarArquivo($caminhoNovoController, $this->subistituirVariaveisDosTemplates($templates[self::controller]));

        /* CRIA O ARQUIVO DO CONTROLLER FACTORY */
        $caminhoNovoControllerFactory = $diretorios[self::controller_factory] . '/' . $this->getNomeTabela() . 'ControllerFactory.php';
        $this->gravarArquivo($caminhoNovoControllerFactory, $this->subistituirVariaveisDosTemplates($templates[self::controller_factory]));

        /* CRIA O ARQUIVO DO FORM */
        $caminhoNovoForm = $diretorios[self::form] . '/' . $this->getNomeTabela() . 'Form.php';
        $this->gravarArquivo($caminhoNovoForm, $this->subistituirVariaveisDosTemplates($templates[self::form]));

        /* CRIA O ARQUIVO DO BASE ENTITY */
        $caminhoNovoBaseEntity = $diretorios[self::entity] . '/BaseEntity.php';
        $this->gravarArquivo($caminhoNovoBaseEntity, $this->subistituirVariaveisDosTemplates($templates[self::base_entity]));

        /* CRIA O ARQUIVO DO ENTITY */
        $caminhoNovoEntity = $diretorios[self::entity] . '/' . $this->getNomeTabela() . '.php';
        $this->gravarArquivo($caminhoNovoEntity, $this->subistituirVariaveisDosTemplates($templates[self::entity]));

        /* CRIA O ARQUIVO DO VIEW LIST */
        $caminhoNovoViewList = $diretorios[self::view] . '/list.phtml';
        $this->gravarArquivo($caminhoNovoViewList, $this->subistituirVariaveisDosTemplates($templates[self::view_list]));

        /* CRIA O ARQUIVO DO VIEW CREATE */
        $caminhoNovoViewCreate = $diretorios[self::view] . '/create.phtml';
        $this->gravarArquivo($caminhoNovoViewCreate, $this->subistituirVariaveisDosTemplates($templates[self::view_create]));

        /* CRIA O ARQUIVO DO VIEW RECOVER */
        $caminhoNovoViewRecover = $diretorios[self::view] . '/recover.phtml';
        $this->gravarArquivo($caminhoNovoViewRecover, $this->subistituirVariaveisDosTemplates($templates[self::view_recover]));

        /* CRIA O ARQUIVO DO VIEW UPDATE */
        $caminhoNovoViewUpdate = $diretorios[self::view] . '/update.phtml';
        $this->gravarArquivo($caminhoNovoViewUpdate, $this->subistituirVariaveisDosTemplates($templates[self::view_update]));

        /* CRIA O ARQUIVO DO VIEW DELETE */
        $caminhoNovoViewDelete = $diretorios[self::view] . '/delete.phtml';
        $this->gravarArquivo($caminhoNovoViewDelete, $this->subistituirVariaveisDosTemplates($templates[self::view_delete]));
    }

    /**
     * Substitui as variaveis pelas do construtor
     * @param type $template
     * @return type
     */
    public function subistituirVariaveisDosTemplates($template) {
        $templateSemNomeTabela = str_replace(self::nome_tabela, $this->getNomeTabela(), $template);
        $templateSemNomeModulo = str_replace(self::nome_modulo, $this->getNomeModulo(), $templateSemNomeTabela);
        $templateSemNomeNoPlural = str_replace(self::nome_no_plural, $this->getNomeNoPlural(), $templateSemNomeModulo);
        $templateSemNomeVariavel = str_replace(self::nome_variavel, $this->getNomeVariavel(), $templateSemNomeNoPlural);
        return $templateSemNomeVariavel;
    }

    /**
     * Tenta ler o arquivo pelo caminho passado ou finaliza
     * @param string $caminhoArquivo
     * @return string
     */
    private function lerArquivo($caminhoArquivo) {
        $handle = fopen($caminhoArquivo, 'r')
                or die('Cannot open file:  ' . $caminhoArquivo);
        $data = fread($handle, filesize($caminhoArquivo));
        return $data;
    }

    private function getDiretorios() {
        if (empty($this->diretorios)) {
            $this->carregarDiretorios();
        }
        return $this->diretorios;
    }

    private function setDiretorios($diretorios) {
        $this->diretorios = $diretorios;
    }

    private function getNomeModulo() {
        return $this->nomeModulo;
    }

    private function getNomeNoPlural() {
        return $this->nomeNoPlural;
    }

    private function getNomeTabela() {
        return $this->nomeTabela;
    }

    private function setNomeModulo($nomeModulo) {
        $this->nomeModulo = $nomeModulo;
    }

    private function setNomeNoPlural($nomeNoPlural) {
        $this->nomeNoPlural = $nomeNoPlural;
    }

    private function setNomeTabela($nomeTabela) {
        $this->nomeTabela = $nomeTabela;
    }

    private function getTemplates() {
        return $this->templates;
    }

    private function setTemplates($templates) {
        $this->templates = $templates;
    }

    private function getNomeVariavel() {
        return $this->nomeVariavel;
    }

    private function setNomeVariavel($nomeVariavel) {
        $this->nomeVariavel = $nomeVariavel;
    }

}
