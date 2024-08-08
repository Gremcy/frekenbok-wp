<?php
/**
 * SBI_Support_Tool.
 *
 * @since 6.4
*/
namespace InstagramFeed\Admin;

// Exit if accessed directly
if (!defined('ABSPATH') ) {
    exit;
}

class SBI_Support_Tool
{

    static $plugin_name = 'SmashBalloon Instagram';
    static $plugin = 'smash_sbi';

    /**
     * Temp User Name
     * @access private
     *
     * @var string
     */
    static $name = 'SmashBalloon';

    /**
     * Temp Last Name
     * @access private
     *
     * @var string
     */
    static $last_name = 'Support';


    /**
     * Temp Login UserName
     * @access private
     *
     * @var string
     */
    static $username = 'SmashBalloon_SBISupport';

    /**
     * Cron Job Name
     * @access public
     *
     * @var string
     */
    static $cron_event_name = 'smash_sbi_delete_expired_user';

    /**
     * Temp User Role
     * @access private
     *
     * @var string
     */
    static $role = '_support_role';

    public function __construct()
    {
        $this->init();
    }

    /**
     * SBI_Support_Tool constructor.
     *
     * @since 6.3
     */
    public function init()
    {
        $this->init_temp_login();

        if (! is_admin() ) {
            return;
        }

        $this->ini_ajax_calls();
        add_action('admin_menu', array( $this, 'register_menu' ));
        add_action( 'admin_footer', ['\InstagramFeed\Admin\SBI_Support_Tool', 'delete_expired_users'] );
    }

    /**
     * Create New User Ajax Call
     *
     * @since 6.3
     *
     * @return void
     */
    public function ini_ajax_calls()
    {
        add_action('wp_ajax_sbi_create_temp_user', array( $this, 'create_temp_user_ajax_call'));
        add_action('wp_ajax_sbi_delete_temp_user', array( $this, 'delete_temp_user_ajax_call'));
    }

    /**
     * Create New User Ajax Call
     *
     * @since 6.3
     */
    public function delete_temp_user_ajax_call()
    {
        check_ajax_referer('sbi-admin', 'nonce');
        if (!sbi_current_user_can('manage_instagram_feed_options')) {
            wp_send_json_error();
        }

        if (!isset($_POST['userId'])) {
            wp_send_json_error();
        }

        $user_id = sanitize_key($_POST['userId']);
        $return = SBI_Support_Tool::delete_temporary_user($user_id);
        echo wp_json_encode($return);
        wp_die();
    }
    /**
     * Create New User Ajax Call
     *
     * @since 6.3
     */
    public function create_temp_user_ajax_call()
    {
        check_ajax_referer('sbi-admin', 'nonce');
        if (!sbi_current_user_can('manage_instagram_feed_options')) {
            wp_send_json_error();
        }
        $return = SBI_Support_Tool::create_temporary_user();
        echo wp_json_encode($return);
        wp_die();
    }

    /**
     * Init Login
     *
     * @since 6.3
     */
    public function init_temp_login()
    {

        $attr = SBI_Support_Tool::$plugin . '_token';
        if (empty($_GET[ $attr ])) {
            return;
        }


        $token = sanitize_key($_GET[ $attr ]);  // Input var okay.
        $temp_user = SBI_Support_Tool::get_temporary_user_by_token($token);
        if (!$temp_user) {
            wp_die(esc_attr__("You Cannot connect user", 'instgaram-feed'));
        }

        $user_id = $temp_user->ID;
        $should_login =( is_user_logged_in() && $user_id !== get_current_user_id() ) || !is_user_logged_in();

        if ($should_login) {

            if ($user_id !== get_current_user_id()) {
                wp_logout();
            }

            $user_login = $temp_user->user_login;

            wp_set_current_user($user_id, $user_login);
            wp_set_auth_cookie($user_id);
            do_action('wp_login', $user_login, $temp_user);
            $redirect_page = 'admin.php?page='.SBI_Support_Tool::$plugin .'_tool';
            wp_safe_redirect(admin_url($redirect_page));
            exit();
        }

    }

