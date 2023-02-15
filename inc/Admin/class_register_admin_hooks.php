<?php

namespace Hupa\StarterV2;

use Child\Ajax\Class_Admin_Ajax;

use Exception;
use stdClass;
use Twig\Environment;
use Register_Child_Hooks;
use Throwable;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;


class Register_Admin_Hooks
{
    private static $instance;

        use ChildSettings;

    /**
     * Store plugin main class to allow admin access.
     *
     * @since    2.0.0
     * @access   private
     * @var Register_Child_Hooks $main The main class.
     */
    protected Register_Child_Hooks $main;

    /**
     * TWIG autoload for PHP-Template-Engine
     * the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      Environment $twig TWIG autoload for PHP-Template-Engine
     */
    protected Environment $twig;

    /**
     * The ID of this theme.
     *
     * @since    2.0.0
     * @access   private
     * @var      string $basename The ID of this theme.
     */
    protected string $basename;

    /**
     * The version of this theme.
     *
     * @since    2.0.0
     * @access   private
     * @var      string $theme_version The current version of this theme.
     */
    protected string $theme_version;

    /**
     * @return static
     */
    public static function admin_child_instance(string $theme_name, string $theme_version, Register_Child_Hooks $main, Environment $twig): self
    {
        if (is_null(self::$instance)) {
            self::$instance = new self($theme_name, $theme_version, $main, $twig);
        }
        return self::$instance;
    }

    public function __construct(string $theme_name, string $theme_version, Register_Child_Hooks $main, Environment $twig)
    {

        $this->basename = $theme_name;
        $this->theme_version = $theme_version;
        $this->main = $main;
        $this->twig = $twig;
    }

    public function register_child_theme_admin_menu(): void
    {
        add_menu_page(
            __('Optionen', 'bootscore'),
            __('Optionen', 'bootscore'),
            'manage_options',
            'child-settings',
            '',
            $this->get_svg_icons('rss')
            , 8
        );

        $hook_suffix = add_submenu_page(
            'child-settings',
            __('Settings', 'bootscore'),
            __('Settings', 'bootscore'),
            'manage_options',
            'child-settings',
            array($this, 'hupa_child_settings_startseite'));

        add_action('load-' . $hook_suffix, array($this, 'hupa_child_load_ajax_admin_options_script'));

        $hook_suffix = add_submenu_page(
            'child-settings',
            __('Optionen', 'bootscore'),
            __('Optionen', 'bootscore'),
            'manage_options',
            'child-options',
            array($this, 'hupa_child_options'));

        add_action('load-' . $hook_suffix, array($this, 'hupa_child_load_ajax_admin_options_script'));

        $hook_suffix = add_submenu_page(
            'child-settings',
            __('Upload', 'bootscore'),
            __('Upload', 'bootscore'),
            'manage_options',
            'child-upload',
            array($this, 'hupa_child_upload'));

        add_action('load-' . $hook_suffix, array($this, 'hupa_child_load_ajax_admin_options_script'));

    }

    public function hupa_child_settings_startseite(): void
    {
        $title_nonce = wp_create_nonce('child_admin_handle');
        $data = [
            'nonce' => $title_nonce
        ];

        try {
            $template = $this->twig->render('@templates/admin-settings-page.html.twig', $data);
            echo $this->html_compress_template($template);
        } catch (LoaderError|SyntaxError|RuntimeError $e) {
            echo $e->getMessage();
        } catch (Throwable $e) {
            echo $e->getMessage();
        }
    }

    public function hupa_child_options()
    {

        $title_nonce = wp_create_nonce('child_admin_handle');
        $data = [
            'nonce' => $title_nonce
        ];
        try {
            $template = $this->twig->render('@templates/admin-options-page.html.twig', $data);
            echo $this->html_compress_template($template);
        } catch (LoaderError|SyntaxError|RuntimeError $e) {
            echo $e->getMessage();
        } catch (Throwable $e) {
            echo $e->getMessage();
        }
    }

    public function hupa_child_upload()
    {
        $title_nonce = wp_create_nonce('child_admin_handle');
        $data = [
            'nonce' => $title_nonce
        ];
        try {
            $template = $this->twig->render('@templates/document-upload.html.twig', $data);
            echo $this->html_compress_template($template);
        } catch (LoaderError|SyntaxError|RuntimeError $e) {
            echo $e->getMessage();
        } catch (Throwable $e) {
            echo $e->getMessage();
        }
    }



    public function fn_child_check_user_capabilities(): bool
    {
        global $current_user;
      /* $downMin = get_option('child_upload_settings')['download_min_role'];
        if(user_can($current_user->ID, $downMin) ) {
            return true;
        }*/

        return true;

    }


