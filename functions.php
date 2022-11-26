<?php
	// style and scripts
	 add_action( 'wp_enqueue_scripts', 'bootscore_5_child_enqueue_styles' );
	 function bootscore_5_child_enqueue_styles() {
         // style.css
         wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
         // child-style.css
         wp_enqueue_style( 'starter-v2-child-style', get_stylesheet_directory_uri() . '/assets/css/child-style.css' );
         // custom.js
         wp_enqueue_script('custom-js', get_stylesheet_directory_uri() . '/assets/js/custom.js', false, '', true);
         wp_enqueue_script('bootscore-script', get_stylesheet_directory_uri() . '/assets/js/lib/theme.js', false, '', true);
     } 
