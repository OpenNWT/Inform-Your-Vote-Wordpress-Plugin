jQuery(document).ready( function($) {
  $('#column-1').click(function(){

    var theme = "ElectionData/ElectionData-V1";
    $.ajax({
      url: ajaxurl,
      type: "POST",
      data: {theme: theme, action: 'setup_theme_for_user'},
      success: function(data){
        alert(data);
        location.reload();
      }
    });
  });

  $('#column-2').click(function(){

    var theme = "ElectionData/ElectionData-V2";
    $.ajax({
      url: ajaxurl,
      type: "POST",
      data: {theme: theme, action: 'setup_theme_for_user'},
      success: function(data){
        alert(data);
        location.reload();
      }
    });
  });
});
