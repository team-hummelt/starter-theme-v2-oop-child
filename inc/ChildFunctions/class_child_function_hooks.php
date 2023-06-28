<?php


use Twig\Environment;

class Child_Function_Hooks
{
    /**
     * Store plugin main class to allow public access.
     *
     * @since    1.0.0
     * @access   private
     * @var Register_Child_Hooks $main The main class.
     */
    private Register_Child_Hooks $main;

    /**
     * Store plugin main class to allow public access.
     *
     * @var Environment $twig TWIG autoload for PHP-Template-Engine
     */
    protected Environment $twig;

    public function __construct(Register_Child_Hooks $main, Environment $twig)
    {
        $this->main = $main;
        $this->twig = $twig;
    }

    public function set_child_header_options()
    {
        global $wp_query;
        $paged = $wp_query->get('pagename');

        $kontakt = get_option('tools_hupa_address');
        //$telefon = str_replace([' ', '-', '/', '–'], '', $kontakt[6]['value']);
        ?>
            <div id="theme-current-page" data-page="<?= $paged ?>"></div>
        <?php
        /*ob_start(function ($output) {
            if (is_user_logged_in()) {
                $url = wp_logout_url(site_url());
                $name = 'Logout';
            } else {
                $url = admin_url();
                $name = 'Login';
            }

            $output = str_replace('###LOGIN-NAME###', $name, $output);
            return str_replace('/###LOGIN-URL###', $url, $output);
        });*/
    }

    public function child_redirect_template_redirect(): void {
        ob_start( function ( $output ) {
            $term = get_term(get_nav_menu_locations()['footer-widget-menu'], 'nav_menu');
            $items = wp_get_nav_menu_items( $term->term_id);
            $i=1;
            $menu = '<ul class="footer-menu-wrapper list-unstyled d-md-flex d-block flex-wrap align-items-center justify-content-center">';
            foreach ($items as $item){
                $i == 1 ? $me = 'me-md-4' : $me = '';
                $menu .= '<li><a class="footer-menu-item text-decoration-none '.$me.'" href="'.$item->url.'">'.$item->title.'</a></li>';
                $i++;
            }
            $menu .= '</ul>';
            $output = str_replace('###FOOTER-MENU###', $menu, $output);
            return $output;
        });
    }

    /**
     * @throws Exception
     */
    public function public_ajax_ChildNoAdmin(): void
    {
        check_ajax_referer('child_public_handle');
        require get_stylesheet_directory() . '/inc/Ajax/class_child_public_ajax.php';
        $publicAjaxHandle = Child_Public_Ajax::child_ajax_instance($this->main, $this->twig);
        wp_send_json($publicAjaxHandle->public_ajax_handle());
    }

    public function enqueue_scripts()
    {
        // style.css
        wp_enqueue_style('parent-style', get_template_directory_uri() . '/style.css');
        // child-style.css
        //wp_enqueue_style('starter-v2-child-style', get_stylesheet_directory_uri() . '/assets/css/child-style.css');
        $modificated = date('YmdHi', filemtime(get_stylesheet_directory() . '/assets/js/lib/theme.js'));
        $modificated = date('YmdHi', filemtime(get_stylesheet_directory() . '/assets/js/child.js'));
        $modificated = date('YmdHi', filemtime(get_stylesheet_directory() . '/assets/js/lib/video-gallery.js'));
        $modificated = date('YmdHi', filemtime(get_stylesheet_directory() . '/assets/js/lib/hupa-starter-theme.js'));

        // JOB HUPA-STARTER-THEME Video Gallery JS
        wp_enqueue_script('hupa-starter-video-script', get_stylesheet_directory_uri(). '/assets/js/lib/video-gallery.js', array(), $modificated, true);
        wp_enqueue_script('bootscore-script', get_stylesheet_directory_uri() . '/assets/js/lib/theme.js', false, $modificated, true);
        // JOB HUPA-STARTER-THEME JS
        wp_enqueue_script('hupa-starter-script', get_stylesheet_directory_uri() . '/assets/js/lib/hupa-starter-theme.js', false, $modificated, true);

        wp_enqueue_script('child-script', get_stylesheet_directory_uri() . '/assets/js/child.js', false, $modificated, true);

        wp_register_script('child-localize', '', [], '', true);
        wp_enqueue_script('child-localize');
        wp_localize_script('child-localize', 'child_localize_obj', array(
            'img_object' => get_hupa_frontend('nav-img'),
            'site_url' => site_url(),
            'site_title' => get_bloginfo('title'),
            'kontakt' => get_option('tools_hupa_address'),
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('child_public_handle'),
            'is_user_logged_in' => is_user_logged_in()
        ));
    }
}