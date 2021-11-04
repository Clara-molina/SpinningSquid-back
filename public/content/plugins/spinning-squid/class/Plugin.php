<?php

namespace SpinningSquid;

use SpinningSquid\Models\NewsLetterCustomerModel;

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
                'label' => 'Article Forum',
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
                    'comments',
                    'custom-fields'   
                ],
                'capability_type' => 'post',
                'map_meta_cap' => true,
                'show_in_rest' => true
            ]
        );

        register_meta('post', 'skatepark', [
            'object_subtype' => 'skatepark', 
            'type'           => 'boolean',
            'description'    => 'skatepark city',
            'single'         => true,
            'show_in_rest'   => true,
        ]);
        register_meta('post', 'pumptrack', [
            'object_subtype' => 'skatepark', 
            'type'           => 'boolean',
            'description'    => 'skatepark city',
            'single'         => true,
            'show_in_rest'   => true,
        ]);
        register_meta('post', 'streetspot', [
            'object_subtype' => 'skatepark', 
            'type'           => 'boolean',
            'description'    => 'skatepark city',
            'single'         => true,
            'show_in_rest'   => true,
        ]);
        register_meta('post', 'zipcode', [
            'object_subtype' => 'skatepark', 
            'type'           => 'string',
            'description'    => 'skatepark city',
            'single'         => true,
            'show_in_rest'   => true,
        ]);
        register_meta('post', 'latitude', [
            'object_subtype' => 'skatepark', 
            'type'           => 'string',
            'description'    => 'skatepark city',
            'single'         => true,
            'show_in_rest'   => true,
        ]);
        register_meta('post', 'longitude', [
            'object_subtype' => 'skatepark', 
            'type'           => 'string',
            'description'    => 'skatepark city',
            'single'         => true,
            'show_in_rest'   => true,
        ]);
        register_meta('post', 'parking', [
            'object_subtype' => 'skatepark', 
            'type'           => 'boolean',
            'description'    => 'skatepark city',
            'single'         => true,
            'show_in_rest'   => true,
        ]);
        register_meta('post', 'water', [
            'object_subtype' => 'skatepark', 
            'type'           => 'boolean',
            'description'    => 'skatepark city',
            'single'         => true,
            'show_in_rest'   => true,
        ]);
        register_meta('post', 'trashcan', [
            'object_subtype' => 'skatepark', 
            'type'           => 'boolean',
            'description'    => 'skatepark city',
            'single'         => true,
            'show_in_rest'   => true,
        ]);
        register_meta('post', 'lighting', [
            'object_subtype' => 'skatepark', 
            'type'           => 'boolean',
            'description'    => 'skatepark city',
            'single'         => true,
            'show_in_rest'   => true,
        ]);
        register_meta('post', 'table', [
            'object_subtype' => 'skatepark', 
            'type'           => 'boolean',
            'description'    => 'skatepark city',
            'single'         => true,
            'show_in_rest'   => true,
        ]);
        register_meta('post', 'benche', [
            'object_subtype' => 'skatepark', 
            'type'           => 'boolean',
            'description'    => 'skatepark city',
            'single'         => true,
            'show_in_rest'   => true,
        ]);
        register_meta('post', 'state', [
            'object_subtype' => 'skatepark', 
            'type'           => 'boolean',
            'description'    => 'skatepark city',
            'single'         => true,
            'show_in_rest'   => true,
        ]);
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

    public function activate()
    {
        // création de la table NewsLetter_customer
        $projectCustomerModel = new NewsLetterCustomerModel();
        $projectCustomerModel->createTable();
    }

    public function deactivate()
    {
        // suppression de la table NewsLetter_customer
        //$projectCustomerModel = new NewsLetterCustomerModel();
        //$projectCustomerModel->dropTable();
    }
    
}