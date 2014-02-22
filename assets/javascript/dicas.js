$(document).ready(function(){
  $('.removeImg').on('click', function(e){
    e.preventDefault()
    var elm = $(this)
    elm.parents('.help-block').fadeOut(function(){
      $(this).remove()
    })
    $.get(elm.attr('href'))
  })
})
