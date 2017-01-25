<?php

namespace Application\Controller;

use Doctrine\ORM\EntityManager;
use Zend\View\Model\ViewModel;
use Application\Model\Entity\Responsavel;
use Application\Model\Entity\ResponsavelSituacao;
use Application\Model\Entity\Empresa;
use Application\Model\Entity\EmpresaSituacao;
use Application\Model\Entity\Shopping;
use Application\Model\ORM\RepositorioORM;
use Application\Form\CadastroResponsavelForm;
use Application\Form\CadastroEmpresaForm;
use Application\Form\CadastroShoppingForm;
use Application\Form\ResponsavelSituacaoForm;
use Application\Form\ResponsavelAtualizacaoForm;
use Application\Form\KleoForm;

/**
 * Nome: CadastroController.php
 * @author Leonardo Pereira Magalhães <falecomleonardopereira@gmail.com>
 * Descricao: Controle de todas ações de crud
 */
class CadastroController extends KleoController {

  /**
     * Contrutor sobrecarregado com os serviços de ORM
     */
  public function __construct(EntityManager $doctrineORMEntityManager = null) {

    if (!is_null($doctrineORMEntityManager)) {
      parent::__construct($doctrineORMEntityManager);
    }
  }

  /**
     * Função padrão, traz a tela principal
     * GET /cadastro
     */
  public function indexAction() {    
    return new ViewModel();
  }

  /**
     * Função padrão, traz a tela principal
     * GET /cadastroResponsavel
     */
  public function responsavelAction() {

    $formulario = $this->params()->fromRoute(self::stringFormulario);
    if($formulario){
      $cadastroResponsavelForm = $formulario;
    }else{
      $cadastroResponsavelForm = new CadastroResponsavelForm('cadastroResponsavel');
    }

    return new ViewModel(
      array(
      self::stringFormulario => $cadastroResponsavelForm,
    )
    );
  }

  /**
     * Função padrão, traz a tela principal
     * GET /cadastroResponsavelFinalizar
     */
  public function responsavelFinalizarAction() {
    $request = $this->getRequest();
    if ($request->isPost()) {
      try {
        $post_data = $request->getPost();
        $responsavel = new Responsavel();
        $cadastrarResponsavelForm = new CadastroResponsavelForm();
        $cadastrarResponsavelForm->setInputFilter($responsavel->getInputFilterCadastrarResponsavel());
        $cadastrarResponsavelForm->setData($post_data);

        /* validação */
        if ($cadastrarResponsavelForm->isValid()) {
          $validatedData = $cadastrarResponsavelForm->getData();
          $responsavel->exchangeArray($cadastrarResponsavelForm->getData());
          $repositorioORM = new RepositorioORM($this->getDoctrineORMEntityManager());                  
          $repositorioORM->getResponsavelORM()->persistir($responsavel);

          $situacao = $repositorioORM->getSituacaoORM()->encontrarPorId(1);
          $responsavelSituacao = new ResponsavelSituacao();
          $responsavelSituacao->setResponsavel($responsavel);
          $responsavelSituacao->setSituacao($situacao);

          $repositorioORM->getResponsavelSituacaoORM()->persistir($responsavelSituacao);

          $emails[] = $validatedData[KleoForm::inputEmail];

          $titulo = self::emailTitulo;
          $mensagem = '<p>Seu cadastro inicial foi concluido</p>
          <p>Em breve um dos nosso executivos entrará em contato.</p>';

          self::enviarEmail($emails, $titulo, $mensagem);
          unset($emails);
          $emails[] = self::emailLeo;
          $emails[] = self::emailKort;

          $titulo = 'Primeiro Contato';
          $mensagem = '<p>NomeFantasia '. $responsavel->getNomeFantasia(). '</p>';
          $mensagem .= '<p>Resposavel '. $responsavel->getNome(). '</p>';
          $mensagem .= '<p>Telefone <a href="tel:'.$responsavel->getTelefone().'">'.$responsavel->getTelefone().'</a></p>';
          $mensagem .= '<p>Email '. $responsavel->getEmail(). '</p>';

          self::enviarEmail($emails, $titulo, $mensagem);

          return $this->redirect()->toRoute(self::rotaCadastro, array(
            self::stringAction => 'responsavelFinalizado',
            self::stringMensagem => 'Cadastro concluido.'
          ));
        } else {
          return $this->forward()->dispatch(self::controllerCadastro, array(
            self::stringAction => 'responsavel',
            self::stringFormulario => $cadastrarResponsavelForm,
          ));
        }
      } catch (Exception $exc) {
        echo $exc->getMessage();
      }
    }
    return new ViewModel();
  }

  /**
     * Função padrão, traz a tela principal
     * GET /cadastroResponsavelFinalizado
     */
  public function responsavelFinalizadoAction() {

    $mensagem = $this->params()->fromRoute(self::stringMensagem);

    return new ViewModel(
      array(self::stringMensagem => $mensagem,)
    );
  }

