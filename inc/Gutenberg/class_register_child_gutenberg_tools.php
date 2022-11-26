<?php

class Register_Child_Gutenberg_Tools
{

    protected Register_Child_Hooks $main;

    public function __construct(Register_Child_Hooks $main)
    {
        $this->main = $main;
    }


    public function team_gutenberg_register_sidebar(): void
    {
        $plugin_asset = require get_stylesheet_directory() . '/inc/Gutenberg/Sidebar/build/index.asset.php';
        wp_register_script(
            'ulrichshaus-team-members',
            get_stylesheet_directory_uri() . '/inc/Gutenberg/Sidebar/build/index.js',
            $plugin_asset['dependencies'], $plugin_asset['version'], true
        );

        wp_register_script('ulrichshaus-team-js-localize', '', [], $plugin_asset['version'], true);
        wp_enqueue_script('ulrichshaus-team-js-localize');
        wp_localize_script('ulrichshaus-team-js-localize',
            'UlrichsHausObj',
            array(
                'url' => esc_url_raw(rest_url('child-methods/v1/')),
                'nonce' => wp_create_nonce('wp_rest')
            )
        );
    }

    public function ulrichshaus_members_sidebar_script_enqueue()
    {
        wp_enqueue_script('ulrichshaus-team-members');
        wp_enqueue_style('ulrichshaus-members-sidebar-style');
        wp_enqueue_style(
            'ulrichshaus-members-sidebar-style',
            get_stylesheet_directory_uri() . '/inc/Gutenberg/Sidebar/build/index.css', array(), 'v123');
    }

    /**
     * Register TAM MEMBERS REGISTER GUTENBERG BLOCK TYPE
     *
     * @since    1.0.0
     */
    public function register_team_members_block_type()
    {
        register_block_type('ulrichshaus/team-members-block', array(
            'render_callback' => [Theme_Gutenberg_Block_Callback::class, 'callback_team_members_block_type'],
            'editor_script' => 'team-member-gutenberg-block',
        ));

    }

    public function team_members_block_type_scripts(): void
    {
        $plugin_asset = require get_stylesheet_directory() . '/inc/Gutenberg/Block/build/index.asset.php';

        wp_enqueue_script(
            'team-member-gutenberg-block',
            get_stylesheet_directory_uri() . '/inc/Gutenberg/Block/build/index.js',
            $plugin_asset['dependencies'], $plugin_asset['version'], true
        );

        wp_enqueue_style(
            'team-member-gutenberg-block',
            get_stylesheet_directory_uri() . '/inc/Gutenberg/Block/build/index.css', array(), 'v124');
    }

    /**
     * Register TAM MEMBERS REGISTER GUTENBERG BLOCK TYPE
     *
     * @since    1.0.0
     */
    public function register_leistungen_block_type()
    {
        register_block_type('ulrichshaus/leistungen-block', array(
            'render_callback' => [Theme_Gutenberg_Block_Callback::class, 'callback_leistungen_block_type'],
            'editor_script' => 'leistungen-gutenberg-block',
        ));

    }

    public function leistungen_block_type_scripts(): void
    {
        $plugin_asset = require get_stylesheet_directory() . '/inc/Gutenberg/Leistungen-Block/build/index.asset.php';

        wp_enqueue_script(
            'leistungen-gutenberg-block',
            get_stylesheet_directory_uri() . '/inc/Gutenberg/Leistungen-Block/build/index.js',
            $plugin_asset['dependencies'], $plugin_asset['version'], true
        );

        wp_enqueue_style(
            'leistungen-gutenberg-block',
            get_stylesheet_directory_uri() . '/inc/Gutenberg/Leistungen-Block/build/index.css', array(), 'v124');
    }

    public function theme_posts_meta_fields():void
    {
        register_meta(
            'post',
            '_team_name',
            array(
                'type' => 'string',
                'object_subtype' => 'team',
                'single' => true,
                'show_in_rest' => true,
                'default' => '',
                'auth_callback' => array($this, 'theme_post_permissions_check')
            )
        );
        register_meta(
            'post',
            '_team_position',
            array(
                'type' => 'string',
                'object_subtype' => 'team',
                'single' => true,
                'show_in_rest' => true,
                'default' => '',
                'auth_callback' => array($this, 'theme_post_permissions_check')
            )
        );
        register_meta(
            'post',
            '_team_arbeitsgebiet1',
            array(
                'type' => 'string',
                'object_subtype' => 'team',
                'single' => true,
                'show_in_rest' => true,
                'default' => '',
                'auth_callback' => array($this, 'theme_post_permissions_check')
            )
        );
        register_meta(
            'post',
            '_team_arbeitsgebiet2',
            array(
                'type' => 'string',
                'object_subtype' => 'team',
                'single' => true,
                'show_in_rest' => true,
                'default' => '',
                'auth_callback' => array($this, 'theme_post_permissions_check')
            )
        );
        register_meta(
            'post',
            '_team_arbeitsgebiet3',
            array(
                'type' => 'string',
                'object_subtype' => 'team',
                'single' => true,
                'show_in_rest' => true,
                'default' => '',
                'auth_callback' => array($this, 'theme_post_permissions_check')
            )
        );
        register_meta(
            'post',
            '_team_arbeitsgebiet4',
            array(
                'type' => 'string',
                'object_subtype' => 'team',
                'single' => true,
                'show_in_rest' => true,
                'default' => '',
                'auth_callback' => array($this, 'theme_post_permissions_check')
            )
        );
        register_meta(
            'post',
            '_team_arbeitsgebiet5',
            array(
                'type' => 'string',
                'object_subtype' => 'team',
                'single' => true,
                'show_in_rest' => true,
                'default' => '',
                'auth_callback' => array($this, 'theme_post_permissions_check')
            )
        );
        register_meta(
            'post',
            '_team_arbeitsgebiet6',
            array(
                'type' => 'string',
                'object_subtype' => 'team',
                'single' => true,
                'show_in_rest' => true,
                'default' => '',
                'auth_callback' => array($this, 'theme_post_permissions_check')
            )
        );

        register_meta(
            'post',
            '_team_lebenslauf',
            array(
                'type'              => 'boolean',
                'object_subtype'    => 'team',
                'single'            => true,
                'show_in_rest'      => true,
                'default'           => 0,
                'auth_callback'     => array($this, 'theme_post_permissions_check')
            )
        );
    }

    /**
     * Check if a given request has access.
     *
     * @return bool
     */
    public function theme_post_permissions_check(): bool
    {
        return current_user_can('edit_posts');
    }
}
