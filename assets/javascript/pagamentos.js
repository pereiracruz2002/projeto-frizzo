$().ready(function(){
  $('body').on('click', '.duplicarLabel', function(e){
    e.preventDefault()
    var original = $(this).parents('.duplicavel')
    nova = original.clone()
	nova.find("input, select, textarea, hidden").val("")
	original.after(nova)
  })

  $('body').on('click', '.removerParametro', function(e){
    e.preventDefault()
    var forma_pagamento_id = $('body').find('.formParametro #forma_pagamento_id').val()
    var nome = $(this).parent('.input-append').parent('.control-group').find('#chave').val()

    $.post($('body').find('.formParametro').attr('action').replace('saveConfigs', 'removerParametro'), { 'forma_pagamento_id' : forma_pagamento_id, 'nome' : nome }, function(data){}, 'json')

  	$(this).parent('.input-append').parent('.control-group').remove()
  })

  $('body').on('click', '.removerStatus', function(e){
    e.preventDefault()
    var forma_pagamento_id = $('body').find('.formStatus #forma_pagamento_id').val()
    var status_pedido_id = $(this).parent('.input-append').parent('.control-group').find('#status_pedido_id').val()

    $.post($('body').find('.formParametro').attr('action').replace('saveConfigs', 'removerStatus'), { 'forma_pagamento_id' : forma_pagamento_id, 'status_pedido_id' : status_pedido_id }, function(data){}, 'json')

  	$(this).parent('.input-append').parent('.control-group').remove()
  })


})
