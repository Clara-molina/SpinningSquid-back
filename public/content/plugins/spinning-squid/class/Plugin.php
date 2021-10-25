<?php

namespace SpinningSquid;

class Plugin
{
    public function __construct()
    {
        add_action('init',[$this,'createArticlePostType']);
    }

    //Méthode créant un CPT : Post (forum)
    public function createArticlePostType()
    {
        register_post_type('article', // nom du cpt 
            [
                'label' => 'Article',
                'public' => true,
                'hierarchical' => false,
                'menu_icon' => 'dashicons-welcome-write-blog',
                'supports' => [ // je définis les fonctionnalités dont va bénéficier le CPT
                    'title',
                    'thumbnail',
                    'editor',
                    'author',
                    'excerpt',
                    'comments' //! ATTENTION il faut activer la "feature" COMMENTS coté BO    
                ],
                'capability_type' => 'post',
                'map_meta_cap' => true,
                'show_in_rest' => true // Permet d'afficher le CPT dans l'API
            ]
        );
    }

    //Méthode créant un CPT : SkatePark 

    //Méthode créant un CPT : Sale 
    
}