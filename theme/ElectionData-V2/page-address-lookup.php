<?php
/**
* Address Lookup Page
*
* Authors: Simranjeet Singh Hunjan
* @package Election_Data_Theme
* @since Election_Data_Theme 1.1
*/
  get_header();
?>
  <div class = "address_lookup_page">
    <div class="search_text">
      <span class="enter_address_text">Enter Your Address To Reveal Your Candidates</span><br />
      <span class="random_order_text">All candidates are displayed in random order.</span><br />
    </div>
    <form id="address_lookup_form" method="POST" action="">
      <input type="text" name="street_number"  id="street_number" placeholder='Street Number'>
      <input type="text" name="street_name"  id="street_name" placeholder="Street Name">
      <input type="hidden" name="page" id="page" value="Address_Lookup" >
      <input type="submit" name="submit" id="submit" value="Find">
      <!-- <input type="button" name = "delete" id = "delete" value = "Delete" > -->
    </form>

    <div class="search_candidates_text">
      <hr>
        <h2>Please enter your address to search for candidates within your area.</h2>
      <hr>
      <br>
    </div>

    <div class="loading">
      <img class="gif" src="/wp-content/themes/ElectionData/ElectionData-V2/images/loading.gif" />
    </div>

    <div id ="candidates" class = "animated fadeIn">

    </div>
  </div>
<?php
  get_footer();
?>