    /**
     * Create New User.
     *
     * @return array
     *
     * @since 6.3
     */
    public static function create_temporary_user()
    {
        if (!current_user_can('create_users')) {
            return [
                'success' => false,
                'message' => __('You don\'t have enough permission to create users'),
            ];
        }
        $domain = str_replace([
            'http://', 'https://', 'http://www.', 'https://www.', 'www.'
        ], '', site_url());

        $email = SBI_Support_Tool::$username . '@' . $domain;
        $temp_user_args = [
            'user_email' => $email,
            'user_pass'  => SBI_Support_Tool::generate_temp_password(),
            'first_name' => SBI_Support_Tool::$name,
            'last_name'  => SBI_Support_Tool::$last_name,
            'user_login' => SBI_Support_Tool::$username,
            'role' => SBI_Support_Tool::$plugin . SBI_Support_Tool::$role
        ];

        $temp_user_id = \wp_insert_user($temp_user_args);

        $result = [];

        if (is_wp_error($temp_user_id)) {
            $result = [
                'success' => false,
                'message' => __('Cannot create user')
            ];
        } else {
            $creation_time = \current_time('timestamp');
            $expires = strtotime('+15 days', $creation_time);
            $token = str_replace(['=', '&', '"',  "'"], '', \sbi_encrypt_decrypt( 'encrypt', SBI_Support_Tool::generate_temp_password(35)));

            update_user_meta($temp_user_id, SBI_Support_Tool::$plugin . '_user', $temp_user_id);
            update_user_meta($temp_user_id, SBI_Support_Tool::$plugin . '_token', $token);
            update_user_meta($temp_user_id, SBI_Support_Tool::$plugin . '_create_time', $creation_time);
            update_user_meta($temp_user_id, SBI_Support_Tool::$plugin . '_expires', $expires);

            $result = [
                'success' => true,
                'message' => __('Temporary user created successfully'),
                'user' =>  SBI_Support_Tool::get_user_meta_data($temp_user_id)
            ];
        }
        return $result;
    }

    /**
     * Delete Temp User.
     *
     * @param $user_id User ID to delete
     *
     * @return array
     *
     * @since 6.3
     */
    public static function delete_temporary_user($user_id)
    {
        require_once(ABSPATH.'wp-admin/includes/user.php');

        if (!current_user_can('delete_users')) {
            return [
                'success' => false,
                'message' => __('You don\'t have enough permission to delete users'),
            ];
        }
        if (!wp_delete_user($user_id)) {
            return [
                'success' => false,
                'message' => __('Cannot delete this user'),
            ];
        }

        return [
            'success' => true,
            'message' => __('User Deleted'),
        ];
    }

    /**
     * Get User Meta
     *
     * @param $user_id User ID to Get
     *
     * @return array/boolean
     *
     * @since 6.3
    */
    public static function get_user_meta_data($user_id)
    {
        $user = get_user_meta($user_id, SBI_Support_Tool::$plugin . '_user');
        if (!$user) {
            return false;
        }
        $token = get_user_meta($user_id, SBI_Support_Tool::$plugin . '_token');
        $creation_time = get_user_meta($user_id, SBI_Support_Tool::$plugin . '_create_time');
        $expires = get_user_meta($user_id, SBI_Support_Tool::$plugin . '_expires');

        $url = SBI_Support_Tool::$plugin . '_token=' . $token[0];
        return [
            'id' => $user_id,
            'token' => $token[0],
            'creation_time' => $creation_time[0],
            'expires' => $expires[0],
            'expires_date' => SBI_Support_Tool::get_expires_days($expires[0]),
            'url'  =>  admin_url('/?' . $url)
        ];
    }

    /**
     * Get UDays before Expiring Token
     *
     * @param $expires timestamp
     *
     * @since 6.3
    */
    public static function get_expires_days($expires)
    {
        return ceil(($expires-time())/60/60/24);
    }

