function checkSlug(text){
  $.post(base_url+"produtos/criaSlug", { 
    'slug' : text,
    'produto_id' : $('#produto_id').val()
  }, function(data){
    $('[name="slug"]').val(data)
  });
}
$().ready(function(){
  $('body').on('keypress','input.mask_decimal', function(){
    mascara($(this).get(0), decimal)
  })
  
  $('body').on('blur', 'input[name=preco_de]', function(){
    var por = $('[name=preco_por]').val()
    if(por == ''){
      var preco = $(this).val()
      $('[name=preco_por]').val(preco)
    }
  })

  $("body").on('blur', '.dados-gerais input[name="nome"], [name="slug"]', function(e){
    checkSlug($(this).val())
  })
  

  
  $('body').on('change', '.file_ajax', function(){
    $(this).hide()
    $(this).after('<img src="'+base_url+'assets/image/ajax-loader.gif" class="loading img-centered" />')
    $('#form_upload').submit()
  })

  $('body').on('click', '.confirmar_remover_imagem', function(e){
    e.preventDefault()
    var a = $(this).parents('.caption').find('.remover_imagem') 
    a.parents('li:first').fadeOut()
    $.get(a.attr('href'))
  })

  $('body').on('click', '.imagem_principal', function(e){
    e.preventDefault()
    var a = $(this)
    a.parents('.thumbnails').find('.disabled').removeClass('disabled')
    a.addClass('disabled')
    $.get(a.attr('href'))
  })

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

  $('body').on('click', '.relacionar_produto', function(e){
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

  $('body').on('change', '.filtro_atributo', function(e){
    $('.tr_sub_produto').each(function(){
      var this_tr = $(this)
      var visivel = false
      var passou = false

      $('.filtro_atributo').each(function(){
        var valor = $(this).val()
        var atributo = $(this).attr('data-atributo')

        if(valor != ''){
          if(this_tr.attr('data-atributo-' + atributo) == valor){
            if(!passou){
              visivel = true
              passou = true
            }
          }
          else{
            visivel = false
            passou = true
          }
        }
      })

      if(visivel){
        this_tr.show()
      }
      else{
        this_tr.hide() 
      }
    })
  })

  $('body').on('click', '.toggleImagemSubProduto', function(e){
    e.preventDefault()
    var a = $(this)
    a.toggleClass('disabled')
    $.get(a.attr('href'))
  })

  $('body').on('click', '.relacionar_sub', function(e){
    e.preventDefault()
    var a = $(this)
    a.parents('tr:first').slideUp()
    $.get(a.attr('href'), function(data){
      $('.erro').remove()
      $('.sub_produtos_relacionados tbody').append(data)
      $('.sub_produtos_relacionados tbody tr:last').slideDown()
    })
  })
})
