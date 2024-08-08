<?php

namespace PS\Functions\Plugins;

/**
 * Class ACF
 * @package PS\Functions\Plugins
 */
class ACF {

    /**
     * constructor
     */
    public function __construct() {
        // wysiwyg field
        add_filter( 'acf/fields/wysiwyg/toolbars', array( $this, 'my_toolbars') );
    }

    /**
     * Wysiwyg field
     */
    public function my_toolbars( $toolbars ){
        // change
        $toolbars['Basic'][1] = [
            'bold',
            'italic',
            'underline',
            'strikethrough',
            'forecolor',
            'link',
            'pastetext',
            'removeformat'
        ];

        $toolbars['Full'][1] = [
            'formatselect',
            'bold',
            'italic',
            'underline',
            'strikethrough',
            'forecolor',
            'alignleft',
            'aligncenter',
            'alignright',
            'bullist',
            'numlist',
            'link',
            //'blockquote',
            'pastetext',
            'removeformat'
        ];
        $toolbars['Full'][2] = [];

        return $toolbars;
    }

}