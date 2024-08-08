<?php

namespace PS\Functions\Ajax;

/**
 * Class Blog
 * @package     PS\Functions\Ajax
 * @since       1.0.0
 */
class Blog {

    public function __construct() {
        add_action( 'wp_ajax_blog_loading_of_items', array( $this, 'blog_loading_of_items' ) );
        add_action( 'wp_ajax_nopriv_blog_loading_of_items', array( $this, 'blog_loading_of_items' ) );
    }

    /**
     * ajax-loading
     */
    public function blog_loading_of_items() {
        ob_start();

        //
        global $wp_query;
        $args = unserialize( stripslashes( $_POST['query'] ) );

        $args['paged']  = (int)$_POST['page'] + 1;
        $args['post_status'] = 'publish';

        //
        query_posts( $args );
        $custom_query = $wp_query;
        if ( $custom_query->have_posts() ):
            while( $custom_query->have_posts() ): $custom_query->the_post();
                get_template_part( $_POST['template'] );
            endwhile;
        else:
            wp_send_json_error(); // {"success":false}
        endif;
        wp_reset_query();

        //
        $posts = ob_get_clean();

        echo json_encode(
            array(
                'success' => true,
                'posts'   => $posts
            ),
            JSON_UNESCAPED_UNICODE
        );

        exit();
    }
}