<?php
/**
 * Plugin Name: MasOffer Dynamic Link
 * Plugin URI: https://masoffer.com
 * Description: Plugin hỗ trợ redirect link sản phẩm
 * Version: 1.1.0
 * Author: MasOffer
 * License: GPLv2 or later
 */
?>
<?php
if (!class_exists('MasOfferDynamicLink')) {
    class MasOfferDynamicLink
    {
        const PLUGIN_VERSION = "1.1.0";
        public function __construct()
        {
            register_activation_hook(__FILE__, array($this, 'activate'));

            register_uninstall_hook(__FILE__, array(__CLASS__, 'uninstall'));

            add_action('wp_footer', array($this, 'wpb_hook_javascript_footer'));

            add_action('admin_menu', array($this, 'create_menu_admin_panel'));

            add_action('admin_action_masoffer_dynamic_link_action', array($this, 'masoffer_dynamic_link_admin_action'));
        }

        public function activate($network_wide)
        {
            if (version_compare(get_bloginfo('version'), '2.6', '<')) {
                deactivate_plugins(basename(__FILE__));
            } else {
                $data = array(
                    'publisher_id'    => '',
                    'publisher_token' => '',
                    'exclude'         => '',
                    'aff_sub1'        => '',
                    'aff_sub2'        => '',
                    'aff_sub3'        => '',
                    'aff_sub4'        => '',
                    'domain'          => 'gotrackecom.info',
                    'protocol'        => 'https',
                    'parking_domains' => array(),
                );
                add_option('masoffer_dl_data', $data, '', 'no');
            }
        }

        public static function uninstall () {
        }

        function wpb_hook_javascript_footer()
        {
            $data = get_option('masoffer_dl_data');
            if (empty($data['publisher_id'])) {
                return;
            }

            if (empty($data['protocol'])) {
                $data['protocol'] = 'https';
            }

            if (empty($data['domain'])) {
                $data['domain'] = 'gotrackecom.info';
            }

            $params = array(
                'publisher_id'    => $data['publisher_id'],
                'exclude'         => $data['exclude'],
                'aff_sub1'        => $data['aff_sub1'],
                'aff_sub2'        => $data['aff_sub2'],
                'aff_sub3'        => $data['aff_sub3'],
                'aff_sub4'        => $data['aff_sub4'],
            );
            $src  = $data['protocol'] . '://' . $data['domain'] . '/linkify.min.js?' . http_build_query($params);
            wp_enqueue_script('linkify', $src, [], self::PLUGIN_VERSION, true);
        }

        public function create_menu_admin_panel()
        {
            add_options_page(
                'MasOffer Options',
                'MasOffer Dynamic Link',
                'manage_options',
                'masoffer-dynamic-link-official',
                array($this, 'masoffer_dynamic_link_plugin_form')
            );
        }

        public function masoffer_dynamic_link_plugin_form()
        {
            $data = get_option('masoffer_dl_data');
            if (!current_user_can('manage_options')) {
                wp_die(__('You do not have sufficient permission to access this page.'));
            }
            $publisher_id    = isset($data['publisher_id']) ? $data['publisher_id'] : '';
            $publisher_token = isset($data['publisher_token']) ? $data['publisher_token'] : '';
            $exclude         = isset($data['exclude']) ? $data['exclude'] : '';
            $aff_sub1        = isset($data['aff_sub1']) ? $data['aff_sub1'] : '';
            $aff_sub2        = isset($data['aff_sub2']) ? $data['aff_sub2'] : '';
            $aff_sub3        = isset($data['aff_sub3']) ? $data['aff_sub3'] : '';
            $aff_sub4        = isset($data['aff_sub4']) ? $data['aff_sub4'] : '';
            $domain          = isset($data['domain']) ? $data['domain'] : 'gotrackecom.info';
            $protocol        = isset($data['protocol']) ? $data['protocol'] : 'https';
            esc_attr($publisher_id);
            esc_attr($publisher_token);
            esc_attr($exclude);
            esc_attr($aff_sub1);
            esc_attr($aff_sub2);
            esc_attr($aff_sub3);
            esc_attr($aff_sub4);
            esc_attr($domain);
            esc_attr($protocol);
            $parking_domains = array(
                'gotrackecom.info',
                'gotrackecom.asia',
                'gotrackecom.biz',
                'gotrackecom.xyz',
                'rutgon.me',
            );
            if (!empty($data['parking_domains'])) {
                foreach($data['parking_domains'] as $parking_domain) {
                    $parking_domains[] = $parking_domain;
                }
            }
            include 'views/admin.php';
        }

        public function masoffer_dynamic_link_admin_action()
        {
            if (!wp_verify_nonce($_POST['_wpnonce'], 'update-pubid_')) {
                wp_die(__('You do not have sufficient permission to save this form.'));
            }
            if (!current_user_can('manage_options')) {
                wp_die(__('You do not have sufficient permission to save this form.'));
            }

            $data = get_option('masoffer_dl_data');

            if (isset($_POST['update_settings'])) {
                $data['publisher_id']    = sanitize_text_field($_POST['publisher_id']);
                $data['publisher_token'] = $_POST['publisher_token'];
                $data['exclude']         = sanitize_text_field($_POST['exclude']);
                $data['aff_sub1']        = sanitize_text_field($_POST['aff_sub1']);
                $data['aff_sub1']        = sanitize_text_field($_POST['aff_sub1']);
                $data['aff_sub2']        = sanitize_text_field($_POST['aff_sub2']);
                $data['aff_sub3']        = sanitize_text_field($_POST['aff_sub3']);
                $data['aff_sub4']        = sanitize_text_field($_POST['aff_sub4']);
                $data['aff_sub4']        = sanitize_text_field($_POST['aff_sub4']);
                $data['domain']          = sanitize_text_field($_POST['domain']);
                $data['protocol']        = sanitize_text_field($_POST['protocol']);
            }

            if (isset($_POST['update_parking_domains']) && !empty($data['publisher_id']) && !empty($data['publisher_token'])) {
                if (!empty($data['publisher_id']) && !empty($data['publisher_token'])) {
                    $data['parking_domains'] = $this->get_parking_domains($data['publisher_id'], $data['publisher_token']);
                }
                else {
                    $data['parking_domains'] = array();
                }
            }

            update_option('masoffer_dl_data', $data);

            wp_safe_redirect('/wp-admin/options-general.php?page=masoffer-dynamic-link-official');
            exit();
        }

        public function get_parking_domains($publisher_id, $publisher_token) {
            $url = "http://publisher-api.dev.masoffer.tech/v1/domains?publisher_id=" . $publisher_id . '&token=' . $publisher_token;
            $response = wp_remote_get($url, ['timeout' => 10]);
            if (is_wp_error($response)) {
                return array();
            }

            if (200 != wp_remote_retrieve_response_code($response)) {
                return array();
            }

            if (isset(json_decode($response['body'], true)['data'])) {
                return json_decode($response['body'], true)['data'];
            }

            return $array();
        }
    }

    $plugin_name = new MasOfferDynamicLink();
}
