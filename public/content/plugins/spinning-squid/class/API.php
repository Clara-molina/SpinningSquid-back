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
    }

    // Sauvegarde un nouvel utilisateur 
    public function newUSerSave(WP_REST_Request $request)
    {
        $username = $request->get_param('username'); 
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

        update_user_meta($userID, $userData, true);

        add_user_meta($userID, 'address', $address);
    }

    // Supprimer un utilisateur 
    public function deleteUser()
    {
        $userID = get_current_user_id();

        wp_delete_user($userID);
    }

    // Ajouter un skatepark 

    // Modifier un skatepark

    // Suppprimer un skatepark 
}