<?php

namespace SpinningSquid;

use WP_REST_Request;
use WP_USer;

class API {

    public function __construct()
    {
        add_action('rest_api_init', [$this, 'initialize']);
    }

    // Créer une url custom pour accéder à notre API
    public function initialize()
    {

        register_rest_route(
            'spinningsquid/v1',
            '/newuser-save',
            [
                'methods' => 'post',
                'callback' => [$this, 'newUSerSave']
            ]
        );

        register_rest_route(
            'spinningsquid/v1',
            '/updateuser',
            [
                'methods' => 'post',
                'callback' => [$this, 'updateUser']
            ]
        );

        register_rest_route(
            'spinningsquid/v1',
            '/deleteuser',
            [
                'methods' => 'post',
                'callback' => [$this, 'deleteUser']
            ]
        );

        register_rest_route(
            'spinningsquid/v1',
            '/newskatepark-save',
            [
                'methods' => 'post',
                'callback' => [$this, 'newSkateparkSave']
            ]
        );
    }

    // Sauvegarder un nouvel utilisateur 
    public function newUSerSave(WP_REST_Request $request)
    {
        $username = $request->get_param('username'); 
        $lastname = $request->get_param('lastname');
        $firstname = $request->get_param('firstname');
        $street = $request->get_param('street');
        $zipcode = $request->get_param('zipcode');
        $city = $request->get_param('city');
        $email = $request->get_param('email');
        $password = $request->get_param('password');

        $userID = wp_create_user($username, $password, $email);

        if(is_int($userID)) {

            new WP_User($userID);

            return [
                'succes' => true,
                'userID' => $userID,
                'username' => $username,
                'email' => $email,
            ];
        } else {

            return [
                'succes' => false
            ];
        }
    }

    // Modifier un utilisateur existant 
    public function updateUser(WP_REST_Request $request)
    {
        $userID = get_current_user_id();

        $firstname = $request->get_param('firstname'); 
        $lastname = $request->get_param('lastname'); 
        $email = $request->get_param('email');
        $password = $request->get_param('password');
        $address = $request->get_param('address');

        $userData = array(
            'first_name' => $firstname,
            'last_name' => $lastname,
            'user_email' => $email,
            'user_pass' => $password,
        );

        update_user_meta($userID, $userData, false);

        add_user_meta($userID, 'address', $address);
    }

    // Supprimer un utilisateur 
    public function deleteUser()
    {
        $userID = get_current_user_id();

        wp_delete_user($userID);
    }

    // Ajouter un skatepark 
    public function skateparkSave(WP_REST_Request $request)
    {
        
        $title = $request->get_param('title'); 
        $description = $request->get_param('description');

        $user = wp_get_current_user();
        if(
            in_array('contributor', (array) $user->roles) ||
            in_array('administrator', (array) $user->roles))
        {

            $skateparkCreateResult = wp_insert_post(
                [
                    'post_title' => $title,
                    'post_content' => $description,
                    'post_status' => 'publish',
                    'post_type' => 'skatepark'
                ]
            );

            return [
                'success' => true,
                'title' => $title,
                'description' => $description,
                'user' => $user,
                'recipe-id'=> $skateparkCreateResult
            ];
        }

        return [
            'success' => false,
        ];
    }

    // Modifier un skatepark
    public function updateSkatepark(WP_REST_Request $request)
    {
        
        $title = $request->get_param('title'); 
        $description = $request->get_param('description');
        $id = $request->get_param('id');

        $user = wp_get_current_user();
        if(
            in_array('contributor', (array) $user->roles) ||
            in_array('administrator', (array) $user->roles))
        {

            update_post_meta($id, 'post_title', $title);
            update_post_meta($id, 'post_content', $description);
        
            return [
                'success' => true,
            ];
        }

        return [
            'success' => false,
        ];
    }

    // Suppprimer un skatepark 
    public function deleteSkatepark(WP_REST_Request $request)
    {
        $id = $request->get_param('id');

        wp_delete_post($id);
    }
}