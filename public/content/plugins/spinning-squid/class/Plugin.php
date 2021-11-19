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
        add_filter( 'rest_skatepark_query', [$this, 'post_meta_request_params'], 99, 2);
        add_filter( 'rest_sale_query', [$this, 'post_meta_request_params'], 99, 2);
        add_filter( 'rest_article_query', [$this, 'post_meta_request_params'], 99, 2);

        add_action('add_meta_boxes',[$this,'init_metabox']);
        add_action('save_post',[$this,'save_metabox']);

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
                    'comments',
                    'custom-fields'   
                ],
                'capability_type' => 'post',
                'map_meta_cap' => true,
                'show_in_rest' => true // Permet d'afficher le CPT dans l'API
            ]
        );

        register_meta('post', 'date', [
            'object_subtype' => 'article', 
            'type'           => 'string',
            'description'    => 'date of event',
            'single'         => true,
            'show_in_rest'   => true,
        ]);
        register_meta('post', 'place', [
            'object_subtype' => 'article', 
            'type'           => 'string',
            'description'    => 'place of event',
            'single'         => true,
            'show_in_rest'   => true,
        ]);
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
            'type'           => 'number',
            'description'    => 'skatepark latitude',
            'single'         => true,
            'show_in_rest'   => true,
        ]);
        register_meta('post', 'longitude', [
            'object_subtype' => 'skatepark', 
            'type'           => 'number',
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
                    'comments',
                    'custom-fields'    
                ],
                'capability_type' => 'post',
                'map_meta_cap' => true,
                'show_in_rest' => true
            ]
        );
        register_meta('post', 'place', [
            'object_subtype' => 'sale', 
            'type'           => 'string',
            'description'    => 'place of saler',
            'single'         => true,
            'show_in_rest'   => true,
        ]);
        register_meta('post', 'price', [
            'object_subtype' => 'sale', 
            'type'           => 'integer',
            'description'    => 'price of sale item',
            'single'         => true,
            'show_in_rest'   => true,
        ]);
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
        register_meta('user', 'avatar', [
            'type'           => 'string',
            'description'    => 'user avatar',
            'single'         => true,
            'show_in_rest'   => true,
        ]);
    }

    // Méthode permettant de faire des appels à l'API en prenant des meta data en paramètre
    // https://maheshwaghmare.com/search-post-by-post-meta-with-rest-api/
    public function post_meta_request_params( $args, $request )
    {
        $args += [
			'meta_key'   => $request['meta_key'],
			'meta_value' => $request['meta_value'],
			'meta_query' => $request['meta_query'],
        ];

	    return $args;
    }

    public function init_metabox(){
      add_meta_box('info_skatepark', 'Informations sur le skatepark', [$this,'info_skatepark'], 'skatepark');
      add_meta_box('info_sale', 'Informations sur le vente', [$this,'info_sale'], 'sale');
      add_meta_box('info_article', "Informations sur l'évenement", [$this,'info_article'], 'article');
    }
    
    public function info_skatepark($post){

      $skatepark      = get_post_meta($post->ID,'skatepark',true);
      $pumptrack   = get_post_meta($post->ID,'pumptrack',true);
      $streetspot = get_post_meta($post->ID,'streetspot',true);
      $zipcode  = get_post_meta($post->ID,'zipcode',true);
      $street  = get_post_meta($post->ID,'street',true);
      $city     = get_post_meta($post->ID,'city',true);
      $latitude      = get_post_meta($post->ID,'latitude',true);
      $longitude      = get_post_meta($post->ID,'longitude',true);
      $parking      = get_post_meta($post->ID,'parking',true);
      $water      = get_post_meta($post->ID,'water',true);
      $trashcan      = get_post_meta($post->ID,'trashcan',true);
      $lighting      = get_post_meta($post->ID,'lighting',true);
      $table      = get_post_meta($post->ID,'table',true);
      $benche      = get_post_meta($post->ID,'benche',true);
      $state      = get_post_meta($post->ID,'state',true);

      ?>
      <br>
      <div>
        <label><input id="" type="checkbox" check( $skatepark, true ) name="skatepark" value=true <?php if ($skatepark == true) {echo 'checked';}?> />Skatepark</label>
        <label><input id="" type="checkbox" check( $pumptrack, true ) name="pumptrack" value=true <?php if ($pumptrack == true) {echo 'checked';}?> />Pumptrack</label>
        <label><input id="" type="checkbox" check( $streetspot, true ) name="streetspot" value=true <?php if ($streetspot == true) {echo 'checked';}?> />StreetSpot</label>
      </div>
      <br>
      <div>
        <input id="" style="width: 50px;" type="text" name="zipcode" value="<?php echo $zipcode; ?>" placeholder="zipcode"/>
        <input id="" type="text" name="street" value="<?php echo $street; ?>" placeholder="street"/>
        <input id="" type="text" name="city" value="<?php echo $city; ?>" placeholder="city"/>
        <input id="" type="number" name="latitude" value="<?php echo $latitude; ?>" placeholder="latitude"/>
        <input id="" type="number" name="longitude" value="<?php echo $longitude; ?>" placeholder="longitude"/>
      </div>
      <br>
      <div>
        <label><input id="" type="checkbox" check( $parking, true ) name="parking" value=true <?php if ($parking == true) {echo 'checked';}?>/>Parking</label>
        <label><input id="" type="checkbox" check( $water, true ) name="water" value=true <?php if ($water == true) {echo 'checked';}?> />Water</label>
        <label><input id="" type="checkbox" check( $trashcan, true ) name="trashcan" value=true <?php if ($trashcan == true) {echo 'checked';}?>/>Trashcan</label>
        <label><input id="" type="checkbox" check( $lighting, true ) name="lighting" value=true <?php if ($lighting == true) {echo 'checked';}?>/>Lighting</label>
        <label><input id="" type="checkbox" check( $table, true ) name="table" value=true <?php if ($table == true) {echo 'checked';}?>/>Table</label>
        <label><input id="" type="checkbox" check( $benche, true ) name="benche" value=true <?php if ($benche == true) {echo 'checked';}?>/>Benche</label>
      </div>
      <br>
      <div>
          <select name="state">
              <option value=null> - Etat - </option>
              <option selected( 'New', $state, false ) value="New">New</option>
              <option selected( 'Good', $state, false ) value="Good">Good</option>
              <option selected( 'Way', $state, false ) value="Way">Way</option>
              <option selected( 'Endoflife', $state, false ) value="Endoflife">End of life</option>
          </select>
      </div>
      <?php 
    }

    public function info_sale($post){

        $place      = get_post_meta($post->ID,'place',true);
        $price   = get_post_meta($post->ID,'price',true);

        ?>
        <br>
        <div>
          <input id="" type="text" name="place" value="<?php echo $place; ?>" />
          <input id="" style="width: 70px;" type="number" name="price" value="<?php echo $price; ?>" />
        </div>
        <br>
        <?php 
    }

      public function info_article($post){

        $date      = get_post_meta($post->ID,'date',true);
        $place   = get_post_meta($post->ID,'place',true);

        ?>
        <br>
        <div>
          <input id="" type="text" name="date" value="<?php echo $date; ?>" />
          <input id="" type="text" name="place" value="<?php echo $place; ?>" />
        </div>
        <br>
        <?php 
    }
    
    //A MODIFIER ! MEESSAGE ERREUR PHP A L'ENVOI DU FORMULAIRE
    public function save_metabox($post_id){

        //For Skatepark Post
        update_post_meta($post_id, 'skatepark', sanitize_text_field($_POST['skatepark']));
        update_post_meta($post_id, 'pumptrack', sanitize_text_field($_POST['pumptrack']));
        update_post_meta($post_id, 'streetspot', sanitize_text_field($_POST['streetspot']));
        
        if(isset($_POST['zipcode'])){
            update_post_meta($post_id, 'zipcode', sanitize_text_field($_POST['zipcode']));
        }
        if(isset($_POST['street'])){
            update_post_meta($post_id, 'street', sanitize_text_field($_POST['street']));
        }
        if(isset($_POST['city'])){
            update_post_meta($post_id, 'city', sanitize_text_field($_POST['city']));
        }

        update_post_meta($post_id, 'parking', sanitize_text_field($_POST['parking']));
        update_post_meta($post_id, 'water', sanitize_text_field($_POST['water']));
        update_post_meta($post_id, 'trashcan', sanitize_text_field($_POST['trashcan']));
        update_post_meta($post_id, 'lighting', sanitize_text_field($_POST['lighting']));
        update_post_meta($post_id, 'table', sanitize_text_field($_POST['table']));
        update_post_meta($post_id, 'benche', sanitize_text_field($_POST['benche']));

        if(isset($_POST['state'])){
            update_post_meta($post_id, 'state', sanitize_text_field($_POST['state']));
        }

        //For Sale Post
        if(isset($_POST['place'])){
            update_post_meta($post_id, 'place', sanitize_text_field($_POST['place']));
        }
        if(isset($_POST['price'])){
            update_post_meta($post_id, 'price', sanitize_text_field($_POST['price']));
        }

        //For Article Post
        if(isset($_POST['date'])){
            update_post_meta($post_id, 'date', sanitize_text_field($_POST['date']));
        }
        if(isset($_POST['place'])){
            update_post_meta($post_id, 'place', sanitize_text_field($_POST['place']));
        }
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