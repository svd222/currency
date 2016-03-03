
$('#currency-rates-form #mode-wrapper input[type=checkbox]').click(function() {
   id = $(this).get(0).id;// currencyratesform-mode-url | currencyratesform-mode-local
   var sourceElmUrl = $('#source-wrapper input[type=text]')[0];
   var sourceElmLocal = $('#source-wrapper input[type=file]')[0];;
   
   if(id == 'currencyratesform-mode-local') {
       $('#currencyratesform-mode-url').attr('checked',false);       
       sourceElmUrl.style.display = 'none';
       sourceElmLocal.style.display = 'block';
   } else {
       $('#currencyratesform-mode-local').attr('checked',false);
       sourceElmLocal.style.display = 'none';
       sourceElmUrl.style.display = 'block';
   }
});
