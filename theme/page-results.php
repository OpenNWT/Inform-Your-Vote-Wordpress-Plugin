<?php

global $is_party_election;
global $ed_post_types;

//dunno if I need these guess I'll find out soon!
//$constituency = get_constituency( $party_id = get_queried_object()->term_id );
//$constituency_id = $constituency['id'];

$street_types = explode(', ', Election_Data_Option::get_option('street_types'));
?>
<a name="top"></a>
<?php get_header(); ?>

  <div class = "address_lookup_page">
    <div class="search_text">
      <span class="enter_address_text">Enter Your Address To Reveal The Results Of Your Area.</span><br />
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
      <input type="hidden" name="page" id="page" value="<?=wp_title('',true);?>" >
      <input type="submit" name="submit" id="submit" value="Find">
      <!-- <input type="button" name = "delete" id = "delete" value = "Delete" > -->
    </form>

    <div class ="candidates">
      <br />

    </div>
  </div>


<?php get_footer(); ?>
