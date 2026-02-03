<?php
/**
 * Plugin Name: Inmolink Search Stats
 * Plugin URI: http://inmolink.es
 * Description: Display search stats in search results.
 * Version: 1.0
 * Author: Innotech
 * Author URI: https://innotech.com.es
 */


add_shortcode('inmolink_property_stats',array('InmoLinkSearchStats','properties_stats'));

class InmoLinkSearchStats
{
    public static function properties_stats($atts = array(), $content = NULL)
    {
        $results = new InmoLinkResults();

        $defaults = array(
            'ref_no' => '',
            'bedrooms_min' => '',
            'bedrooms_max' => '',
            'bathrooms_min' => '',
            'bathrooms_max' => '',
            'list_price_min' => '',
            'list_price_max' => '',
            'ownonly' => '',
            'page' => 1,
            'type' => '',
            'location' => '',
            'pagination_class' => '',
            'listing_type' => '',
            'perpage' => 12,
            'shortlist' => '',
        );
        $atts = shortcode_atts($defaults, $atts);
        $args = $results->parseGetParams($atts);
        $args = array_filter($args);

        $results->fetch_properties($args);

        return str_ireplace(
            array(
                '#properties#',
                '#property_i_first#',
                '#property_i_last#'
            ),
            array(
                $results->count,
                (int)$args['limit'] * ((int)$results->page-1) + 1,
                $results->page == $results->pages ? $results->count : (int)$args['limit'] * ((int)$results->page)
            ),
            $content
        );
    }
}

/*
Inmolink Only on first page
*/
add_shortcode("only_on_first_page", function($atts = array(), $content=NULL){
	if(!isset($_GET['il_page']) || $_GET['il_page'] == "1")
		return do_shortcode($content);
});