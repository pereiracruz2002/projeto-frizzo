$().ready(function(){
  $('body').on('submit', '.filtro-modal', function(e){
    e.preventDefault()
    var form = $(this)
    var dados = form.serialize()
    var act = form.attr('action')
    form.after('<img src="'+base_url+'assets/image/ajax-loader.gif" class="loading img-centered" />')
    $.post(act, dados, function(data){
      $('.modal-body table, .loading').remove()
      form.after(data)
    })
  })

  $('body').on("click", ".cliente_cupom", function(e) {
      e.preventDefault()
      var id = $(this).attr("href");
      var nome = $(this).attr("title");
      $('#cliente_id').val(id)
      $('#texto_cliente_id').val(nome);
      $(".fade").hide();
  })
})
