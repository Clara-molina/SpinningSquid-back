<?php

namespace SpinningSquid;

class Plugin
{
    public function __construct()
    {
        add_action('init',[$this,'createArticlePostType']);
        add_action('init',[$this,'createSkateparkPostType']);
        add_action('init',[$this,'createSalePostType']);
    }

    //Méthode créant un CPT : Post (forum)
    public function createArticlePostType()
    {
        register_post_type('article', // nom du cpt 
            [
<<<<<<< HEAD
                'label' => 'Article',
=======
                'label' => 'Article Forum',
>>>>>>> Dev
                'public' => true,
                'hierarchical' => false,
                'menu_icon' => 'dashicons-welcome-write-blog',
                'supports' => [ 
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
    public function createSkateparkPostType()
    {
        register_post_type('skatepark',
            [
                'label' => 'Skatepark',
                'public' => true,
                'hierarchical' => false,
                'menu_icon' => 'dashicons-location-alt',
                'supports' => [ 
                    'title',
                    'thumbnail',
                    'editor',
                    'author',
                    'excerpt',
                    'comments'    
                ],
                'capability_type' => 'post',
                'map_meta_cap' => true,
                'show_in_rest' => true
            ]
        );
    }

    //Méthode créant un CPT : Sale 
    public function createSalePostType()
    {
        register_post_type('sale',
            [
                'label' => 'Sale',
                'public' => true,
                'hierarchical' => false,
                'menu_icon' => 'dashicons-cart',
                'supports' => [ 
                    'title',
                    'thumbnail',
                    'editor',
                    'author',
                    'excerpt',
                    'comments'    
                ],
                'capability_type' => 'post',
                'map_meta_cap' => true,
                'show_in_rest' => true
            ]
        );
    }
    
}