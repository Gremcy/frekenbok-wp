<?php

namespace PS\Functions\Seo;

/**
 * Class Rewrite
 * @package PS\Functions\Seo
 */
class Rewrite {

    /**
     * constructor
     */
    public function __construct() {
        add_action('init', array( $this, 'custom_rewrite_rule' ), 10, 0);
    }

    /**
     * rewrite rules
     */
    public function custom_rewrite_rule() {
        //add_rewrite_rule('^delivery/([^/]*)/([^/]*)/([^/]*)/?$','index.php?post_type=delivery&name=$matches[1]&section=$matches[2]&item=$matches[3]','top');
        //add_rewrite_rule('^profile/([^/]*)/?$','index.php?page_id=442&section=$matches[1]','top');
    }
}