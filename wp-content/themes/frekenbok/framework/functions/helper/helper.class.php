<?php

namespace PS\Functions\Helper;

/**
 * Class Helper
 * @package PS\Functions\Helper
 * @since   1.0.0
 */
class Helper {

    /**
     * Init constructor.
     */
    public function __construct() {
        //
    }

    /**
     ******* GENERAL *******
     */
	 
	 // get posts by args
    public static function get_posts( $post_type, $post_status = 'publish', $posts_per_page = -1, $paged = 1, $meta_query = array(), $tax_query = array(), $post__in = array(), $post__not_in = array(), $orderby = 'menu_order', $order = 'ASC' ) {
        $args['post_type']      = $post_type;
        $args['post_status']    = $post_status;
        $args['posts_per_page'] = $posts_per_page;
        $args['paged'] = $paged;
        if ( count( $meta_query ) ) {
            $args['meta_query'] = $meta_query;
        }
        if ( count( $tax_query ) ) {
            $args['tax_query'] = $tax_query;
        }
        if ( count( $post__in ) ) {
            $args['post__in'] = $post__in;
        }
        if ( count( $post__not_in ) ) {
            $args['post__not_in'] = $post__not_in;
        }
        if($orderby){
            $args['orderby'] = $orderby;
        }
        if($order){
            $args['order'] = $order;
        }
        return query_posts( $args );
    }
	
	/**
     ******* PRODUCTS *******
     */

    // get products
    public static function get_products($table_active = false){
        $products = [];

        // get products
        global $wp_query;
        \PS\Functions\Helper\Helper::get_posts(
            'products'
        );
        $custom_query = $wp_query;
        if ( $custom_query->have_posts() ){
            while ( $custom_query->have_posts() ){
                $custom_query->the_post();

                if($table_active){
                    if(get_field('table_active')){
                        $products[] = [
                            'title' => get_the_title(),
                            'link' => get_field('link'),
                            'table_name' => get_field('table_name'),
                            'products_tags' => (array)get_field('products_tags')
                        ];
                    }
                }else{
                    $products[] = [
                        'title' => get_the_title(),
                        'img' => get_field('img'),
                        'link' => get_field('link')
                    ];
                }
            }
        }
        wp_reset_query();

        // return
        return $products;
    }
	
}