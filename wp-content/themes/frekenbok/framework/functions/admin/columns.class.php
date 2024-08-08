<?php

namespace PS\Functions\Admin;

/**
 * Class Columns
 * @package PS\Functions\Admin
 */
class Columns {

    /**
     * constructor
     */
    public function __construct() {
        // products
        add_filter('manage_edit-products_columns', array( $this, 'columns_head_only_products'), 15);
        add_filter('manage_products_posts_custom_column', array( $this, 'columns_content_only_products'), 10, 2);
    }

    /**
     * products
     */
    public function columns_head_only_products($defaults) {
        unset($defaults['title']);
        unset($defaults['taxonomy-products_tags']);
        unset($defaults['date']);
        $defaults['img'] = 'Фото';
        $defaults['title'] = 'Заголовок';
        $defaults['link'] = 'Посилання';
        $defaults['table'] = 'В таблиці?';
        $defaults['taxonomy-products_tags'] = 'Особливості';
        $defaults['date'] = 'Дата';
        return $defaults;
    }

    public function columns_content_only_products($column_name, $post_ID) {
        // image
        if ($column_name == 'img') {
            $img = get_field('img', $post_ID);
            echo (is_array($img) ? "<img src='" . $img['sizes']['100x100'] . "'>" : "-");
        }

        // link
        elseif ($column_name == 'link') {
            echo get_field('link', $post_ID) ? '<a href="' . get_field('link', $post_ID) . '" target="_blank">' . get_field('link', $post_ID) . '</a>' : '-';
        }

        // table
        elseif ($column_name == 'table') {
            echo get_field('table_active', $post_ID) ? '<div class="ws_icon_image icon16 dashicons dashicons-yes"></div>' : '-';
        }
    }

}