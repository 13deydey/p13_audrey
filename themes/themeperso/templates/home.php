<?php

/**
 * Template Name: Home Page
 */

get_header(); 

?>

<section id="hero_section" class="hero_section">
    <hgroup class="hero_header">
        <?php while ( have_posts() ) : the_post(); ?>
            <h1 class="hero_title"><?php the_field('herotitle'); ?></h1>
            <h2 class="hero_slogan"><?php the_field('heroslogan'); ?></h2>
            <img src="<?php the_field('heroimg'); ?>" />
        <?php endwhile; // end of the loop. ?>
    </hgroup>
</section><!-- #primary -->

<section id="galerie_section" class="galerie_section"> 
    <div class="galerie_filtre">
        <div class="filter_gauche">
            <div class="competence_filter">
                <select id="competenceSelect">
                    <option value="" class="competence_option" selected disabled>Compétences</option>
                    <?php
                    $competences = get_terms( array(
                        'taxonomy' => 'competence',
                        'orderby'  => 'name',
                        'order'    => 'ASC',
                        'hide_empty' => true,
                    ) );

                    foreach ( $competences as $competence ) {
                        echo '<option value="' . esc_attr( $competence->term_id ) . '">' . esc_html( $competence->name ) . '</option>';
                    }
                    ?>
                </select>
            </div>

        </div>
        <div class="filter_droite">
                <select id="anneeSelect">
                    <option value="" class="annee_option" selected disabled>Années</option>
                    <?php
                    $annees = get_terms( array(
                        'taxonomy' => 'annee',
                        'orderby'  => 'name',
                        'order'    => 'ASC',
                        'hide_empty' => true,
                    ) );

                    foreach ( $annees as $annee ) {
                        echo '<option value="' . esc_attr( $annee->term_id ) . '">' . esc_html( $annee->name ) . '</option>';
                    }
                    ?>
                </select>
        </div>

    </div>

    <div class="galerie_photos" id="gallery">
    </div>

    <button
        class="load_more_button"
        data-nonce="<?php echo wp_create_nonce('galerie_load_more'); ?>"
        data-action="galerie_load_more"
        data-ajaxurl="<?php echo admin_url( 'admin-ajax.php' ); ?>"
        >Charger plus
    </button>   

</section> <!-- #galerie_section -->
    
<?php get_footer(); ?>