  /**
     * Função padrão, traz a tela principal
     * GET /cadastroResponsaveis
     */
  public function responsaveisAction() {
    $this->setLayoutAdm();
    $repositorioORM = new RepositorioORM($this->getDoctrineORMEntityManager());
    $responsaveis = $repositorioORM->getResponsavelORM()->encontrarTodos();
    return new ViewModel(
      array(
      'responsaveis' => $responsaveis,
    )
    );
  }

  /**
     * Formulario para alterar situacao
     * GET /cadastroResponsavelSituacao
     */
  public function responsavelSituacaoAction() {
    $this->setLayoutAdm();    
    $this->getSessao();

    $repositorioORM = new RepositorioORM($this->getDoctrineORMEntityManager());
    $sessao = self::getSessao();
    $idResponsavel = $sessao->idSessao;
    if(empty($idResponsavel)){
      return $this->redirect()->toRoute(self::rotaCadastro, array(
        self::stringAction => 'responsaveis',
      ));
    }
    unset($sessao->idSessao);

    $responsavel = $repositorioORM->getResponsavelORM()->encontrarPorId($idResponsavel); 
    $situacoes = $repositorioORM->getSituacaoORM()->encontrarTodas();

    $responsavelSituacaoForm = new ResponsavelSituacaoForm('ResponsavelSituacao', $idResponsavel, $situacoes, 
                                                           $responsavel->getResponsavelSituacaoAtivo()->getSituacao()->getId());
    return new ViewModel(
      array(
      self::stringFormulario => $responsavelSituacaoForm,
      'responsavel' => $responsavel,
    ));
  }


  /**
  * Ação para alterar situacao
  * GET /cadastroResponsavelSituacaoFinalizar
  */
  public function responsavelSituacaoFinalizarAction() {
    $request = $this->getRequest();
    if ($request->isPost()) {
      try {
        $post_data = $request->getPost();
        $repositorioORM = new RepositorioORM($this->getDoctrineORMEntityManager());                  
        $responsavel = $repositorioORM->getResponsavelORM()->encontrarPorId($post_data[KleoForm::inputId]);

        $gerar = false;
        if($responsavel->getResponsavelSituacaoAtivo()->getSituacao()->getId() !== intval($post_data[KleoForm::inputSituacao])){
          $gerar = true;
        }
        if($gerar){
          $token = '';
          $situacaoAguardandoDocumentacao = 2;
          $situacaoAtivo = 4;

          if(intval($post_data[KleoForm::inputSituacao]) === $situacaoAguardandoDocumentacao){
            $token = $responsavel->gerarToken();
            $responsavel->setToken($token);
            $repositorioORM->getResponsavelORM()->persistir($responsavel, false);
          }

          $responsavelSituacaoAtivo = $responsavel->getResponsavelSituacaoAtivo();
          $responsavelSituacaoAtivo->setDataEHoraDeInativacao();
          $repositorioORM->getResponsavelSituacaoORM()->persistir($responsavelSituacaoAtivo, false);

          $situacao = $repositorioORM->getSituacaoORM()->encontrarPorId($post_data[KleoForm::inputSituacao]);
          $responsavelSituacao = new ResponsavelSituacao();
          $responsavelSituacao->setResponsavel($responsavel);
          $responsavelSituacao->setSituacao($situacao);

          $repositorioORM->getResponsavelSituacaoORM()->persistir($responsavelSituacao);

          $emails[] = $responsavel->getEmail();
          $titulo = self::emailTitulo;
          $mensagem = '';
          if(intval($post_data[KleoForm::inputSituacao]) === $situacaoAguardandoDocumentacao){
            $mensagem = '<p>Precisamos que você atualize seus dados</p>';
            $mensagem .= '<p>Clique no link abaixo para atualizar</p>';
            $mensagem .= '<p><a href="'.self::url.'cadastroResponsavelAtualizacao/'.$token.'">Clique aqui</a></p>';
          }
          if(intval($post_data[KleoForm::inputSituacao]) === $situacaoAtivo){
            $mensagem = '<p>Cadastro ativado</p>';
            $mensagem .= '<p>Usuario: '.$responsavel->getEmail().'</p>';
            $mensagem .= '<p>Senha: 123456</p>';
            $mensagem .= '<p><a href="'.self::url.'">Clique aqui para acessar</a></p>';
          }
          self::enviarEmail($emails, $titulo, $mensagem);
        }

        return $this->redirect()->toRoute(self::rotaCadastro, array(
          self::stringAction => 'responsaveis',
        ));

      } catch (Exception $exc) {
        echo $exc->getMessage();
      }
    }
  }


