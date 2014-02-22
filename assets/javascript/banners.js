function add_popover(){
  $('.remover').popover({
      trigger: 'manual',
      html: true,
      placement: 'left',
      title: 'Remover esse registro?',
      content: '<a class="btn btn-danger cancelar" href="#">Cancelar</a> <a class="btn btn-primary confirmar_remover" href="#">Deletar</a>' 
    }).on('click', function (e) {
      e.preventDefault()
      $(this).popover('toggle')
    })
}

$().ready(function(){
  add_popover()
})
