

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

function carregarFoto(inputFoto){
  $("#message").empty(); // To remove the previous error message
  var file = inputFoto.files[0];
  var imagefile = file.type;
  var match= ["image/jpeg","image/png","image/jpg"];
  if(!((imagefile==match[0]) || (imagefile==match[1]) || (imagefile==match[2]))){
    $("#message").html("<p id='error'>Please Select A valid Image File</p>"+"<h4>Note</h4>"+"<span id='error_message'>Only jpeg, jpg and png Images type allowed</span>");
    return false;
  }else{
    var reader = new FileReader();
    reader.onload = imageIsLoaded;
    reader.readAsDataURL(inputFoto.files[0]);
  }
}

function imageIsLoaded(e) {
  $('#image_preview').css("display", "block");
  $('#previewing').attr('src', e.target.result);
  $('#previewing').attr('width', '256px');
  $('#previewing').attr('height', '256px');
};