  /**
     * Formulario para alterar situacao
     * GET /cadastroResponsavelAtualizacao
     */
  public function responsavelAtualizacaoAction() {

    $formulario = $this->params()->fromRoute(self::stringFormulario);
    if($formulario){
      $responsavelAtualizacaoForm = $formulario;
    }else{
      $repositorioORM = new RepositorioORM($this->getDoctrineORMEntityManager());
      $token = $this->getEvent()->getRouteMatch()->getParam(self::stringToken);
      $responsavel = $repositorioORM->getResponsavelORM()->encontrarPorToken($token); 
      $responsavel->setId($token);
      $responsavelAtualizacaoForm = new ResponsavelAtualizacaoForm('ResponsavelAtualizacao', $responsavel);
    }
    return new ViewModel(
      array(
      self::stringFormulario => $responsavelAtualizacaoForm,
    ));
  }


  /**
     * Atualiza os dados do responsavel
     * GET /cadastroResponsavelAtualizar
     */
  public function responsavelAtualizarAction() {
    $request = $this->getRequest();
    if ($request->isPost()) {
      try {
        $post_data = $request->getPost();
        $repositorioORM = new RepositorioORM($this->getDoctrineORMEntityManager());
        $token = $post_data[KleoForm::inputId];
        $responsavel = $repositorioORM->getResponsavelORM()->encontrarPorToken($token); 

        $responsavelAtualizacaoForm = new ResponsavelAtualizacaoForm(null, $responsavel);
        $responsavelAtualizacaoForm->setInputFilter($responsavel->getInputFilterAtualizarResponsavel());

        $post = array_merge_recursive(
          $request->getPost()->toArray(),
          $request->getFiles()->toArray()
        );

        $responsavelAtualizacaoForm->setData($post);

        if ($responsavelAtualizacaoForm->isValid()) {

          $responsavel->exchangeArray($responsavelAtualizacaoForm->getData());
          $responsavel->setToken(null);

          $responsavel = self::escreveDocumentos($responsavel);

          $repositorioORM = new RepositorioORM($this->getDoctrineORMEntityManager());                  
          $repositorioORM->getResponsavelORM()->persistir($responsavel);

          $responsavelSituacaoAtivo = $responsavel->getResponsavelSituacaoAtivo();
          $responsavelSituacaoAtivo->setDataEHoraDeInativacao();
          $repositorioORM->getResponsavelSituacaoORM()->persistir($responsavelSituacaoAtivo, false);

          $situacaoDocumentacaoEntregues = 3;
          $situacao = $repositorioORM->getSituacaoORM()->encontrarPorId($situacaoDocumentacaoEntregues);
          $responsavelSituacao = new ResponsavelSituacao();
          $responsavelSituacao->setResponsavel($responsavel);
          $responsavelSituacao->setSituacao($situacao);

          $repositorioORM->getResponsavelSituacaoORM()->persistir($responsavelSituacao);

          $emails[] = $validatedData[KleoForm::inputEmail];

          $titulo = self::emailTitulo;
          $mensagem = '<p>Dados atualizados.</p>
                    <p>Em breve um dos nosso executivos entrará em contato.</p>';

          self::enviarEmail($emails, $titulo, $mensagem);
          unset($emails);
          $emails[] = self::emailLeo;
          $emails[] = self::emailKort;

          $titulo = 'Documentos Entregues';
          $mensagem = '<p>NomeFantasia '. $responsavel->getNomeFantasia(). '</p>';
          $mensagem .= '<p>Responsavel '. $responsavel->getNome(). '</p>';
          $mensagem .= '<p>Telefone <a href="tel:'.$responsavel->getTelefone().'">'.$responsavel->getTelefone().'</a></p>';
          $mensagem .= '<p>Email '. $responsavel->getEmail(). '</p>';

          self::enviarEmail($emails, $titulo, $mensagem);

          return $this->redirect()->toRoute(self::rotaCadastro, array(
            self::stringAction => 'responsavelFinalizado',
            self::stringMensagem => 'Atualização concluida.'
          ));
        } else {
          return $this->forward()->dispatch(self::controllerCadastro, array(
            self::stringAction => 'responsavelAtualizacao',
            self::stringFormulario => $responsavelAtualizacaoForm,
          ));
        }
      } catch (Exception $exc) {
        echo $exc->getMessage();
      }
    }
    return new ViewModel();
  }

  /**
     * Seta o layout da administracao
     */
  private function setLayoutAdm() {
    $this->layout('layout/adm');
  }


  /**
     * Função de cadastro de empresa
     * GET /cadastroEmpresa
     */
  public function empresaAction() {
    $this->setLayoutAdm();

    $formulario = $this->params()->fromRoute(self::stringFormulario);

    if($formulario){
      $cadastroEmpresaForm = $formulario;
    }else{
      $cadastroEmpresaForm = new CadastroEmpresaForm('cadastroEmpresa');
    }

    return new ViewModel(
      array(
      self::stringFormulario => $cadastroEmpresaForm,
    )
    );
  }

