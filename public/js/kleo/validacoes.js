

function validacoesFormulario(campo){
  var temErro = false;
  var mensagemDeErro = '';
  switch(campo.id){
    case 'inputNome':
      if(campo.value.length < 3 || campo.value.length > 50){
        temErro = true;
        mensagemDeErro = 'Nome precisa ter 3 a 50 caracteres';
      }
      break;
    case 'inputDDD':
      if(campo.value.length < 2 || campo.value.length > 2){
        temErro = true;
        mensagemDeErro = 'DDD precisa ter 2 caracteres';
      }
      break;
    case 'inputTelefone':
      if(campo.value.length < 8 || campo.value.length > 9){
        temErro = true;
        mensagemDeErro = 'Telefone precisa ter 8 ou 9 caracteres';
      }
      break;
    case 'inputEmail':
      if(!isEmail(campo.value)){
        temErro = true;
        mensagemDeErro = 'Preencha o email corretamente';
      }
      break;
    case 'inputRepetirEmail':
      if(campo.value.length === 0 || campo.value != document.getElementById(inputEmail).value){
        temErro = true;
        mensagemDeErro = 'Repita o email corretamente';
      }
      break;
    case 'inputNomeFantasia':
       if(campo.value.length < 3 || campo.value.length > 50){
        temErro = true;
         mensagemDeErro = 'Nome Fantasia precisa ter 3 a 50 caracteres';
      }
      break;
    case 'inputCNPJ':
       if(campo.value.length != 14){
        temErro = true;
         mensagemDeErro = 'Preencha o CNPJ corretamente';
      }
      break;
    default: break;
  }

  if(temErro){
    escreveMensagemDeErro(campo.id, mensagemDeErro);
  }else{
    limpaAMensagemDeErro(campo.id);
  }

}

function escreveMensagemDeErro(id, mensagem){
  var html = '<p class="text-danger"><small>' +
      mensagem +
      '</small></p>';
  var idDiv = 'mensagemErro' + id;
  document.getElementById(idDiv).innerHTML = html;
}

function limpaAMensagemDeErro(id){
  var html = '';
  var idDiv = 'mensagemErro' + id;
  document.getElementById(idDiv).innerHTML = html;
}