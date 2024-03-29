<?php

use Hupa\StarterThemeV2\ChildPostType;
use Hupa\StarterV2\Admin_Document_Upload;
use Hupa\StarterV2\Register_Admin_Hooks;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Extension\CoreExtension;
use Twig\Loader\FilesystemLoader;
use Twig\TwigFilter;

class Register_Child_Hooks
{

    /**
     *
     * @access   protected
     * @var      Child_Theme_Loader $loader Maintains and registers all hooks for the child.
     */
    protected Child_Theme_Loader $loader;

    /**
     * Store child main class to allow child access.
     *
     * @var object The main class.
     */
    private object $main;

    private string $childVersion = '';
    private string $childBasename;

    /**
     *
     * @var Environment $twig TWIG autoload for PHP-Template-Engine
     */
    protected Environment $twig;

    private static $instance;

    /**
     * @return static
     */
    public static function instance(): self
    {
        if (is_null((self::$instance))) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function __construct()
    {
        $this->main = $this;
        $this->load_dependencies();
        $upload_dir = wp_get_upload_dir();
        define("CHILD_CUSTOM_UPLOAD_DIR", $upload_dir['basedir'] . DIRECTORY_SEPARATOR . 'hupa-child-upload' . DIRECTORY_SEPARATOR);
        define("CHILD_CUSTOM_UPLOAD_URL", $upload_dir['baseurl'].'/hupa-child-upload/');

        $this->childBasename = wp_basename(dirname(__DIR__));
        $child_data = wp_get_theme('starter-theme-v2-oop-child');
        $this->childVersion = $child_data->get('Version');
        $templateDir = get_stylesheet_directory() . DIRECTORY_SEPARATOR . 'inc' . DIRECTORY_SEPARATOR . 'Templates' . DIRECTORY_SEPARATOR . 'Twig' . DIRECTORY_SEPARATOR;
        $twig_loader = new FilesystemLoader($templateDir);

        try {
            $twig_loader->addPath($templateDir . 'Loops', 'loops');
            $twig_loader->addPath($templateDir . 'Templates', 'templates');
            $twig_loader->addPath($templateDir . 'Layouts', 'layout');
        } catch (LoaderError $e) {
        }

        $getImgUrl = new TwigFilter('img_url', function ($id, $type) {
            return wp_get_attachment_image_url($id, $type);
        });

        $getVersion = new TwigFilter('version', function () {
            return $this->childVersion;
        });

        $getOption = new TwigFilter('get_option', function ($option) {
            return get_option($option);
        });

        $wpGetText = new TwigFilter('__', function ($text) {
            return __($text, 'bootscore');
        });

        $this->twig = new Environment($twig_loader);
        $this->twig->getExtension(CoreExtension::class)->setTimezone('Europe/Berlin');
        $this->twig->addFilter($getImgUrl);
        $this->twig->addFilter($getOption);
        $this->twig->addFilter($wpGetText);
        $this->twig->addFilter($getVersion);


        $this->document_upload_handle();
        //$this->register_team_templates();
        //$this->register_child_custom_post();
        $this->register_child_block_endpoint();
        //$this->define_team_gutenberg_hooks();
        //$this->register_theme_render_callback();
        $this->define_child_function_hooks();
        if(CHILD_ADMIN_MENU_AKTIV){
            $this->define_admin_hooks();
        }

    }

    /**
     * @access   private
     */
    private function load_dependencies(): void
    {
        require_once 'vendor/autoload.php';
        require_once 'Admin/ChildSettings.php';
        require_once 'CustomPostTypes/class_post_child_metabox.php';

        require_once 'Templates/class_team_twig_templates.php';
        require_once 'Admin/class_admin_document_upload.php';
        require_once 'CustomPostTypes/ChildPostType.php';
        require_once 'class_child_theme_loader.php';
        require_once 'Gutenberg/class_child_rest_endpoint.php';
        require_once 'Gutenberg/class_register_child_gutenberg_tools.php';
        require_once 'Gutenberg/class_team_gutenberg_block_callback.php';
        require_once 'ChildFunctions/class_child_function_hooks.php';
        require_once 'Admin/class_register_admin_hooks.php';
        $this->loader = new Child_Theme_Loader();
    }

    private function register_child_custom_post(): void
    {
        $postType = ChildPostType::init();
        $this->loader->add_action('init', $postType, 'team_post_type');
        $this->loader->add_action('init', $postType, 'team_taxonomies');

        $this->loader->add_action('init', $postType, 'leistungen_post_type');
        $this->loader->add_action('init', $postType, 'leistungen_taxonomies');
    }

    private function define_team_gutenberg_hooks(): void
    {
        $gbTools = new Register_Child_Gutenberg_Tools($this->main);
        $this->loader->add_action('init', $gbTools, 'theme_posts_meta_fields');
        $this->loader->add_action('init', $gbTools, 'team_gutenberg_register_sidebar');
        $this->loader->add_action('enqueue_block_editor_assets', $gbTools, 'ulrichshaus_members_sidebar_script_enqueue');
        $this->loader->add_action('init', $gbTools, 'register_team_members_block_type');
        $this->loader->add_action('enqueue_block_editor_assets', $gbTools, 'team_members_block_type_scripts');

        $this->loader->add_action('init', $gbTools, 'register_leistungen_block_type');
        $this->loader->add_action('enqueue_block_editor_assets', $gbTools, 'leistungen_block_type_scripts');


    }

    private function register_theme_render_callback(): void
    {
        global $registerThemeCallback;
        $registerThemeCallback = new Theme_Gutenberg_Block_Callback();
    }

    private function register_child_block_endpoint(): void
    {

        $registerEndpoint = new Team_Rest_Endpoint($this->main);
        $this->loader->add_filter('child_get_custom_terms', $registerEndpoint, 'child_theme_get_custom_terms');
        $this->loader->add_action('rest_api_init', $registerEndpoint, 'register_block_child_routes');
    }

    private function define_child_function_hooks(): void
    {
        $functions = new Child_Function_Hooks($this->main, $this->twig);
        $this->loader->add_action('wp_enqueue_scripts', $functions, 'enqueue_scripts');
        $this->loader->add_action('wp_ajax_nopriv_ChildNoAdmin', $functions, 'public_ajax_ChildNoAdmin');
        $this->loader->add_action('wp_ajax_ChildNoAdmin', $functions, 'public_ajax_ChildNoAdmin');
        $this->loader->add_action('wp_head', $functions, 'set_child_header_options');
        //$this->loader->add_action( 'template_redirect', $functions, 'child_redirect_template_redirect' );
    }

    private function define_admin_hooks()
    {
        global $register_child_admin_options;
        $register_child_admin_options = Register_Admin_Hooks::admin_child_instance($this->get_child_slug(), $this->get_theme_version(), $this->main, $this->twig);
        $this->loader->add_action('admin_menu', $register_child_admin_options, 'register_child_theme_admin_menu');
        $this->loader->add_action('wp_ajax_nopriv_ChildAdmin', $register_child_admin_options, 'admin_ajax_ChildAdmin');
        $this->loader->add_action('wp_ajax_ChildAdmin', $register_child_admin_options, 'admin_ajax_ChildAdmin');

        //$this->loader->add_action('init', $register_child_admin_options, 'child_documents_trigger_check');
        //$this->loader->add_action('template_redirect', $register_child_admin_options, 'child_document_callback_trigger');
        $this->loader->add_filter('child_check_user_capabilities', $register_child_admin_options, 'fn_child_check_user_capabilities');

        //$this->loader->add_action('admin_enqueue_scripts', $register_child_admin_options, 'child_theme_wordpress_dashboard_style');
        // $this->loader->add_action('edited_term_taxonomy', $register_child_admin_options, 'updateCounts',100,2);

    }

    private function register_team_templates(): void
    {
        $templates = new Team_Twig_Templates($this->main, $this->twig);
        $this->loader->add_filter('ulrichshaus_get_template', $templates, 'filter_ulrichshaus_get_template');
        $this->loader->add_filter('ulrichshaus_get_leistungen_template', $templates, 'filter_ulrichshaus_get_leistungen_template');
        $this->loader->add_filter('team_get_post_meta', $templates, 'child_get_post_meta');
    }

    private function document_upload_handle()
    {
        global $uploadHandle;
        $uploadHandle = Admin_Document_Upload::instance($this->main);
        $this->loader->add_filter( 'get_document_size', $uploadHandle, 'FileSizeConvert' );

    }

    public function run()
    {
        $this->loader->run();
    }

    /**
     * @return    Child_Theme_Loader    Orchestrates the hooks of the child.
     */
    public function get_loader(): Child_Theme_Loader
    {
        return $this->loader;
    }

    public function get_theme_version(): string
    {
        return $this->childVersion;
    }

    public function get_child_slug(): string
    {
        return $this->childBasename;
    }
}