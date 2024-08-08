<?php

namespace PS\Functions\Seo;

/**
 * Class Breadcrumbs
 * @package PS\Functions\Seo
 */
class Breadcrumbs {

    /**
     * constructor
     */
    public function __construct() {
        //
    }

    /**
     * breadcrumbs
     */
    public static function generate_breadcrumbs_array() {
        $return = array();

        // main page
        $return[] = array(
            'title' => __( 'Главная', \PS::$theme_name ),
            'link' => '/'
        );

        // archive
        if(is_post_type_archive()){
            $post_type_object = get_queried_object();
            //
            $return[] = array(
                'title' => $post_type_object->label,
                'link' => '/' . $post_type_object->name . '/'
            );
        }

        // single
        elseif(is_singular()){
            //
            $post_type = get_post_type(get_the_ID());
            if($post_type && $post_type !== 'page'){
                $post_type_object = get_post_type_object( $post_type );
                //
                $return[] = array(
                    'title' => $post_type_object->label,
                    'link' => '/' . $post_type_object->name . '/'
                );
            }

			$return[] = array(
				'title' => get_the_title(),
				'link' => get_the_permalink()
			);
        }

        // page
        elseif(is_page()){
            $return[] = array(
                'title' => get_the_title(),
                'link' => get_the_permalink()
            );
        }

        //
        return $return;
    }

}