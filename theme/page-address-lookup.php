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

  <form id="address_lookup_form" method="POST" action="">
    <input type="text" name="street_number"  id="street_number" placeholder='Street Number'>
    <input type="text" name="street_name"  id="street_name" placeholder="Street Name">
    <select>
      <option value = "All" >(All)</option>
      <?php
        foreach($street_types as $street){
          echo "<option value='" . $street . "'>". $street ."</option>";
        }
      ?>
    </select>
    <input type="text" name="street_direction"  id="street_direction" placeholder="Street Direction">
    <input type="submit" name="submit" id="submit" value="Find">
    <input type="button" name = "delete" id = "delete" value = "Delete" disabled>
  </form>


  <div id="ajax_result">

  </div>

<?php
  get_footer();
?>
