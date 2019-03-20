<?php

/*
Plugin Name: JSPlug
Plugin UI:
Description: Simple plugin
Author: Julian Schreiner
Author URI:
Version: 0.1
*/
error_reporting(E_ALL);
add_action("admin_menu", "addMenu");
add_shortcode('shtest', 'test');

function test(){
	return "WITH SHORTCODE CREATED";
}


function addMenu(){
	add_menu_page("Example Options", "JS Plug Options", "4", "ex-options", "exampleMenu");
	
}

function exampleMenu(){
	$t = <<< TPL
         <div class="wrap">
         	<h1>Product Custom Prices</h1>
         	<form method="POST" action="">
         		<input type="submit" name="create_shortcode" value="Show All Products" class="button-primary">
         	</form>
         </div> 	 
TPL;
    echo $t;



    if(isset($_POST['create_shortcode'])){
    	handlePost();
    }

}

function handlePost(){
	global $wpdb;
	
	$mydrafts = $wpdb->get_results(
		"
			SELECT p.ID,
p.post_title 'Name',
p.post_content 'Description',
IF (meta.meta_key = '_sku', meta.meta_value, null) 'SKU',
IF (meta.meta_key = '_price', meta.meta_value, null) 'Price',
IF (meta.meta_key = '_weight', meta_value, null) 'Weight'
FROM wp_posts AS p
LEFT JOIN wp_postmeta AS meta ON p.ID = meta.post_ID
WHERE (p.post_type = 'product' OR p.post_type = 'product_variation')
AND meta.meta_key IN ('_sku', '_price', '_weight')
GROUP BY p.ID
		"
	);

	echo "<br><br><br>";
	echo '<table class="wp-list-table widefat fixed striped posts">';
		echo '<thead>';
		echo '<tr>';
		echo '<th scope="col" id="name" class="manage-column column-name column-primary sortable desc">
				<span>Name</span>
			  </th>
			<th scope="col" id="sku" class="manage-column column-sku sortable desc">
			<span>Artikelnummer</span>
			</th>
			<th scope="col" id="price" class="manage-column column-price sortable desc">
			<span>Preis</span>
			</th>
			<th scope="col" id="description" class="manage-column column-description sortable desc">
			<span>Description</span>
			</th>
			<th scope="col" id="btn">
			<span></span>
			</th>
			</tr>';
		echo '</thead>';
		echo '<tbody id="the-list">';

	foreach ($mydrafts as $draft){
		echo '<tr id="post-'.$draft->ID.'" class="iedit author-self level-0 post-136 type-product status-publish has-post-thumbnail hentry product_cat-allgemein">';
		echo '<td class="name column-name has-row-actions column-primary">' . $draft->Name . '</td>';
		echo '<td class="name column-name has-row-actions column-primary">' . $draft->SKU . '</td>';
		echo '<td class="name column-name has-row-actions column-primary">' . $draft->Price . '</td>';
		echo '<td class="name column-name has-row-actions column-primary">'. $draft->Description . '</td>';
		echo '<td class="name column-name has-row-actions column-primary">
				<input type="submit" class="button-primary" 
				value="Edit" name="'.$draft->ID.'"
				></button></td>';
		echo '</tr>';
	}	
	echo '</tbody>';
	echo '</table>';
}

// HERE below in the array set your specific product IDs
function specific_product_ids(){
    return array(136); //  <===  <===  <===  <===  Your Product IDs
}


// Simple, grouped and external products
add_filter('woocommerce_product_get_price', 'custom_price', 10, 2);
add_filter('woocommerce_product_get_regular_price', 'custom_price' , 10, 2);


## This goes outside the constructor ##

// Utility function to change the prices with a multiplier (number)
function get_price_multiplier() {
    return 2; // x2 for testing
}

function custom_price( $price, $product ) {
	if( in_array($product->get_id(), specific_product_ids() ) ) {
    	return $price * get_price_multiplier();
	} else{
		return $price;
	}
}

?> 



