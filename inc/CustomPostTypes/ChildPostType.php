<?php


namespace Hupa\StarterThemeV2;

defined('ABSPATH') or die();

class ChildPostType
{
    //INSTANCE
    private static $instance;

    /**
     * @return static
     */
    public static function init(): self
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function team_post_type() {
        register_post_type(
            'team',
            array(
                'labels' => array(
                    'name' => __('Ulrichshaus Team', 'bootscore'),
                    'singular_name' => __('Ulrichshaus Team', 'bootscore'),
                    'menu_name' => __('Ulrichshaus Team', 'bootscore'),
                    'parent_item_colon' => __('Parent Item:', 'bootscore'),
                    'edit_item' => __('Bearbeiten', 'bootscore'),
                    'update_item' => __('Aktualisieren', 'bootscore'),
                    'all_items' => __('Alle Teams', 'bootscore'),
                    'items_list_navigation' => __('Team Posts navigation', 'bootscore'),
                    'add_new_item' => __('Add new post', 'bootscore'),
                    'archives' => __('Team Posts Archives', 'bootscore'),
                ),
                'public' => true,
                'publicly_queryable' => true,
                'show_in_rest' => true,
                'show_ui' => true,
                'show_in_menu' => true,
                'has_archive' => true,
                'query_var' => true,
                'show_in_nav_menus' => true,
                'exclude_from_search' => false,
                'hierarchical' => false,
                'capability_type' => 'post',
                'menu_icon' => 'dashicons-admin-users',
                'menu_position' => 199,
                'can_export' => true,
                'show_in_admin_bar' => true,
                'supports' => array(
                    'title', 'excerpt', 'page-attributes', 'editor', 'thumbnail', 'custom-fields'
                ),
                'taxonomies' => array('team_category'),
            )
        );
    }

    public function team_taxonomies() {
        $labels = array(
            'name' => __('Team-Kategorien', 'bootscore'),
            'singular_name' => __('Team-Kategorie', 'bootscore'),
            'search_items' => __('Suche Team-Kategorie', 'bootscore'),
            'all_items' => __('Alle Team-Kategorien', 'bootscore'),
            'parent_item' => __('Übergeordnete Kategorie', 'bootscore'),
            'parent_item_colon' => __('Übergeordnete Kategorie:', 'bootscore'),
            'edit_item' => __('Team-Kategorie bearbeiten', 'bootscore'),
            'update_item' => __('Team-Kategorie aktualisieren', 'bootscore'),
            'add_new_item' => __('Neue Team-Kategorie hinzufügen', 'bootscore'),
            'new_item_name' => __('Neue Team-Kategorie', 'bootscore'),
            'menu_name' => __('Team-Kategorie', 'bootscore'),
        );

        $args = array(
            'labels' => $labels,
            'hierarchical' => true,
            'public' => false,
            'show_ui' => true,
            'sort' => true,
            'show_in_rest' => true,
            'query_var' => true,
            'args' => array('orderby' => 'term_order'),
            'show_admin_column' => true,
            'publicly_queryable' => true,
            'show_in_nav_menus' => true,

        );
        register_taxonomy('team_category', array('attachment', 'team'), $args);

        $terms = [
            '0' => [
                'name' => 'Allgemein',
                'slug' => 'allgemein'
            ]
        ];

        foreach ($terms as $term) {
            if (!term_exists($term['name'], 'team_category')) {
                wp_insert_term(
                    $term['name'],
                    'team_category',
                    array(
                        'description' => 'Team Kategorie',
                        'slug' => $term['slug']
                    )
                );
            }
        }
    }

    public function leistungen_post_type() {
        register_post_type(
            'leistungen',
            array(
                'labels' => array(
                    'name' => __('Leistung', 'bootscore'),
                    'singular_name' => __('Leistungen', 'bootscore'),
                    'menu_name' => __('Leistungen', 'bootscore'),
                    'parent_item_colon' => __('Parent Item:', 'bootscore'),
                    'edit_item' => __('Bearbeiten', 'bootscore'),
                    'update_item' => __('Aktualisieren', 'bootscore'),
                    'all_items' => __('Alle Leistungen', 'bootscore'),
                    'items_list_navigation' => __('Leistungen Posts navigation', 'bootscore'),
                    'add_new_item' => __('Add new post', 'bootscore'),
                    'archives' => __('Leistungen Posts Archives', 'bootscore'),
                ),
                'public' => true,
                'publicly_queryable' => true,
                'show_in_rest' => true,
                'show_ui' => true,
                'show_in_menu' => true,
                'has_archive' => true,
                'query_var' => true,
                'show_in_nav_menus' => true,
                'exclude_from_search' => false,
                'hierarchical' => false,
                'capability_type' => 'post',
                'menu_icon' => 'dashicons-media-document',
                'menu_position' => 200,
                'can_export' => true,
                'show_in_admin_bar' => true,
                'supports' => array(
                    'title', 'excerpt', 'page-attributes', 'editor', 'thumbnail', 'custom-fields'
                ),
                'taxonomies' => array('leistungen_category'),
            )
        );
    }

    public function leistungen_taxonomies() {
        $labels = array(
            'name' => __('Leistungen-Kategorien', 'bootscore'),
            'singular_name' => __('Leistung-Kategorie', 'bootscore'),
            'search_items' => __('Suche Leistung-Kategorie', 'bootscore'),
            'all_items' => __('Alle Leistung-Kategorien', 'bootscore'),
            'parent_item' => __('Übergeordnete Kategorie', 'bootscore'),
            'parent_item_colon' => __('Übergeordnete Kategorie:', 'bootscore'),
            'edit_item' => __('Leistung-Kategorie bearbeiten', 'bootscore'),
            'update_item' => __('Leistung-Kategorie aktualisieren', 'bootscore'),
            'add_new_item' => __('Neue Leistungen-Kategorie hinzufügen', 'bootscore'),
            'new_item_name' => __('Neue Leistung-Kategorie', 'bootscore'),
            'menu_name' => __('Leistung-Kategorie', 'bootscore'),
        );

        $args = array(
            'labels' => $labels,
            'hierarchical' => true,
            'public' => false,
            'show_ui' => true,
            'sort' => true,
            'show_in_rest' => true,
            'query_var' => true,
            'args' => array('orderby' => 'term_order'),
            'show_admin_column' => true,
            'publicly_queryable' => true,
            'show_in_nav_menus' => true,

        );
        register_taxonomy('leistungen_category', array('attachment', 'leistungen'), $args);

        $terms = [
            '0' => [
                'name' => 'Leistungen',
                'slug' => 'leistungen'
            ]
        ];

        foreach ($terms as $term) {
            if (!term_exists($term['name'], 'leistungen_category')) {
                wp_insert_term(
                    $term['name'],
                    'leistungen_category',
                    array(
                        'description' => 'Leistungen Kategorie',
                        'slug' => $term['slug']
                    )
                );
            }
        }
    }
}