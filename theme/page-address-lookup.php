<?php
/**
* Address Lookup Page
*
* Authors: Simranjeet Singh Hunjan
* @package Election_Data_Theme
* @since Election_Data_Theme 1.1
*/
  get_header();
  $street_types = explode(', ', Election_Data_Option::get_option('street_types'));
?>
  <div class = "address_lookup_page">
    <div class="search_text">
      <span class="enter_address_text">Enter Your Address To Reveal Your Candidates</span><br>
      <span class="random_order_text">All candidates are displayed in random order.</span><br>
    </div>
    <form id="address_lookup_form" method="POST" action="">
      <input type="text" name="street_number"  id="street_number" placeholder='#'>
      <input type="text" name="street_name"  id="street_name" placeholder="Name">
      <select>
        <option value = "All" >(All)</option>
        <?php
          foreach($street_types as $street){
            echo "<option value='" . $street . "'>". $street ."</option>";
          }
        ?>
      </select>
      <input type="text" name="street_direction"  id="street_direction" placeholder="Direction">
      <input type="submit" name="submit" id="submit" value="Find">
      <!-- <input type="button" name = "delete" id = "delete" value = "Delete" > -->
    </form>
    <div class ="candidates">
      <hr>
        <h2>Please enter your address to search for candidates within your area.</h2>
      <hr>
      <br>
    </div>
  </div>
<?php
  get_footer();
?>
