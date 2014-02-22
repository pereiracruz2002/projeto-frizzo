$(document).ready(function(){
  $("body").on('click','.removerItem', function(e){
    e.preventDefault()

    if($(this).parents(".controls").find("#novo").val() == 'false'){
      return;
    }

    if($(this).parents(".novos_itens").find(".control-group").length > 1){
      $(this).parent().parent().remove()
    }
  })

  $("body").on('click', '.adicionaItem', function(e){
    e.preventDefault()
    quantidade = $(this).parents(".row-fluid").find(".control-group .controls #quantidade_linhas").val()

    for (var i = 0; i < quantidade; i++) {
      div = $(this).parents(".row-fluid").find(".novos_itens .control-group:first").clone()
      div.find("input, select, hidden").val("")
      div.find("#quantidade, .suggest").removeAttr('readonly')
      div.find("#estoque_id").val('')
      div.find("#novo").val('true')
      $(this).parents(".row-fluid").find(".novos_itens").append(div)
    }
  })

  $("body").on('keyup', '.calculaValor', function(e){
    quantidade = $(this).parents(".controls").find('#quantidade').val()
    preco = $(this).parents(".controls").find('#preco_compra_unitario').val()
    preco = preco.replace('.', '')
    preco = preco.replace(',', '.')
    if((quantidade != '') && (preco != '')){
      $(this).parents(".controls").find('#preco_compra_total').val((quantidade * preco).toFixed(2).replace('.', ','))
    }
  })

  $('body').on('keyup', '.suggest', function(e){
    var elm = $(this)
    var act = elm.attr('data-url')
    
    elm.parent().append('<img src="'+assets_url+'image/ajax-loader-mini.gif" class="loading" />')
    $.post(act, {'produto': elm.val()}, function(data){
      $('.typehead, .boxSuggest .alert, .loading').remove()
      elm.parent().append(data)
      $('.typehead').slideDown('fast')
    })
  })

  $('body').on('click', '.typehead a', function(e){
    e.preventDefault()
    var sub_produto_id = $(this).attr('href')
    var text = $(this).text()
    $(this).parents('.boxSuggest').find('.suggest_value').val(sub_produto_id)
    $(this).parents('.boxSuggest').find('.suggest').val(text.trim())
    $('.typehead').slideUp('fast', function(){$(this).remove();})
  })

  $('body').on('focus', '.suggest', function(e){
    var text = $(this).val()
    if(text)
      $(this).attr('data-cache', text)
  })

});