  /**
     * Função para validar e finalizar cadastro
     * GET /cadastroEmpresaFinalizar
     */
  public function empresaFinalizarAction() {
    $request = $this->getRequest();
    if ($request->isPost()) {
      try {
        $post_data = $request->getPost();
        $empresa = new Empresa();
        $cadastrarEmpresaForm = new CadastroEmpresaForm();
        $cadastrarEmpresaForm->setInputFilter($empresa->getInputFilterCadastrarEmpresa());
        $cadastrarEmpresaForm->setData($post_data);

        /* validação */
        if ($cadastrarEmpresaForm->isValid()) {
          $validatedData = $cadastrarEmpresaForm->getData();
          $empresa->exchangeArray($cadastrarEmpresaForm->getData());
          $empresa->setTelefone($validatedData[KleoForm::inputDDD]
                                . $validatedData[KleoForm::inputTelefone]);
          $repositorioORM = new RepositorioORM($this->getDoctrineORMEntityManager());                  
          $repositorioORM->getEmpresaORM()->persistir($empresa);

          $situacao = $repositorioORM->getSituacaoORM()->encontrarPorId(1);
          $empresaSituacao = new EmpresaSituacao();
          $empresaSituacao->setEmpresa($empresa);
          $empresaSituacao->setSituacao($situacao);

          $repositorioORM->getEmpresaSituacaoORM()->persistir($empresaSituacao);

          return $this->redirect()->toRoute(self::rotaCadastro, array(
            self::stringAction => 'empresas',
          ));
        } else {

          return $this->forward()->dispatch(self::controllerCadastro, array(
            self::stringAction => 'empresa',
            self::stringFormulario => $cadastrarEmpresaForm,
          ));
        }
      } catch (Exception $exc) {
        echo $exc->getMessage();
      }
    }
    return new ViewModel();
  }

  /**
     * Tela com listagem de empresas
     * GET /cadastroEmpresas
     */
  public function empresasAction() {
    $this->setLayoutAdm();
    $repositorioORM = new RepositorioORM($this->getDoctrineORMEntityManager());
    $empresas = $repositorioORM->getEmpresaORM()->encontrarTodas();
    $situacoes = $repositorioORM->getSituacaoORM()->encontrarTodas();
    return new ViewModel(
      array(
      'empresas' => $empresas,
      'situacoes' => $situacoes,
    )
    );
  }

  /**
     * Função de cadastro de shopping
     * GET /cadastroShopping
     */
  public function shoppingAction() {
    $this->setLayoutAdm();

    $formulario = $this->params()->fromRoute(self::stringFormulario);

    if($formulario){
      $cadastroShoppingForm = $formulario;
    }else{
      $cadastroShoppingForm = new CadastroShoppingForm('cadastroShopping');
    }

    return new ViewModel(
      array(
      self::stringFormulario => $cadastroShoppingForm,
    )
    );
  }

  /**
     * Função para validar e finalizar cadastro
     * GET /cadastroShoppingFinalizar
     */
  public function shoppingFinalizarAction() {
    $request = $this->getRequest();
    if ($request->isPost()) {
      try {
        $post_data = $request->getPost();
        $shopping = new Shopping();
        $cadastrarShoppingForm = new CadastroShoppingForm();
        $cadastrarShoppingForm->setInputFilter($shopping->getInputFilterCadastrarShopping());
        $cadastrarShoppingForm->setData($post_data);

        /* validação */
        if ($cadastrarShoppingForm->isValid()) {
          $validatedData = $cadastrarShoppingForm->getData();
          $shopping->exchangeArray($cadastrarShoppingForm->getData());
          $repositorioORM = new RepositorioORM($this->getDoctrineORMEntityManager());                  
          $repositorioORM->getShoppingORM()->persistir($shopping);

          return $this->redirect()->toRoute(self::rotaCadastro, array(
            self::stringAction => 'shoppings',
          ));
        } else {
          return $this->forward()->dispatch(self::controllerCadastro, array(
            self::stringAction => 'shopping',
            self::stringFormulario => $cadastrarShoppingForm,
          ));
        }
      } catch (Exception $exc) {
        echo $exc->getMessage();
      }
    }
    return new ViewModel();
  }

  /**
     * Tela com listagem de shoppings
     * GET /cadastroShoppings
     */
  public function shoppingsAction() {
    $this->setLayoutAdm();
    $repositorioORM = new RepositorioORM($this->getDoctrineORMEntityManager());
    $shoppings = $repositorioORM->getShoppingORM()->encontrarTodos();
    return new ViewModel(
      array(
      'shoppings' => $shoppings,
    )
    );
  }

}
