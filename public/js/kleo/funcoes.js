

function kleo(action, id) {  
  $(".splash").css("display", "block");
  $.post(
    '/cadastroKleo',
    {
      action: action,
      id: id                  
    },
    function (data) {
      if (data.response) {
        location.href = data.url;                
      }
    }, 'json');    
}

function mudarPaginaComLoader(url){
  $(".splash").css("display", "block");
  location.href = url;
}

function submeterFormulario(form){
  $(".splash").css("display", "block");
  form.submit();
}

$(window).bind("load", function () {
    // Remove splash screen after load
    $('.splash').css('display', 'none')
});