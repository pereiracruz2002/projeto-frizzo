var width_lista = 0;
$(document).ready(function(){

  $('#produtosSelecionados ul li').each(function(){
    width_lista += ($(this).width() + 65);
  });
  $('#produtosSelecionados ul').width(width_lista);

  $('body').on('click', '.addProduto', function(e){
    e.preventDefault()
    var elm = $(this)
    elm.before('<img src="'+assets_url+'image/ajax-loader-mini.gif" class="loading pull-right" />')
    elm.hide()
    $.getJSON(elm.attr('href'), function(data){
      if(data.msg == 'ok'){
        $('.loading').remove()
        var li = elm.parent();
        elm.removeClass('addProduto')
        elm.addClass('removeProduto')
        elm.show()
        width_lista += li.width() + 65
        $('#produtosSelecionados ul').width(width_lista)
        li.prependTo('#produtosSelecionados ul')
        elm.attr('href', elm.attr('href').replace('addProduto', 'removeProduto'))
        elm.find('i').removeClass('icon-ok').addClass('icon-remove')
        li.fadeIn('slow')
      }else{
        alert(data.msg)
      }
    })
  });


  $('body').on('click', '.removeProduto', function(e){
    e.preventDefault()
    var elm = $(this)
    elm.before('<img src="'+assets_url+'image/ajax-loader-mini.gif" class="loading pull-right" />')
    elm.hide()
    $.getJSON(elm.attr('href'), function(data){
      if(data.msg == 'ok'){
        var li = elm.parent();
        li.fadeOut('slow')
      }else{
        alert(data.msg)
      }
    })
  });

});
