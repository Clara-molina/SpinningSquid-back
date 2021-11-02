<?php

namespace SpinningSquid;

use SpinningSquid\Models\NewsLetterCustomerModel;
use WP_REST_Request;
use WP_USer;

class API
{

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
            '/newskatepark-save',
            [
                'methods' => 'post',
                'callback' => [$this, 'skateparkSave']
            ]
        );

        register_rest_route(
            'spinningsquid/v1',
            '/newsletter',
            [
                'methods' => 'post',
                'callback' => [$this, 'newsLetter']
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

        //$userID = wp_create_user($username, $password, $email);


        $userData = [
            'user_login' => $username,
            'first_name' => $firstname,
            'last_name' => $lastname,
            'user_pass' => $password,
            'user_email' => $email,
        ];


        $userID = wp_insert_user($userData);

        if (is_int($userID)) {

            $user = new WP_User($userID);

            $user->set_role('contributor');

            add_user_meta($userID, 'street', $street);
            add_user_meta($userID, 'zipcode', $zipcode);
            add_user_meta($userID, 'city', $city);

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

        $username = $request->get_param('username');
        $lastname = $request->get_param('lastname');
        $firstname = $request->get_param('firstname');
        $street = $request->get_param('street');
        $zipcode = $request->get_param('zipcode');
        $city = $request->get_param('city');
        $email = $request->get_param('email');
        $password = $request->get_param('password');

        $userData = array(
            'user_login' => $username,
            'first_name' => $firstname,
            'last_name' => $lastname,
            'street ' => $street,
            'zipcode' => $zipcode,
            'city' => $city,
            'user_email' => $email,
            'user_pass' => $password,
            'user_email' => $email,
        );

        update_user_meta($userID, $userData, false);
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
        $skatepark = $request->get_param('skatepark');
        $pumptrack = $request->get_param('pumptrack');
        $street = $request->get_param('street');
        $streetspot = $request->get_param('streetspot');
        $zipcode = $request->get_param('zipcode');
        $city = $request->get_param('city');
        $parking = $request->get_param('parking');
        $water = $request->get_param('water');
        $trashcan = $request->get_param('trashcan');
        $lighting = $request->get_param('lighting');
        $table = $request->get_param('table');
        $benche = $request->get_param('benche');
        $state = $request->get_param('state');

        //image est envoyé par le front en base64
        $image = $request->get_param('image');

        $user = wp_get_current_user();

        // Je vérie que l'user a le bon rôle (donc bien inscrit)
      
            $skateparkCreateResult = wp_insert_post(
                [
                    'post_title' => $title,
                    'post_status' => 'publish',
                    'post_type' => 'skatepark'
                ]
            );

            // Si le post skatepark est créé alors...
            if (is_int($skateparkCreateResult)) {

                // J'ajoute les meta data
                update_post_meta($skateparkCreateResult, 'skatepark', $skatepark);
                update_post_meta($skateparkCreateResult, 'pumptrack', $pumptrack);
                update_post_meta($skateparkCreateResult, 'streetspot', $streetspot);
                update_post_meta($skateparkCreateResult, 'street', $street);
                update_post_meta($skateparkCreateResult, 'zipcode', $zipcode);
                update_post_meta($skateparkCreateResult, 'city', $city);
                update_post_meta($skateparkCreateResult, 'parking', $parking);
                update_post_meta($skateparkCreateResult, 'water', $water);
                update_post_meta($skateparkCreateResult, 'trashcan', $trashcan);
                update_post_meta($skateparkCreateResult, 'lighting', $lighting);
                update_post_meta($skateparkCreateResult, 'table', $table);
                update_post_meta($skateparkCreateResult, 'benche', $benche);
                update_post_meta($skateparkCreateResult, 'state', $state);        

                // Je récupère la base64 et le type de l'image
                list($type, $data) = explode(';', $image);
                list(, $data)      = explode(',', $data);
                list(, $type) = explode('/', $type);

            
                // Si l'image a le bont type alors...
                if (!in_array($type, ['jpg', 'jpeg','png'])) {
                    echo "nop!";
                } else {
                    echo "yes!";
                    $dataDecoded = base64_decode($data);
                    //$datajson = $dataDecoded;

                }
            
                // nom de mon image
                $name = $title . '-' . uniqid() . $type;
                // nom de mon image (sans l'extension)
                $filename = basename( $name );
                // je demande à WP les chemins de téléchargement 
                $upload_dir = wp_upload_dir();

                // si il n'existe pas, WP va me créer un dossier (ici uploads/2021/)
                if ( wp_mkdir_p( $upload_dir['path'] ) ) {
                    $file = $upload_dir['path'] . '/' . $filename;
                }
                else {
                    $file = $upload_dir['basedir'] . '/' . $filename;
                }
                
                // Je reconstruit mon image
                file_put_contents( $file, $dataDecoded );

                $attachment = array(
                //'guid'=> $upload_dir['url'] . '/' . basename($name),
                'post_mime_type' => "image/{$type}",
                'post_title' => 'test',
                'post_content' => '',
                'post_status' => 'inherit'
                );

                $image_id = wp_insert_attachment($attachment, $file, $skateparkCreateResult);

                // Make sure that this file is included, as wp_generate_attachment_metadata() depends on it.
                require_once(ABSPATH . 'wp-admin/includes/image.php');
                // Generate the metadata for the attachment, and update the database record.
                $attach_data = wp_generate_attachment_metadata($image_id, $file);
                wp_update_attachment_metadata($image_id, $attach_data);

                return [
                    'succes' => true,
                    //'data' => $datajson
                 ];
            }

        return [
            'succes' => false,
        ];
    }

    // Modifier un skatepark
    public function updateSkatepark(WP_REST_Request $request)
    {

        $title = $request->get_param('title');
        $skatepark = $request->get_param('skatepark');
        $pumptrack = $request->get_param('pumptrack');
        $street = $request->get_param('street');
        $streetspot = $request->get_param('streetspot');
        $zipcode = $request->get_param('zipcode');
        $city = $request->get_param('city');
        $parking = $request->get_param('parking');
        $water = $request->get_param('water');
        $trashcan = $request->get_param('trashcan');
        $lighting = $request->get_param('lighting');
        $table = $request->get_param('table');
        $benche = $request->get_param('benche');
        $state = $request->get_param('state');

        //image est envoyé par le front en base64
        $image = $request->get_param('image');

        $user = wp_get_current_user();

        // Je vérie que l'user a le bon rôle (donc bien inscrit)
      
            $skateparkCreateResult = wp_insert_post(
                [
                    'post_title' => $title,
                    'post_status' => 'publish',
                    'post_type' => 'skatepark'
                ]
            );

            // Si le post skatepark est créé alors...
            if (is_int($skateparkCreateResult)) {

                // J'ajoute les meta data
                update_post_meta($skateparkCreateResult, 'skatepark', $skatepark);
                update_post_meta($skateparkCreateResult, 'pumptrack', $pumptrack);
                update_post_meta($skateparkCreateResult, 'streetspot', $streetspot);
                update_post_meta($skateparkCreateResult, 'street', $street);
                update_post_meta($skateparkCreateResult, 'zipcode', $zipcode);
                update_post_meta($skateparkCreateResult, 'city', $city);
                update_post_meta($skateparkCreateResult, 'parking', $parking);
                update_post_meta($skateparkCreateResult, 'water', $water);
                update_post_meta($skateparkCreateResult, 'trashcan', $trashcan);
                update_post_meta($skateparkCreateResult, 'lighting', $lighting);
                update_post_meta($skateparkCreateResult, 'table', $table);
                update_post_meta($skateparkCreateResult, 'benche', $benche);
                update_post_meta($skateparkCreateResult, 'state', $state);        

                // Je récupère la base64 et le type de l'image
                list($type, $data) = explode(';', $image);
                list(, $data)      = explode(',', $data);
                list(, $type) = explode('/', $type);

            
                // Si l'image a le bont type alors...
                if (!in_array($type, ['jpg', 'jpeg','png'])) {
                    echo "nop!";
                } else {
                    echo "yes!";
                    $dataDecoded = base64_decode($data);
                    //$datajson = $dataDecoded;

                }
            
                // nom de mon image
                $name = $title . '-' . uniqid() . $type;
                // nom de mon image (sans l'extension)
                $filename = basename( $name );
                // je demande à WP les chemins de téléchargement 
                $upload_dir = wp_upload_dir();

                // si il n'existe pas, WP va me créer un dossier (ici uploads/2021/)
                if ( wp_mkdir_p( $upload_dir['path'] ) ) {
                    $file = $upload_dir['path'] . '/' . $filename;
                }
                else {
                    $file = $upload_dir['basedir'] . '/' . $filename;
                }
                
                // Je reconstruit mon image
                file_put_contents( $file, $dataDecoded );

                $attachment = array(
                //'guid'=> $upload_dir['url'] . '/' . basename($name),
                'post_mime_type' => "image/{$type}",
                'post_title' => 'test',
                'post_content' => '',
                'post_status' => 'inherit'
                );

                $image_id = wp_insert_attachment($attachment, $file, $skateparkCreateResult);

                // Make sure that this file is included, as wp_generate_attachment_metadata() depends on it.
                require_once(ABSPATH . 'wp-admin/includes/image.php');
                // Generate the metadata for the attachment, and update the database record.
                $attach_data = wp_generate_attachment_metadata($image_id, $file);
                wp_update_attachment_metadata($image_id, $attach_data);

                return [
                    'succes' => true,
                    //'data' => $datajson
                 ];
            }

        return [
            'succes' => false,
        ];
    }

    // Suppprimer un skatepark 
    public function deleteSkatepark(WP_REST_Request $request)
    {
        $id = $request->get_param('id');

        wp_delete_post($id);
    }

}


    // Save email in table custom newsletter
    public function newsLetter(WP_REST_Request $request)
    {
        $email = $request->get_param('email');

        if($email) {
            $newsletterModel =   new NewsLetterCustomerModel();
            $newsletterModel->insert($email);

            return [
                'succes' => true,
                'email' => $email
            ];
        }
    }
}

