jQuery(document).ready( function($) {

  $('#address_lookup_form').submit(function(){

    var street_number = $('#street_number').val();
    var street_name = $('#street_name').val();
    var street_address = street_number + street_name;

    if(street_address){
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
    }
    else{
      alert("Please enter an address");
    }
    return false;
  });
});