    /**
     * Get User By Token.
     *
     * @param $token Token to connect with
     *
     * @since 6.3
     */
    public static function get_temporary_user_by_token($token = '')
    {
        if (empty($token)) {
            return false;
        }

        $args = [
            'fields'     => 'all',
            'meta_query' => [
                [
                    'key'     => SBI_Support_Tool::$plugin . '_token',
                    'value'   => sanitize_text_field($token),
                    'compare' => '=',
                ]
            ]
        ];

        $users = new \WP_User_Query($args);
        $users_result = $users->get_results();

        if (empty($users_result)) {
            return false;
        }

        return $users_result[0];
    }

    /**
     * Check Temporary User Created
     *
     * @since 6.3
     */
    public static function check_temporary_user_exists()
    {
        $args = [
            'fields'     => 'all',
            'meta_query' => [
                [
                    'key'     => SBI_Support_Tool::$plugin . '_token',
                    'value'   => null,
                    'compare' => '!=',
                ]
            ]
        ];
        $users = new \WP_User_Query($args);
        $users_result = $users->get_results();
        if (empty($users_result)) {
            return null;
        }
        return SBI_Support_Tool::get_user_meta_data($users_result[0]->ID);
    }

    /**
      * Check & Delete Expired Users
      *
      * @since 6.3
      *
      */
    public static function delete_expired_users()
    {
        $existing_user = SBI_Support_Tool::check_temporary_user_exists();
        if ($existing_user === null) {
            return false;
        }
        $is_expired = intval($existing_user['expires']) - \current_time('timestamp') <= 0;
        if (!$is_expired) {
            return false;
        }
        require_once(ABSPATH.'wp-admin/includes/user.php');
        \wp_delete_user($existing_user['id']);
    }

    /**
      * Delete Temp User
      *
      * @since 6.3
      *
      */
    public static function delete_temp_user()
    {
        $existing_user = SBI_Support_Tool::check_temporary_user_exists();
        if ($existing_user === null) {
            return false;
        }
        require_once(ABSPATH.'wp-admin/includes/user.php');
        \wp_delete_user($existing_user['id']);
    }


    /**
     * Register Menu.
     *
     * @since 6.0
     */
    public function register_menu()
    {
        $role_id      = SBI_Support_Tool::$plugin . SBI_Support_Tool::$role;
        $cap          = $role_id;
        $cap          = apply_filters('sbi_settings_pages_capability', $cap);

        $support_tool_page = add_submenu_page(
            'sb-instagram-feed',
            __('Support API tool', 'instagram-feed'),
            __('Support API tool', 'instagram-feed'),
            $cap,
            SBI_Support_Tool::$plugin .'_tool',
            array( $this, 'render' ),
            5
        );
        #add_action('load-' . $support_tool_page, array( $this, 'support_page_enqueue_assets'));
    }


    /**
     * Generate Temp User Password
     *
     * @param $length Length of password
     *
     * @since 6.3
     *
     * @return string
     */
    public static function generate_temp_password($length = 20)
    {
        return wp_generate_password( $length, true, true );
    }


    /**
     * Render the Api Tools Page
     *
     * @since 6.3
     *
     * @return string
     */
	public function render()
	{
		include_once SBI_PLUGIN_DIR . 'admin/views/support/support-tools.php';
	}

	/**
	 * Available Endpoints
	 *
	 * @since 6.3
	 *
	 * @return array
	 */
	public function available_endpoints()
	{
		return array(
			'https://graph.instagram.com/me',
			'https://graph.instagram.com/{user_id}/media?',
			'https://graph.facebook.com/{user_id}/stories?',
			'https://graph.facebook.com/{hashtag_id}/top_media?',
			'https://graph.facebook.com/{hashtag_id}/recent_media?',
			'https://graph.facebook.com/{user_id}/recently_searched_hashtags?',
			'https://graph.facebook.com/{user_id}/tags?',
			'https://graph.facebook.com/ig_hashtag_search?',
			'https://graph.facebook.com/{user_id}/media?',
		);
	}

