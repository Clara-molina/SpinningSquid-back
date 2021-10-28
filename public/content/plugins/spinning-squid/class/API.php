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
            '/newsletter-save',
            [
                'methods' => 'post',
                'callback' => [$this, 'newsLetterSave']
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

    // Save email in table custom newsletter
    public function newsLetterSave(WP_REST_Request $request)
    {
        $email = $request->get_param('username');

        return $email;
    }
}