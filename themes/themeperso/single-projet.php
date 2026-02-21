<?php

/**
 * Template Name: Single Photo Page
 */

get_header(); 

?>

<section class="single_photo_section">
    <div class="photo_container">
        <?php while ( have_posts() ) : the_post(); ?>
            <div class="photo_informations">
                <?php the_title( '<h2 class="entry-title">', '</h2>' ); //the_title ≠ get_the_title bc inclus echo  ?> 
                <p>CADRE : <?php the_field('cadreprojet'); ?></p>
                <p>POUR : <?php the_field('clientprojet'); ?></p>

                <?php 
                $terms_competence = get_the_terms( get_the_ID(), 'competence' );
                // 1. Vérification de l'existence des termes et de l'absence d'erreur
                if ( ! is_wp_error( $terms_competence ) && ! empty( $terms_competence ) ) : 
                    // 2. Vérification et affichage de la Compétence 1 (Index 0)
                    if ( isset( $terms_competence[0] ) ) : ?>
                        <p>COMPÉTENCE 1 : <?php echo esc_html( $terms_competence[0]->name ); ?></p>
                    <?php endif; 
                    // 3. Vérification et affichage de la Compétence 2 (Index 1)
                    if ( isset( $terms_competence[1] ) ) : ?>
                        <p>COMPÉTENCE 2 : <?php echo esc_html( $terms_competence[1]->name ); ?></p>
                    <?php endif; 
                endif; 
                ?>


                <p>OBJECTIF : <?php the_field('objectifprojet'); ?></p>
                <?php 
                    // --- RÉCUPÉRATION DE LA TAXONOMIE ANNÉE ---
                    $terms_annee = get_the_terms( get_the_ID(), 'annee' );
                    if ( ! empty( $terms_annee ) && ! is_wp_error( $terms_annee ) ) : ?>
                        <p>ANNÉE : <?php echo esc_html( $terms_annee[0]->name ); ?></p>
                <?php endif; ?>

                <div class="description_container">
                    <h3>DESCRIPTION</h3>
                    <p><?php the_field('descriptionprojet'); ?></p>
                </div>
            </div>

            <?php if ( has_post_thumbnail() ) : ?>
                <div class="photo_showcase">
                    <?php the_post_thumbnail( 'full' ); // Formats possibles 'thumbnail', 'medium', 'large' ou 'full' ?>
                </div>
            <?php else : ?>
                <img src="<?php echo get_template_directory_uri(); ?>/assets/default_img.png" alt="Image par défaut">
            <?php endif; ?>

            <?php endwhile;  ?>
    </div>

    <div class="other_projets_section">
        <h3>Projets apparentés </h2>
        <div class="other_projets_container">
            <?php 
        //I. CONFIGURATION DES VARIABLES
        // Récupération des posts précédent et suivant dans la même catégorie dans des variables
            $next_post_showcase = get_next_post( true, '', 'competence' );
            $prev_post_showcase = get_previous_post( true, '', 'competence' );

        //Déclaration des derniers recours possibles en bouclant les posts
            // Le tout premier post (le plus ancien)
                //1. récupérer le premier post publié = au 1er en partant de l'ordre croissant
                $first_post_query = get_posts(array(
                    'post_type' => 'projet', 
                    'posts_per_page' => 1, 
                    'order' => 'ASC'
                ));
                //2. vérifier qu'on a bien un post et l'assigner à la variable [le n° 0 du tableau renvoyé par get_posts, CAD le 1er du début]
                if(!empty($first_post_query)) {
                    $absolute_first = $first_post_query[0];
                } else {
                    $absolute_first = null;
                }

            // Le tout dernier post (le plus récent)
                //1. récupérer le dernier post publié = au 1er en partant de l'ordre décroissant
                $last_post_query = get_posts(array(
                    'post_type' => 'projet', 
                    'posts_per_page' => 1, 
                    'order' => 'DESC'
                ));
                //2. vérifier qu'on a bien un post et l'assigner à la variable [le n° 0 du tableau renvoyé par get_posts, CAD le 1er de la fin]
                if(!empty($last_post_query)){
                    $absolute_last = $last_post_query[0];
                } else {
                    $absolute_last = null ;
                }

            //Attribution du post à la variable du post suivant en testant les différents cas possibles
                //Si pas de post dans la même catégorie, on cherche dans la taxonomie année
                if (empty($next_post_showcase)){
                    $next_post_showcase = get_next_post( true, '', 'annee' );
                }
                    //Si pas de post dans la même format non plus, on cherche dans le post suivant sans filtre
                        if (empty($next_post_showcase)){
                            $next_post_showcase = get_next_post( false );
                        }
                            //Si pas de post suivant, on cherche dans le post précédent sans filtre
                            if (empty($next_post_showcase)){
                                $next_post_showcase = $absolute_first;
                            }
            

            //Même chose pour le post précédent
            if (empty($prev_post_showcase)){
                $prev_post_showcase = get_previous_post( true, '', 'annee' );
            }
                if (empty($prev_post_showcase)){
                    $prev_post_showcase = get_previous_post( false );
                }
                    if (empty($prev_post_showcase)){
                        $prev_post_showcase = $absolute_last;
                    }
        //II. ATTRIBUTION DES LIENS ET IMAGES VIA VARIABLES                
        //Attribution des liens et des images aux variables
            if ( !empty($next_post_showcase) ) {
                $next_url_showcase = get_permalink($next_post_showcase->ID);
                $next_thumb_showcase = get_the_post_thumbnail_url($next_post_showcase->ID, 'full');
            }
            if ( !empty($prev_post_showcase) ) {
                $prev_url_showcase = get_permalink($prev_post_showcase->ID);
                $prev_thumb_showcase = get_the_post_thumbnail_url($prev_post_showcase->ID, 'full');
            }
        //III. AFFICHAGE DES IMAGES ET LIENS
            ?>
            <a href="<?php echo $prev_url_showcase; ?>" class="other_projet_item">
                <img src="<?php echo $prev_thumb_showcase; ?>" alt="Projet 1">
            </a>
            <a href="<?php echo $next_url_showcase; ?>" class="other_projet_item">
                <img src="<?php echo $next_thumb_showcase;?>" alt="Projet 2">
            </a>
        </div>
    </div>
</section>


<?php get_footer(); ?>