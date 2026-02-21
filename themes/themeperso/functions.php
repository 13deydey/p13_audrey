<?php
function mon_theme_enqueue_styles() {
    //1. ENQUEUE MAIN THEME STYLE
    wp_enqueue_style(
        'theme-id-style',  // Handle: identifiant unique
        get_stylesheet_uri(), // Chemin: style.css à la racine
        array(),
        wp_get_theme()->get('Version')
    );

    //2. ENQUEUE FONTS 
    wp_enqueue_style(
        'theme-fonts',
        get_template_directory_uri() . '/css/font.css',
        array('theme-id-style'),
        '1.0'
    );

    //3. ENQUEUE MAIN CSS
    wp_enqueue_style(
        'theme-main-css',
        get_template_directory_uri() . '/css/main.css',
        array('theme-fonts'),
        '1.0'
    );
}
add_action('wp_enqueue_scripts', 'mon_theme_enqueue_styles');

function mon_theme_register_nav_menus() {
    //Enregistre les emplacements de menus personnalisés
        register_nav_menus(
            array(
                'primary' => __( 'Menu Principal', 'themeperso' ), 
                'footer'  => __( 'Menu Pied de Page', 'themeperso' )  
            )
        );
    }
add_action( 'after_setup_theme', 'mon_theme_register_nav_menus' );

//LOGO UPLOAD VIA WP CUSTOMIZER
//ajoute le support du logo personnalisé
function mon_theme_setup() {
    add_theme_support( 'custom-logo', array(
        'height'      => 75, // Hauteur maximale recommandée pour le logo
        'width'       => 150, // Largeur maximale recommandée pour le logo
        'flex-height' => true,
        'flex-width'  => true,
        'header-text' => array( 'site-title', 'site-description' ),
    ) );
}
add_action( 'after_setup_theme', 'mon_theme_setup' );

function mon_theme_enqueue_scripts() {
    wp_enqueue_script('mon-theme-script', get_template_directory_uri() . '/js/script.js', array('jquery'), '1.0', true);

    // Cette fonction rend l'URL Ajax accessible dans le JS sous l'objet 'monThemeAjax'
    wp_localize_script('mon-theme-script', 'monThemeAjax', array(
        'ajaxurl' => admin_url('admin-ajax.php'),
        'rest_url' => esc_url_raw( rest_url() ),
        'nonce'   => wp_create_nonce('mon_theme_contact_nonce') // Optionnel: pour plus de sécurité
    ));
}
add_action('wp_enqueue_scripts', 'mon_theme_enqueue_scripts');
