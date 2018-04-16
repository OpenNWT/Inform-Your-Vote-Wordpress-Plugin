jQuery(document).ready( function($) {

  $('#address_lookup_form').submit(function(){
    $('#candidates').css('display', 'none');
    $('.search_candidates_text').css('display', 'none');
    
    $('.loading').css('display', 'block');
    var form_data = $(this).serializeArray();

    $.ajax({
      url: ajaxurl,
      type: "POST",
      data: {form_data: form_data, action : 'address_lookup'},
      success: function(data){
        $('#candidates').css('display', 'block');
        $('.loading').css('display', 'none');
        $('#candidates').html(data);
      }
    });

    return false;
  });


  // $('#delete').click(function(){
  //   alert("Yes");
  //   $.ajax({
  //     url: ajaxurl,
  //     type: "POST",
  //     data: {action : 'delete'},
  //     success: function(data){
  //       $('#ajax_result').html(data);
  //     }
  //   });
  //
  //   return false;
  //   });

});
