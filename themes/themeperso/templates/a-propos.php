<?php

/**
 * Template Name: A Propos Page
 */

get_header(); 

?>

<section class="a_propos_section">
    <div class="presentation_container">
        <div class="presentation_img">
            <img src="<?php the_field('presentationimg'); ?>" alt="Photo de présentation">
        </div>
        <div class="presentation_text">
            <h2> <?php the_field('presentationtitle'); ?> </h2>
            <div class="formation_perso">
                <div class="job1">
                    <h3> <?php the_field('presentationjob1'); ?> </h3> 
                    <p> STATUT : <?php the_field('statutjob1'); ?> </p>
                </div>
                <div class="job2">
                    <h3> <?php the_field('presentationjob2'); ?> </h3> 
                    <p> STATUT : <?php the_field('statutjob2'); ?> </p>
                </div>
            </div>
            <div class="presentation_bio">
                <p>
                    Basée en Loire-Atlantique, je suis une passionnée du web qui aime faire le pont entre le code et l'image.
                    Actuellement en fin de formation de Développeuse Web spécialisée WordPress, j'ai choisi cet écosystème pour sa puissance et sa flexibilité. 
                    Mon objectif ? Transformer des lignes de code en expériences utilisateurs fluides, intuitives et surtout, performantes. 
                </p>
            </div>
        </div>
    </div>

    <div class="presentation_division">
        <div class="adn_design">
            <h3>Le design comme ADN</h3>
            <p>
            Avant d'écrire ma première ligne de PHP, j'ai appris à dompter les pixels. 
            Graphiste autodidacte, j'ai développé un regard affûté pour l'esthétique et l'ergonomie. 
            Cette double casquette me permet de comprendre les enjeux d'une identité visuelle 
            tout en sachant exactement comment l'intégrer techniquement.
            </p>
        </div>
        <div class="adn_code">
            <h3>Le code comme langage</h3>
            <p>
            Le code est pour moi un langage de création, un moyen de donner vie à des idées. 
            J'aime la logique et la rigueur qu'il impose, mais aussi la liberté qu'il offre pour innover. 
            Chaque projet est une nouvelle aventure où je peux expérimenter, apprendre et repousser les limites du possible.
            </p>
        </div>
    </div>

    <div class="travaillons_ensemble">
        <h2>Travaillons ensemble</h2>
        <p class="travaillons_text">
            En tant que développeuse web, je suis convaincue que la collaboration est la clé du succès. 
            J'aspire à travailler en étroite collaboration avec mes clients pour comprendre leurs besoins et leurs objectifs, 
            afin de créer des solutions sur mesure qui répondent à leurs attentes.
        </p>
        <div class="details_travaillons">
            <ul class="travaillons_cta">
                <h3> Pourquoi me choisir ? </h3>
                <li> CURIOSITÉ INSATIABLE : <br> Le web bouge vite, et moi aussi. Je suis toujours en veille sur les dernières technos et tendances design. </li>
                <li> GOÛT DU DÉFI : <br> Un bug persistant ? Une fonctionnalité complexe à intégrer ? C’est ce qui me motive à me dépasser. </li>
                <li> ÉNERGIE & DYNAMISME : <br> Je m'investis à 100% dans chaque projet, avec la rigueur d'une développeuse et l'enthousiasme d'une créative. </li>
            </ul>
            <button class="contact_button" id="contactButton">Contactez-moi</button>
        </div>
    </div>

</section>

<?php get_footer(); ?>