    public function hupa_child_load_ajax_admin_options_script(): void
    {
        add_action('admin_enqueue_scripts', array($this, 'load_hupa_child_theme_admin_style'));
        $title_nonce = wp_create_nonce('child_admin_handle');

        wp_register_script('hupa-child-ajax-script', '', [], '', true);
        wp_enqueue_script('hupa-child-ajax-script');
        wp_localize_script('hupa-child-ajax-script', 'child_admin_obj', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => $title_nonce,
            'tableJson' => get_stylesheet_directory_uri() . '/assets/DataTablesGerman.json'
        ));
    }

    /**
     * @throws Exception
     */
    public function admin_ajax_ChildAdmin(): void
    {
        check_ajax_referer('child_admin_handle');
        require get_stylesheet_directory() . '/inc/Ajax/class_admin_ajax.php';
        $adminAjaxHandle = Class_Admin_Ajax::admin_ajax_instance($this->main, $this->twig);
        wp_send_json($adminAjaxHandle->admin_ajax_handle());
    }

    public function load_hupa_child_theme_admin_style()
    {
        // TODO DASHBOARD STYLES
        wp_enqueue_style('hupa-starter-admin-bs-style', get_template_directory_uri() . '/admin/admin-core/assets/css/bs/bootstrap.min.css', array(), $this->theme_version, false);
        wp_enqueue_style('hupa-starter-admin-bs-icons-style', get_template_directory_uri() . '/admin/vendor/twbs/bootstrap-icons/font/bootstrap-icons.css', array(), $this->theme_version, false);
        wp_enqueue_style('hupa-starter-admin-dashboard-style', get_template_directory_uri() . '/admin/admin-core/assets/css/admin-dashboard-style.css', array(), $this->theme_version, false);

        wp_enqueue_style('hupa-starter-child-admin-dropzone', get_stylesheet_directory_uri() . '/assets/js/tools/dropzone/dropzone.min.css', array(), $this->theme_version, false);

        wp_enqueue_style('font-awesome-icons-style', get_template_directory_uri() . '/admin/vendor/components/font-awesome/css/font-awesome.min.css', array(), $this->theme_version);

        wp_enqueue_style('hupa-starter-admin-animate', get_template_directory_uri() . '/admin/admin-core/assets/css/tools/animate.min.css', array(), $this->theme_version, false);
        wp_enqueue_style('hupa-starter-swal2', get_template_directory_uri() . '/admin/admin-core/assets/css/tools/sweetalert2.min.css', array(), $this->theme_version, false);

        wp_enqueue_script('hupa-hupa-starter-bs', get_template_directory_uri() . '/admin/admin-core/assets/js/bs/bootstrap.bundle.min.js', array(), $this->theme_version, true);
        wp_enqueue_script('js-hupa-swal2-script', get_template_directory_uri() . '/admin/admin-core/assets/js/tools/sweetalert2.all.min.js', array(), $this->theme_version, true);

        wp_enqueue_style('hupa-starter-admin-bs-data-table', get_template_directory_uri() . '/admin/admin-core/assets/css/tools/dataTables.bootstrap5.min.css', array(), $this->theme_version, false);

        wp_enqueue_script('js-hupa-data-table', get_template_directory_uri() . '/admin/admin-core/assets/js/tools/data-table/jquery.dataTables.min.js', array(), $this->theme_version, true);
        wp_enqueue_script('js-hupa-bs-data-table', get_template_directory_uri() . '/admin/admin-core/assets/js/tools/data-table/dataTables.bootstrap5.min.js', array(), $this->theme_version, true);

        wp_enqueue_script('admin-dropzone-dashboard', get_stylesheet_directory_uri() . '/assets/js/tools/dropzone/dropzone.min.js', false, $this->theme_version, true);
        wp_enqueue_script('admin-dropzone-options-dashboard', get_stylesheet_directory_uri() . '/assets/js/tools/dropzone/dropzone-optionen.js', false, $this->theme_version, true);

        wp_enqueue_style('hupa-starter-child-admin', get_stylesheet_directory_uri() . '/assets/dashboard.css', array(), $this->theme_version, false);

        wp_enqueue_script('admin-child-dashboard', get_stylesheet_directory_uri() . '/assets/js/Admin.js', false, $this->theme_version, true);
    }

    public function child_theme_wordpress_dashboard_style()
    {
        $child_current_screen = get_current_screen();
        if($child_current_screen->post_type == 'mitglieder'|| $child_current_screen->post_type == 'foerder-mitglieder'){
            wp_enqueue_style('metabox-admin-bs-style', get_template_directory_uri() . '/admin/admin-core/assets/css/bs/bootstrap.min.css', array(), $this->theme_version, false);
        }
        // wp_enqueue_style('hupa-starter-admin-custom-icons', Config::get('WP_THEME_ADMIN_URL') . 'admin-core/assets/css/tools.css', array(), $this->theme_version, false);
    }


    /**
     * @param $name
     *
     * @return string
     */
    private static function get_svg_icons($name): string
    {
        $icon = '';
        switch ($name) {
            case'rss':
                $icon = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-wrench-adjustable" viewBox="0 0 16 16">
                        <path d="M16 4.5a4.492 4.492 0 0 1-1.703 3.526L13 5l2.959-1.11c.027.2.041.403.041.61Z"/>
                        <path d="M11.5 9c.653 0 1.273-.139 1.833-.39L12 5.5 11 3l3.826-1.53A4.5 4.5 0 0 0 7.29 6.092l-6.116 5.096a2.583 2.583 0 1 0 3.638 3.638L9.908 8.71A4.49 4.49 0 0 0 11.5 9Zm-1.292-4.361-.596.893.809-.27a.25.25 0 0 1 .287.377l-.596.893.809-.27.158.475-1.5.5a.25.25 0 0 1-.287-.376l.596-.893-.809.27a.25.25 0 0 1-.287-.377l.596-.893-.809.27-.158-.475 1.5-.5a.25.25 0 0 1 .287.376ZM3 14a1 1 0 1 1 0-2 1 1 0 0 1 0 2Z"/>
                        </svg>';
                break;

            default:
        }
        return 'data:image/svg+xml;base64,' . base64_encode($icon);
    }

    protected function html_compress_template(string $string): string
    {
        if (!$string) {
            return $string;
        }
        return preg_replace(['/<!--(.*)-->/Uis', "/[[:blank:]]+/"], ['', ' '], str_replace(["\n", "\r", "\t"], '', $string));
    }

}