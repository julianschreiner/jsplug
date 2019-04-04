<?php
/*
Plugin Name: Custom Price Changer for WooCommerce
Plugin UI:
Description: Make custom WooCommerce product prices for each user group!
Author: Julian Schreiner
Author URI:
Version: 0.1
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

add_action("admin_menu", "addMenu");

wp_enqueue_script('jquery');
wp_register_script('script', plugin_dir_url(__FILE__) . '/js/script.js', array('jquery'));
wp_enqueue_script('script');

function addMenu()
{
    add_menu_page("Example Options", "Price Changer", "4", "ex-options", "exampleMenu");

}

function exampleMenu()
{
    $t = <<< TPL
         <div class="wrap">
         	<h1>Custom Product Prices</h1>
         </div>
TPL;
    echo $t;

    handlePost();

}

/*
 *    Function to print table with all products
 */

function handlePost()
{
    global $wpdb, $wp_roles;

    $defaultPrice = [];

    foreach ($wp_roles->roles as $role) {
        echo '<input type="hidden" name="' . $role['name'] . '" class="roles"></input>';
        //var_dump($role['name']);
    }

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

    $defaultPrice = [];

    foreach ($mydrafts as $draft) {
        $sku = $wpdb->get_results(
            "
    SELECT meta_value
    FROM wp_postmeta
    WHERE meta_key = '_sku' AND post_id =" . $draft->ID
        );
        $sku = $sku[0];

        $defaultPrice[$sku->meta_value] = $draft->Price;
    }

    if (isset($_POST['editPrices'])) {    
        foreach ($wp_roles->roles as $role) {
            //var_dump($role['name']);

            $rol = $role['name'];

            $rol = str_replace(' ', '_', $rol);

            //var_dump($_POST[$rol . 'Price']);
        }

        $jsonArr = [];

        foreach ($_POST as $key => $value) {
            if (is_null($value)) {
                // ASSIGN DEFAULT PRICE INTO IT
                var_dump($defaultPrice[$_POST['productID']]);
                $jsonArr[$key] = $defaultPrice[$_POST['productID']];
                // BUG: IT GETS DEFAULT PRICE FROM SECOND ROW
            } else {
                var_dump($value);
                $jsonArr[$key] = $value;
            }

        }

        echo json_encode($jsonArr);

        $jsonArr = json_encode($jsonArr);

        $mysql_date_now = date("Y-m-d H:i:s");
        $sku = $_POST['productID'];
        $where = 'price_change_product_' . $sku;

        $alreadyInDB = $wpdb->get_results(
            "SELECT * FROM wp_posts WHERE post_title = '" . $where . "'"
        );

        // TODO CHECK IF ITS IN DB ALREADY
        if (isset($alreadyInDB) && empty($alreadyInDB)) {
            // INSERT INTO DB
            $result = $wpdb->insert('wp_posts', array(
                'post_author' => 1,
                'post_date' => $mysql_date_now,
                'post_date_gmt' => $mysql_date_now,
                'post_content' => $jsonArr,
                'post_title' => $where,
                'post_status' => 'plugin',
                'post_name' => 'price_changer',
            ));
        } else {
            // UPDATE ENTRY
            $result = $wpdb->update('wp_posts', array(
                'post_date' => $mysql_date_now,
                'post_date_gmt' => $mysql_date_now,
                'post_content' => $jsonArr,
            ), array('post_title' => $where));
        }

        // CLEAR TABLE AND REBUILD
        showTable();
    } else {
        showTable();
    }

}
// HERE below in the array set your specific product IDs
function specific_product_ids()
{
    global $wpdb;
    $sku = $wpdb->get_results(
        "
			SELECT meta_value
			FROM wp_postmeta
			WHERE meta_key = '_sku'"
    );

    $retArray = [];

    foreach ($sku as $nr) {
        $retArray[] = $nr->meta_value;
    }

    return $retArray;
}

// Simple, grouped and external products
add_filter('woocommerce_product_get_price', 'custom_price', 10, 2);
add_filter('woocommerce_product_get_regular_price', 'custom_price', 10, 2);

## This goes outside the constructor ##

// Utility function to change the prices with a multiplier (number)
function get_price_multiplier()
{
    return 2; // x2 for testing
}

function custom_price($price, $product)
{
    global $wpdb;

    if (in_array($product->get_sku(), specific_product_ids())) {
        // TODO CHECK USER GROUP AND GIVE SETTED PRICE FOR THIS PRODUCT!!
        $current_user_roles = wp_get_current_user()->roles[0];

        // NOT LOGGED IN
        if (is_null($current_user_roles) || empty($current_user_roles)) {
            $customPrice = $price;
        } else {
            // LOGGED IN
            // User can have more than one role
            $current_user_roles = ucwords($current_user_roles);

            $where = 'price_change_product_' . $product->get_sku();

            $alreadyInDB = $wpdb->get_results(
                "SELECT * FROM wp_posts WHERE post_title = '" . $where . "'"
            );

            $customPrice = 0;

            if (!empty($alreadyInDB[0])) {
                $key = $current_user_roles . "Price";

                // PARSE
                $jsonOBJ = json_decode($alreadyInDB[0]->post_content);

                $customPrice = $jsonOBJ->$key;
            }
        }
    } else {
        // KEINE CUSTOM CONFIG ANGELEGT
        // SHOW NORMAL PRICE
        $customPrice = $price;
    }

    return $customPrice;
}

function showTable()
{
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

    initTableHead();

    $defaultPrice = [];

    foreach ($mydrafts as $draft) {
        $sku = $wpdb->get_results(
            "
		SELECT meta_value
		FROM wp_postmeta
		WHERE meta_key = '_sku' AND post_id =" . $draft->ID
        );
        $sku = $sku[0];

        $where = 'price_change_product_' . $sku->meta_value;

        $priceChangeInformations = $wpdb->get_results(
            "SELECT * FROM wp_posts WHERE post_title = '" . $where . "'"
        );

        $priceChangeInformations = $priceChangeInformations[0];
        var_dump($priceChangeInformations);

        $description = substr($draft->Description, 0, 100);
        $description .= '...';
        $defaultPrice[$sku->meta_value] = $draft->Price;

        initTableBody($sku->meta_value, $draft, $description, $priceChangeInformations->post_content);

        /*echo '<tr>
    <label for="priceAdmin">Price Admin</label>
    <input type="text" id="priceAdmin">
    <br>
    <label for="priceNormalCust">Price Customer</label>
    <input type="text" id="priceNormalCust">
    <br>
    </tr>';*/
    }
    echo '</tbody>';
    echo '</table>';
}

function initTableHead()
{
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
}

function initTableBody($sku, $draft, $description, $priceChangeInformations)
{
    echo '<tr id="post-' . $sku . '" class="iedit author-self level-0 post-136 type-product status-publish has-post-thumbnail hentry product_cat-allgemein" name="tableRows">';
    echo '<td class="name column-name has-row-actions column-primary">' . $draft->Name . '</td>';
    echo '<td class="name column-name has-row-actions column-primary" id="sku">' . $sku->meta_value . '</td>';
    echo '<td class="name column-name has-row-actions column-primary">' . $draft->Price . '</td>';
    echo '<td class="name column-name has-row-actions column-primary">' . $description . '</td>';
    echo '<input type="hidden" name="priceInformation' . $sku . '" value="' . htmlspecialchars($priceChangeInformations) . '"></input>';
    echo '<td class="name column-name has-row-actions column-primary">
				<input type="submit" class="button-primary"
				value="Edit" name="' . $sku . '"
				></button></td></tr>';
}
