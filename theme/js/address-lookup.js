jQuery(document).ready( function($) {

  // var street_number = $("#street_number").val();
  // var street_number_suffix = $('#street_number_suffix').val();
  // var street_name = $('#street_name').val();
  // var street_type = $('#street_type').val();
  // var street_direction = $('#street_direction').val();
  //
  // $('#submit').click(function(){
  //   alert("Street Num = " + street_number + " / " + "Street Num Suff = " + street_number_suffix + " / "+
  //         "Street Name = " + street_name + " / " + "Street Type = " + street_type + " / " +
  //         "Street Direction = " + street_direction);
  // });

  $('#address_lookup_form').submit(function(){

    var form_data = $(this).serializeArray();

    $.ajax({
      url: ajaxurl,
      type: "POST",
      data: {form_data: form_data, action : 'address_lookup'},
      success: function(data){
        $('#ajax_result').html(data);
      }
    });

    return false;
  });

  $('#delete').click(function(){
    alert("Yes");
    $.ajax({
      url: ajaxurl,
      type: "POST",
      data: {action : 'delete'},
      success: function(data){
        $('#ajax_result').html(data);
      }
    });

    return false;
    });

});
