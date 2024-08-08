<?php if(!defined('ABSPATH')){ exit(); }

//
require_once 'autoload.php';

/**
 * Class PS
 * @since 1.0.0
 */
class PS{
    /**
     * theme name
     * @var string
     */
    static $theme_name;

    /**
     * theme uri
     * @var string
     */
    static $theme_uri;

    /**
     * framework path
     * @var string
     */
    static $framework_path;

    /**
     * assets url
     * @var string
     */
    static $assets_url;

    /**
     * functions path
     * @var string
     */
    static $functions_path;

	/**
     * pages
     * @var string
     */
    static $pages;

    /**
     * options page
     * @var string
     */
    static $option_page;

	/**
     * language
     * @var string
     */
    static $current_language;

    /**
     * PS constructor.
     */
    public function __construct(){
        $this::$theme_name = 'frekenbok';
        $this::$theme_uri = get_template_directory_uri();
        $this::$framework_path = dirname(__FILE__);
        $this::$functions_path = $this::$framework_path . '/functions/';
        $this::$assets_url = $this::$theme_uri . '/assets/';
		
		$this::$pages = [
            'front' => 9
        ];
        $this::$option_page = 'option';
		
		$this::$current_language = 'uk';

        // load functions
        $this->load_functions();
    }

    private function load_functions(){
        if(is_dir($this::$functions_path)){
            if($functions_path_handle = opendir($this::$functions_path)){
                $classes = array();
                while(false !== ($functions_folder = readdir($functions_path_handle))){
                    if(is_dir($this::$functions_path . $functions_folder) && !in_array($functions_folder, array('..', '.'))){
                        $functions_folder = strtoupper(substr($functions_folder, 0, 1)) . substr($functions_folder, 1);
                        $functions_folder_long = explode('-', $functions_folder);
                        if(count($functions_folder_long) > 0){
                            $new_folder_part_name = array();
                            foreach($functions_folder_long as $folder_part_item){
                                $new_folder_part_name[] = strtoupper(substr($folder_part_item, 0, 1)) . substr($folder_part_item, 1);
                            }
                            $functions_folder = implode('_', $new_folder_part_name);
                        }
                        $function_init_name = 'PS\Functions\\' . $functions_folder . '\Init';
                        try{
                            if(class_exists($function_init_name)){
                                if(method_exists($function_init_name, 'is_initialize')){
                                    if($function_init_name::is_initialize()){
                                        $classes[] = $function_init_name;
                                    }
                                }else{
                                    $classes[] = $function_init_name;
                                }
                            }
                        }catch(Exception $e){
                            //
                        }
                    }
                }
                foreach($classes as $class){
                    try{
                        new $class;
                    }catch (Exception $e){
                        //
                    }
                }
                closedir($functions_path_handle);
            }
        }
    }

    /**
     * theme vars
     *
     */
    public static function get_context(){
        $context = array();
        return apply_filters('PS_get_context', $context);
    }

}

new PS();