$().ready(function(){
  $('.bxslider').bxSlider({
  auto: true,
  controls : false,
  autoControls: false,
  pager:false,
  
  });

   $("input:text:first:visible").focus();
   $( "#senha" ).focusout(function() {
     $("#senha").focus();
     //alert('perdeu o foco');
  });

});

