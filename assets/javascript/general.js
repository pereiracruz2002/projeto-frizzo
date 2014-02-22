var static_url = "/administrator/assets/";

function habilitar_tiny(){
  $('textarea.tinymce').tinymce({
    script_url : base_url+'assets/tinymce/tinymce.min.js',
    language : "pt_BR",
    height: 300,
    plugins: [
      "advlist autolink lists link image charmap print preview anchor",
      "searchreplace visualblocks code fullscreen",
      "insertdatetime media table contextmenu paste "
    ],
    toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image"
  })
}

function add_masks(){
  $('.cep').mask('99999-999',{
    onComplete: function(cep, e){
      var fieldset = $(e.target).parents('fieldset')

      $(e.target).prev().append('<img src="'+assets_url+'image/ajax-loader-mini.gif" class="loading pull-right" />')
      $.getJSON(base_url+'cliente/getEndereco/'+cep, function(data){
        $('.loading').remove()
        if(data.erro){
          alert('CEP não encontrado')
        } else {
          fieldset.find('[name^=logradouro]').val(data.logradouro)
          fieldset.find('[name^=cidade]').val(data.localidade)
          fieldset.find('[name^=estado]').val(data.uf)
          fieldset.find('[name^=bairro]').val(data.bairro)
        }
      })
    }
  })

}


function add_popover(){
  $('.remover_field').popover({
    trigger: 'manual',
    html: true,
    placement: 'left',
    title: 'Remover esse campo?',
    content: '<a class="btn btn-danger cancelar" href="#">Cancelar</a> <a class="btn btn-primary confirmar_remover_campo" href="#">Remover</a>' 
  })
  
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

  $('.remover_imagem').popover({
    trigger: 'manual',
    html: true,
    placement: 'bottom',
    title: 'Remover essa imagem?',
    content: '<a class="btn  cancelar" href="#">Cancelar</a> <a class="btn btn-danger confirmar_remover_imagem" href="#">Deletar</a>' 
  }).on('click', function (e) {
    e.preventDefault()
    $(this).popover('toggle')
  })

  $('.cupom_duplicar').popover({
    trigger: 'manual',
    html: true,
    placement: 'left',
    title: 'Clonar registro',
    content: '<form class="form-horizontal clonar" action="administrator/cupom/duplicar/"><input type="text" name="quantidade" class="input-small" placeholder="Quantidade"><button type="submit" class="btn">Clonar</button></form>' 
  }).on('click', function (e) {
    e.preventDefault()
    $(this).popover('toggle')
  })


}

