<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Doctrine\ORM\EntityManager;
use Zend\Authentication\AuthenticationService;
use Zend\View\Model\ViewModel;
use Application\Model\Entity\Responsavel;
use Application\Model\Entity\ResponsavelSituacao;
use Application\Model\Entity\Situacao;
use Application\Model\ORM\RepositorioORM;
use Application\Form\CadastroResponsavelForm;
use Application\Form\ResponsavelAtualizacaoForm;
use Application\Form\ResponsavelSenhaAtualizacaoForm;
use Application\Form\LoginForm;
use Application\Form\KleoForm;

class PubController extends KleoController{

   private $_doctrineAuthenticationService;
  /**
     * Contrutor sobrecarregado com os serviços de ORM
     */
  public function __construct(EntityManager $doctrineORMEntityManager = null,  
                              AuthenticationService $doctrineAuthenticationService = null) {

    if (!is_null($doctrineORMEntityManager)) {
      parent::__construct($doctrineORMEntityManager);
    }
    if (!is_null($doctrineAuthenticationService)) {
      $this->_doctrineAuthenticationService = $doctrineAuthenticationService;
    }
  }

  public function loginAction(){
    $this->setLayoutAdm();
    $formulario = $this->params()->fromRoute(self::stringFormulario);
    if($formulario){
      $loginForm = $formulario;
    }else{
      $loginForm = new LoginForm('login');
    }

    return new ViewModel(
      array(
      self::stringFormulario => $loginForm,
    )
    );
  }

  public function logarAction(){
    $this->setLayoutAdm();
    $data = $this->getRequest()->getPost();

    $usuarioTrim = trim($data[KleoForm::inputEmail]);
    $senhaTrim = trim($data[KleoForm::inputSenha]);
    $adapter = $this->getDoctrineAuthenticationServicer()->getAdapter();
    $adapter->setIdentityValue($usuarioTrim);
    $adapter->setCredentialValue(md5($senhaTrim));
    $authenticationResult = $this->getDoctrineAuthenticationServicer()->authenticate();
    
    if ($authenticationResult->isValid()) {
      /* Autenticacao valida */
      /* Helper Controller */
      $repositorioORM = new RepositorioORM($this->getDoctrineORMEntityManager());
      /* Verificar se existe responsavel por email informado */
      $responsavel = $repositorioORM->getResponsavelORM()->encontrarPorEmail($usuarioTrim);
      /* Se o responsavel esta ativo */
      $reponsavelSituacaoAtivo = $responsavel->getResponsavelSituacaoAtivo();

      if ($reponsavelSituacaoAtivo->getSituacao()->getId() === Situacao::ativo) {
        /* Registro de sessão */
        $sessao = $this->getSessao();
        $sessao->idResponsavel = $responsavel->getId();

        return $this->redirect()->toRoute(KleoController::rotaAdm);
      } else {
        return $this->forward()->dispatch(KleoController::controllerPub, array(
          KleoController::stringAction => 'login',
        ));
      }
    } else {
      echo 'autenticacao invalida';
//       return $this->forward()->dispatch(KleoController::controllerPub, array(
//         KleoController::stringAction => 'login',
//       ));
    }

  }

  /**
     * Função padrão, traz a tela principal
     * GET /pubResponsavel
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
     * GET /pubResponsavelFinalizar
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
          $urlResponsaveis = self::url . 'admResponsaveis';

          $titulo = 'Primeiro Contato';
          $mensagem = '<p>NomeFantasia '. $responsavel->getNomeFantasia(). '</p>';
          $mensagem .= '<p>Resposavel '. $responsavel->getNome(). '</p>';
          $mensagem .= '<p>Telefone <a href="tel:'.$responsavel->getTelefone().'">'.$responsavel->getTelefone().'</a></p>';
          $mensagem .= '<p>Email '. $responsavel->getEmail(). '</p>';
          $mensagem .= '<p><a href="'.$urlResponsaveis.'">Visualizar</a></p>';

          self::enviarEmail($emails, $titulo, $mensagem);

          return $this->redirect()->toRoute(self::rotaPub, array(
            self::stringAction => 'responsavelFinalizado',
          ));
        } else {
          return $this->forward()->dispatch(self::controllerPub, array(
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
    return new ViewModel();
  } 
  /**
     * Função padrão, traz a tela principal
     * GET /cadastroResponsavelAlterado
     */
  public function responsavelAlteradoAction() {
    return new ViewModel();
  }