	public function validate_fields( $raw_fields )
	{
		$fields_array = explode(',', $raw_fields );

		$acceptable_fields = array(
			'biography',
			'id',
			'username',
			'website',
			'followers_count',
			'media_count',
			'profile_picture_url',
			'name',
			'limit',
			'media_url',
			'media_product_type',
			'thumbnail_url',
			'caption',
			'id',
			'media_type',
			'timestamp',
			'username',
			'comments_count',
			'like_count',
			'permalink',
			'media_url',
			'id',
			'media_type',
			'timestamp',
			'permalink',
			'thumbnail_url'
		);

		$valid = array();

		foreach ( $fields_array as $field ) {
			if ( in_array( $field, $acceptable_fields, true ) ) {
				$valid[] = $field;
			}
		}

		return $valid;
	}

	public function validate_and_sanitize_support_settings( $raw_post ) {

		if ( empty( $raw_post['sb_instagram_support_connected_account'] ) ) {
			return array();
		}

		$return = array(
			'sb_instagram_support_connected_account' => sanitize_key( $raw_post['sb_instagram_support_connected_account'] ),
			'sb_instagram_support_hashtag' => sanitize_key( $raw_post['sb_instagram_support_hashtag'] ),
			'sb_instagram_support_endpoint' => absint( $raw_post['sb_instagram_support_endpoint'] ),
			'sb_instagram_support_fields' => sanitize_text_field( wp_unslash( $raw_post['sb_instagram_support_fields'] ) ),
			'sb_instagram_support_children_fields' => sanitize_text_field( wp_unslash( $raw_post['sb_instagram_support_children_fields'] ) ),
			'sb_instagram_support_limit' => absint( $raw_post['sb_instagram_support_limit'] ),

		);

		$connected_accounts = \SB_Instagram_Connected_Account::get_all_connected_accounts();

		foreach ( $connected_accounts as $connected_account ) {
			if ( (string) $connected_account['id'] === $return[ 'sb_instagram_support_connected_account' ] ) {
				$return['access_token'] = $connected_account['access_token'];
				$return['user_id'] = (string) $connected_account['id'];
			}
		}

		$endpoints = $this->available_endpoints();
		$return['endpoint'] = $endpoints[ $return['sb_instagram_support_endpoint'] ];
		$return['hashtag_id'] = $return['sb_instagram_support_hashtag'];

		$return['fields'] = $this->validate_fields( str_replace( ' ', '', $raw_post['sb_instagram_support_fields'] ) );
		$return['children_fields'] = $this->validate_fields( str_replace( ' ', '', $raw_post['sb_instagram_support_children_fields'] ) );

		$return['limit'] = $return['sb_instagram_support_limit'];

		return $return;
	}

	public function create_api_url( $url, $settings ) {
		if ( ! empty( $settings['user_id'] ) ) {
			$url = str_replace( '{user_id}', $settings['user_id'], $url );
		}
		if ( ! empty( $settings['user_id'] ) ) {
			$url = str_replace( '{hashtag_id}', $settings['hashtag_id'], $url );
		}

		$params = array();

		if ( ! empty( $settings['limit'] ) ) {
			$params['limit'] = absint( $settings['limit'] );
		}

		if ( ! empty( $settings['access_token'] ) ) {
			$params['access_token'] = sanitize_key( $settings['access_token'] );
		}

		if ( ! empty( $settings['access_token'] ) ) {
			$params['access_token'] = $settings['access_token'];
		}

		if ( ! empty( $settings['fields'] ) ) {
			$params['fields'] = implode(',', $settings['fields'] );
		}

		if ( ! empty( $settings['children_fields'] ) ) {
			$params['fields'] .= ',children%7B' . implode(',', $settings['children_fields'] ) . '%7D';
		}
		return add_query_arg( $params, $url );
	}


}


?>