function set_datepicker(){
  $("input.date").attr('readonly', 'readonly').datepicker()
  $('.date_min').datepicker({"minDate" : new Date()})

  $('.hora').change('blur', function(){
    var value = $(this).val()
    hora = parseInt(value.substr(0,2))
    min = parseInt(value.substr(3,2))
    if(hora > 24 || min > 59)
      $('.hora').val('')
  }).mask('00:00')
}
$().ready(function(){
  
  habilitar_tiny()
  var grupoSelecionado = $('select[name*=usuario_grupo_id]').val();
  if(grupoSelecionado == ""){
      if($("#grupoPermissao").length > 0){
          $("#grupoPermissao").hide();
      }
  }
  
  $.datepicker.setDefaults($.datepicker.regional["pt-BR"])
  set_datepicker()
  add_popover()

  $('.tabs-left .nav li a').on('click', function(e){
    e.preventDefault()
    var href = $(this)
    $('.tab-pane').hide()
    if(href.attr('href').substr(0,1) == '#'){
      $(href.attr('href')).show()
    } else {
      $('.tab-content').html('<img src="'+base_url+'assets/image/ajax-loader.gif" class="loading img-centered" />')
      $.get(href.attr('href'), function(data){
        $('.tab-content').html(data)
        add_popover()
        habilitar_tiny()
      })
    }
    $('.tabs-left .active').removeClass('active')
    $(this).parent().addClass('active')
  })

  $('body').on('click', '.duplicarGroup', function(e){
    e.preventDefault()
    var div = $(this).parents('fieldset').find('.control-group:first').clone()
    div.find('input').val('')
    $('.control-group:last').before(div)
  })

  $('body').on('click', '.removeGroup', function(e){
    e.preventDefault()
    var elm = $(this)
    if(elm.attr('href'))
      $.get(elm.attr('href'))

    if($('.campo').length > 1)
      elm.parents('.control-group').fadeOut(function(){$(this).remove();});
    else
      elm.parents('.control-group').find('input').val('')

  })

  $('body').on('click', '.duplicarFieldset', function(e){
    e.preventDefault()
    var fieldset = $(this).parent().find('fieldset:first').clone()
    fieldset.find('input').val('')
    fieldset.find('legend span').text('Novo Endereço')
    $(this).before(fieldset)
    add_masks()
  })

  $('body').on('click', '.removeFieldset', function(e){
    e.preventDefault()
    if($('fieldset').length > 1)
      $(this).parents('fieldset').fadeOut(function(){$(this).remove();});
    else
      $(this).parents('fieldset').find('input').val('')
  })

  $('body').on('click', '.cancelar', function(e){
    e.preventDefault()
    $('.remover, .remover_field, .remover_imagem').popover('hide')
    $(this).parents('.popover').fadeOut(function(){$(this).remove();});
  })

  $('body').on('click', '.confirmar_remover', function(e){
    e.preventDefault()
    var tr = $(this).parents('tr:first')
    var href = $(this).parents('td:first').find('.remover').attr('href')
    $.get(href, function(data){
      if(data == 'ok')
        tr.fadeOut(function(){$(this).remove();})
      else
        alert(data)
    })
  })

  $('body').on('click', '.modal-ajax', function(e) {
    e.preventDefault()
    var href = $(this).attr('href')
    $('.modal').remove()
    $('body').append('<div class="modal fade "><img src="'+base_url+'assets/image/ajax-loader.gif" class="img-centered" /></div>')
    $('.modal').modal()
    $.get(href, function(data) {
      $('.modal').html(data)
      add_popover()
    })
  })

  $('body').on('submit', '.modal-busca', function(e){
    e.preventDefault()
    var form = $(this)
    var dados = form.serialize()
    $('#content-busca').html('<img src="'+base_url+'assets/image/ajax-loader.gif" class="img-centered" />')
    form.find('select, textarea, input').attr('disabled','disabled')
    $.post(form.attr('action'), dados, function(data){
      $('#content-busca').html(data)
      form.find('select, textarea, input').removeAttr('disabled')
    })
  })

  if($('[name=uf]').get(0) && $('[name=cidade]').get(0)){
    new dgCidadesEstados({
      estado: $('[name=uf]').get(0),
      cidade: $('[name=cidade]').get(0),
      change: true
    })
  }

  $('body').on('submit', '.ajaxForm', function(e){
    e.preventDefault()
    var form = $(this)
    var dados = form.serialize()
    form.find('.botao-ajax').hide().before('<img src="'+static_url+'image/ajax-loader.gif" class="loading" />')
    $('.alert').remove()
    form.find('input, textarea, select').attr('disabled', 'disabled')
    $.post(form.attr('action'), dados, function(data){
      form.find('.botao-ajax').show()
      form.find('legend:first').before(data.msg)
      form.find('input, textarea, select').removeAttr('disabled')
      $('.loading').remove()
      $.scrollTo(0, 800)
    }, 'json')
  })

  $('body').on("submit",".clonar", function(e){
      e.preventDefault();
      var form = $(this)
      var href = $(this).parents('td:first').find('a').attr('href')
      var qtd  = form.find('[name=quantidade]').val();
      if(qtd > 0){
        $.ajax({
          url: href,
          async: true,
          dataType: 'json',
          type: 'post',
          data: {'id':href, 'qtd':qtd},
          success: function(data) {
              if (data.msg == "ok") {
                  window.location.reload();
              }
          },
          error: function() {
             alert('erro')
          }
        });

      }else{
        alert("Valor deve ser maior que zero")
      }
      
  })
  $('select[name*=usuario_grupo_id]').on("change", function(e){
      e.preventDefault();
      var id_selecionado = $(this).val();             
      if(id_selecionado > 0){
        $.ajax({
          url: '/administrator/usuario/buscarPermissaoGrupo',
          async: true,
          dataType: 'json',
          type: 'post',
          data: {'usuario_grupo_id':id_selecionado},
          success: function(data) {
               $("#grupoPermissao").show();
             $("input[id*=permissao]").prop('checked', false);
             for(var i in data){
                 var c=data[i].crud.search("c"); 
                 var r=data[i].crud.search("r"); 
                 var u=data[i].crud.search("u"); 
                 var d=data[i].crud.search("d"); 
                 
                 if(c>=0){
                    $("input[name=crud_c_"+data[i].permissao_id+"]").prop('checked', true);
                 }
                 if(r>=0){
                    $("input[name=crud_r_"+data[i].permissao_id+"]").prop('checked', true);
                 }
                 if(u>=0){
                    $("input[name=crud_u_"+data[i].permissao_id+"]").prop('checked', true);
                 }
                 if(d>=0){
                    $("input[name=crud_d_"+data[i].permissao_id+"]").prop('checked', true);
                 }
             }
          }         
        });
      }else{
           $("#grupoPermissao").hide();
      }
      
  })

  /*
  $(".cupom_duplicar").on('click', function(e) {
    e.preventDefault();
    var href = $(this).attr('href')
    $.ajax({
        url: '/administrator/cupom/duplicar/',
        async: true,
        dataType: 'json',
        type: 'post',
        data: {'id':href},
        success: function(data) {
            if (data.msg == "ok") {
                window.location.reload();
            }
        },
        error: function() {
           alert('erro')
        }
    });
  });
*/

    $('a[id*=permissao]').on('click',function(e){
        e.preventDefault();        
        var id = $(this).attr('id');
       
        if($("input[id*="+id+"]").is(':checked')==false){
            $("input[id*="+id+"]").prop('checked', true);
        }else{
            $("input[id*="+id+"]").prop('checked', false);
        }


      
    });

    $('body').on('submit','.filtroAjax', function(e){
      e.preventDefault()
      var form = $(this)
      $('.table').hide().before('<img src="'+base_url+'assets/image/ajax-loader.gif" class="img-centered loading" />')
      $.post(form.attr('action'), form.serialize(), function(data){
        $('.tab-content').html(data)

      })
    })

    $('body').mouseup(function(e){
      var container = $('.boxSuggest')
      if(!container.is(e.target) && container.has(e.target).length === 0)
        $('.typehead, .boxSuggest .alert').slideUp('fast', function(){$(this).remove();})
    })

})
