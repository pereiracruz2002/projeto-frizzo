$(document).ready(function(){
  $("body").on('click','.removerAtributo', function(e){
    e.preventDefault()
    if($(this).parents(".novos_atributos").find(".control-group").length > 1)
      $(this).parent().remove()
  })
  $("body").on('click', '.adicionaAtributo', function(e){
    e.preventDefault()
    div = $(this).parents(".sub_produto").find(".novos_atributos .control-group:first").clone()
    $(this).parents(".sub_produto").find(".novos_atributos").append(div)
  })

  $('.start_upload').on('click', function(e){ 
    e.preventDefault()
    $('#image_ajax').show()
    $('.start_upload').hide()
    $('#fileupload').submit()
  })
});