  /**
     * Função padrão, traz a tela principal
     * GET /cadastroResponsavelAlterado
     */
  public function responsavelSenhaCadastradoAction() {
    return new ViewModel();
  }

  /**
     * Formulario para alterar dados do responsavel
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
     * Formulario para alterar dados do responsavel
     * GET /cadastroResponsavelSenhaAtualizacao
     */
  public function responsavelSenhaAtualizacaoAction() {

    $repositorioORM = new RepositorioORM($this->getDoctrineORMEntityManager());
    $formulario = $this->params()->fromRoute(self::stringFormulario);
    if($formulario){
      $responsavelSenhaAtualizacaoForm = $formulario;
      $idToken = $formulario->get(KleoForm::inputId);
      $responsavel = $repositorioORM->getResponsavelORM()->encontrarPorToken($token); 
    }else{
      $token = $this->getEvent()->getRouteMatch()->getParam(self::stringToken);
      $responsavel = $repositorioORM->getResponsavelORM()->encontrarPorToken($token); 
      $responsavel->setId($token);
      $responsavelSenhaAtualizacaoForm = new ResponsavelSenhaAtualizacaoForm('ResponsavelSenhaAtualizacao', $responsavel);
    }

    return new ViewModel(
      array(
      self::stringFormulario => $responsavelSenhaAtualizacaoForm,
      KleoForm::inputEmail => $responsavel->getEmail(),
    ));
  }

  /**
     * Atualiza a senha do responsavel
     * GET /cadastroResponsavelSenhaAtualizar
     */
  public function responsavelSenhaAtualizarAction() {
    $request = $this->getRequest();
    if ($request->isPost()) {
      try {
        $post_data = $request->getPost();
        $repositorioORM = new RepositorioORM($this->getDoctrineORMEntityManager());
        $token = $post_data[KleoForm::inputId];
        $responsavel = $repositorioORM->getResponsavelORM()->encontrarPorToken($token); 

        $responsavelSenhaAtualizacaoForm = new ResponsavelSenhaAtualizacaoForm(null, $responsavel);
        $responsavelSenhaAtualizacaoForm->setInputFilter($responsavel->getInputFilterCadastrarSenhaResponsavel());

        $responsavelSenhaAtualizacaoForm->setData($post_data);

        if ($responsavelSenhaAtualizacaoForm->isValid()) {

          $responsavel->setSenha($post_data[KleoForm::inputSenha]);
          $responsavel->setToken(null);

          $repositorioORM = new RepositorioORM($this->getDoctrineORMEntityManager());                  
          $repositorioORM->getResponsavelORM()->persistir($responsavel);

          $emails[] = $responsavel->getEmail();
          $titulo = self::emailTitulo;
          $mensagem = '';
          $mensagem = '<p>Senha Cadastra com Sucesso</p>';
          $mensagem .= '<p>Usuario: '.$responsavel->getEmail().'</p>';
          $mensagem .= '<p>Senha: '.$responsavel->getSenha().'</p>';
          $mensagem .= '<p><a href="'.self::url.'pubLogin">Clique aqui acessar</a></p>';
          self::enviarEmail($emails, $titulo, $mensagem);

          return $this->redirect()->toRoute(self::rotaPub, array(
            self::stringAction => 'responsavelSenhaCadastrado',
          ));
        } else {      
          return $this->forward()->dispatch(self::controllerPub, array(
            self::stringAction => 'responsavelSenhaAtualizacao',
            self::stringFormulario => $responsavelSenhaAtualizacaoForm,
          ));
        }
      } catch (Exception $exc) {
        echo $exc->getMessage();
      }
    }
    return new ViewModel();
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
        $responsavelAtualizacaoForm->setInputFilter($responsavel->getInputFilterAtualizarResponsavel(false));

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

          return $this->redirect()->toRoute(self::rotaPub, array(
            self::stringAction => 'responsavelAlterado',
          ));
        } else {      
          return $this->forward()->dispatch(self::controllerPub, array(
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
     * Recupera autenticação doctrine
     * @return AuthenticationService
     */
    public function getDoctrineAuthenticationServicer() {
        return $this->_doctrineAuthenticationService;
    }
}
