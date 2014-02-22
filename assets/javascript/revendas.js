$().ready(function(){
  $('body').on('submit', '.open-modal-form', function(e){
    e.preventDefault()
    var form = $(this)
    var dados = form.serialize()
    $('.modal').remove()
    $('body').append('<div class="modal hide fade"><img src="'+static_url+'image/ajax-loader.gif" class="img-centered" /></div>')
    $('.modal').modal()
    $.post(form.attr('action'), dados, function(data){
      $('.modal').html(data)
    })
  })

  $('body').on('click', '.relacionarProduto', function(e){
    e.preventDefault()
    var a = $(this)
    a.after('<img src="'+base_url+'assets/image/ajax-loader-mini.gif" class="loading img-centered" />')
    a.hide()
    $.get(a.attr('href'), function(data){
      $('.tab-content table tbody').append(data)
      $('.remover:last').popover({
        trigger: 'manual',
        html: true,
        placement: 'left',
        title: 'Remover esse registro?',
        content: '<a class="btn btn-danger cancelar" href="#">Cancelar</a> <a class="btn btn-primary confirmar_remover" href="#">Deletar</a>' 
      }).on('click', function (e) {
        e.preventDefault()
        $(this).popover('toggle')
      })

      $('.modal').modal('hide')
    })
  })
})
