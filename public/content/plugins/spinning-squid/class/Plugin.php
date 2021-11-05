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
        add_action('init',[$this,'createUserCustomData']);
        add_filter('rest_user_query',[$this, 'showAllUsers']);
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
            'description'    => 'spot type',
            'single'         => true,
            'show_in_rest'   => true,
        ]);
        register_meta('post', 'pumptrack', [
            'object_subtype' => 'skatepark', 
            'type'           => 'boolean',
            'description'    => 'spot type',
            'single'         => true,
            'show_in_rest'   => true,
        ]);
        register_meta('post', 'streetspot', [
            'object_subtype' => 'skatepark', 
            'type'           => 'boolean',
            'description'    => 'spot type',
            'single'         => true,
            'show_in_rest'   => true,
        ]);
        register_meta('post', 'street', [
            'object_subtype' => 'skatepark', 
            'type'           => 'string',
            'description'    => 'skatepark street',
            'single'         => true,
            'show_in_rest'   => true,
        ]);
        register_meta('post', 'zipcode', [
            'object_subtype' => 'skatepark', 
            'type'           => 'string',
            'description'    => 'skatepark zipcode',
            'single'         => true,
            'show_in_rest'   => true,
        ]);
        register_meta('post', 'city', [
            'object_subtype' => 'skatepark', 
            'type'           => 'string',
            'description'    => 'skatepark city',
            'single'         => true,
            'show_in_rest'   => true,
        ]);
        register_meta('post', 'latitude', [
            'object_subtype' => 'skatepark', 
            'type'           => 'integer',
            'description'    => 'skatepark latitude',
            'single'         => true,
            'show_in_rest'   => true,
        ]);
        register_meta('post', 'longitude', [
            'object_subtype' => 'skatepark', 
            'type'           => 'integer',
            'description'    => 'skatepark longitude',
            'single'         => true,
            'show_in_rest'   => true,
        ]);
        register_meta('post', 'parking', [
            'object_subtype' => 'skatepark', 
            'type'           => 'boolean',
            'description'    => 'skatepark equipement',
            'single'         => true,
            'show_in_rest'   => true,
        ]);
        register_meta('post', 'water', [
            'object_subtype' => 'skatepark', 
            'type'           => 'boolean',
            'description'    => 'skatepark equipement',
            'single'         => true,
            'show_in_rest'   => true,
        ]);
        register_meta('post', 'trashcan', [
            'object_subtype' => 'skatepark', 
            'type'           => 'boolean',
            'description'    => 'skatepark equipement',
            'single'         => true,
            'show_in_rest'   => true,
        ]);
        register_meta('post', 'lighting', [
            'object_subtype' => 'skatepark', 
            'type'           => 'boolean',
            'description'    => 'skatepark equipement',
            'single'         => true,
            'show_in_rest'   => true,
        ]);
        register_meta('post', 'table', [
            'object_subtype' => 'skatepark', 
            'type'           => 'boolean',
            'description'    => 'skatepark equipement',
            'single'         => true,
            'show_in_rest'   => true,
        ]);
        register_meta('post', 'benche', [
            'object_subtype' => 'skatepark', 
            'type'           => 'boolean',
            'description'    => 'skatepark equipement',
            'single'         => true,
            'show_in_rest'   => true,
        ]);
        register_meta('post', 'state', [
            'object_subtype' => 'skatepark', 
            'type'           => 'string',
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

    public function addCapAdmin()
    {   //add capabilities for CPT skatepark
        $role = get_role('administrator');
        $role->add_cap('delete_others_skatepark');
        $role->add_cap('delete_private_skatepark');
        $role->add_cap('delete_published_skatepark');
        $role->add_cap('delete_skatepark');
        $role->add_cap('edit_others_skatepark');
        $role->add_cap('edit_private_skatepark');
        $role->add_cap('edit_published_skatepark');
        $role->add_cap('edit_skatepark');
        $role->add_cap('publish_skatepark');
        $role->add_cap('read_private_skatepark');

        //add capabilities for CPT sale
        $role->add_cap('delete_others_sale');
        $role->add_cap('delete_private_sale');
        $role->add_cap('delete_published_sale');
        $role->add_cap('delete_sale');
        $role->add_cap('edit_others_sale');
        $role->add_cap('edit_private_sale');
        $role->add_cap('edit_published_sale');
        $role->add_cap('edit_sale');
        $role->add_cap('publish_sale');
        $role->add_cap('read_private_sale');

        //add capabilities for CPT article
        $role->add_cap('delete_others_article');
        $role->add_cap('delete_private_article');
        $role->add_cap('delete_published_article');
        $role->add_cap('delete_article');
        $role->add_cap('edit_others_article');
        $role->add_cap('edit_private_article');
        $role->add_cap('edit_published_article');
        $role->add_cap('edit_article');
        $role->add_cap('publish_article');
        $role->add_cap('read_private_article');
    }

    public function addCapContributor()
    {   // add capabilities for Contributor
        $role = get_role('contributor');
        $role->add_cap('create_posts');
        $role->add_cap('publish_posts');
    }

    // Add function at plugin activation
    /**
     * Méthode permettant d'acceder à tous les user via l'appel à l'API 
     * 
     * Removes `has_published_posts` from the query args so even users who have not
     * published content are returned by the request.
     *
     * @see https://developer.wordpress.org/reference/classes/wp_user_query/
     *
     * @param array           $prepared_args Array of arguments for WP_User_Query.
     * @param WP_REST_Request $request       The current request.
     *
     * @return array
     */
    public function showAllUsers($prepared_args)
    {
        unset($prepared_args['has_published_posts']);

        return $prepared_args;
    }

    // Méthode permettant d'acceder au meta data d'un user via l'appel à l'API
    public function createUserCustomData()
    {
        register_meta('user', 'username', [
            'type'           => 'string',
            'description'    => 'user username',
            'single'         => true,
            'show_in_rest'   => true,
        ]);
        register_meta('user', 'firstname', [
            'type'           => 'string',
            'description'    => 'user firstname',
            'single'         => true,
            'show_in_rest'   => true,
        ]);
        register_meta('user', 'lastname', [
            'type'           => 'string',
            'description'    => 'user lastname',
            'single'         => true,
            'show_in_rest'   => true,
        ]);
        register_meta('user', 'street', [
            'type'           => 'string',
            'description'    => 'user street',
            'single'         => true,
            'show_in_rest'   => true,
        ]);
        register_meta('user', 'zipcode', [
            'type'           => 'string',
            'description'    => 'user zipcode',
            'single'         => true,
            'show_in_rest'   => true,
        ]);
        register_meta('user', 'city', [
            'type'           => 'string',
            'description'    => 'user city',
            'single'         => true,
            'show_in_rest'   => true,
        ]);
        register_meta('user', 'email', [
            'type'           => 'string',
            'description'    => 'user email',
            'single'         => true,
            'show_in_rest'   => true,
        ]);
    }

    public function activate()
    {
        // Make custom table newsletter
        $projectCustomerModel = new NewsLetterCustomerModel();
        $projectCustomerModel->createTable();
        // Add capabilities
        $this->addCapAdmin();
        $this->addCapContributor();
    }
    // Add function at plugin deactivation
    public function deactivate()
    {

    }
    
}