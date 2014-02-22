$().ready(function(){
  $('.suggest').typeahead({
    source: function(query, process){
      return $.post(base_url+'cliente/procurar', {'email': query}, function(data){
        return process(data)
      })
    }
  })

})
