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
            '/user-edit',
            [
                'methods' => 'post',
                'callback' => [$this, 'updateUser']
            ]
        );

        register_rest_route(
            'spinningsquid/v1',
            '/user-delete',
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
                'callback' => [$this, 'skateparkSave']
            ]
        );

        register_rest_route(
            'spinningsquid/v1',
            '/skatepark-edit',
            [
                'methods' => 'post',
                'callback' => [$this, 'updateSkatepark']
            ]
        );

        register_rest_route(
            'spinningsquid/v1',
            '/skatepark-delete',
            [
                'methods' => 'post',
                'callback' => [$this, 'deleteSkatepark']
            ]
        );

        register_rest_route(
            'spinningsquid/v1',
            '/add-sale',
            [
                'methods' => 'post',
                'callback' => [$this, 'addSale']
            ]
        );

        register_rest_route(
            'spinningsquid/v1',
            '/update-sale',
            [
                'methods' => 'post',
                'callback' => [$this, 'updateSale']
            ]
        );

        register_rest_route(
            'spinningsquid/v1',
            '/delete-sale',
            [
                'methods' => 'post',
                'callback' => [$this, 'deleteSale']
            ]
        );
        register_rest_route(
            'spinningsquid/v1',
            '/add-article',
            [
                'methods' => 'post',
                'callback' => [$this, 'addArticle']
            ]
        );

        register_rest_route(
            'spinningsquid/v1',
            '/update-article',
            [
                'methods' => 'post',
                'callback' => [$this, 'updateArticle']
            ]
        );

        register_rest_route(
            'spinningsquid/v1',
            '/delete-article',
            [
                'methods' => 'post',
                'callback' => [$this, 'deleteArticle']
            ]
        );

        register_rest_route(
            'spinningsquid/v1',
            '/save-comments',
            [
                'methods' => 'post',
                'callback' => [$this, 'commentSave']
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

        if (username_exists($username)) {
            return [
                'succes' => 'this username already exist'
            ];
        }

        $userID = wp_create_user($username, $password, $email);

        if (is_int($userID)) {
            $user = new WP_User($userID);

            $user->set_role('contributor');
            add_user_meta($userID, 'username', $username);
            add_user_meta($userID, 'lastname', $lastname);
            add_user_meta($userID, 'firstname', $firstname);
            add_user_meta($userID, 'street', $street);
            add_user_meta($userID, 'zipcode', $zipcode);
            add_user_meta($userID, 'city', $city);
            add_user_meta($userID, 'email', $email);

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
        $username = $request->get_param('username');
        $lastname = $request->get_param('lastname');
        $firstname = $request->get_param('firstname');
        $street = $request->get_param('street');
        $zipcode = $request->get_param('zipcode');
        $city = $request->get_param('city');
        $email = $request->get_param('email');
        $password = $request->get_param('password');
        $image = $request->get_param('image');

        $user = get_user_by('slug', $username);
        $userID = $user->ID;

        $userDataKey = array(
            'firstname' => $firstname,
            'lastname' => $lastname,
            'street ' => $street,
            'zipcode' => $zipcode,
            'city' => $city,
            'email' => $email,
        );

        foreach ($userDataKey as $key => $value) {
            update_user_meta($userID, $key, $value);
        }
        wp_set_password($password, $userID);

        // Je récupère la base64 et le type de l'image
        list($type, $data) = explode(';', $image);
        list(, $data)      = explode(',', $data);
        list(, $type) = explode('/', $type);

        // Si l'image a le bont type alors...
        if (!in_array($type, ['jpg', 'jpeg', 'png'])) {
            echo "nop!";
        } else {
            echo "yes!";
            $dataDecoded = base64_decode($data);
            //$datajson = $dataDecoded;
        }

        // nom de mon image
        $userImageID = uniqid();
        $name = $userImageID . $type;
        // nom de mon image (sans l'extension)
        $filename = basename($name);
        // je demande à WP les chemins de téléchargement 
        $upload_dir = wp_upload_dir();

        // si il n'existe pas, WP va me créer un dossier (ici uploads/2021/)
        if (wp_mkdir_p($upload_dir['path'])) {
            $file = $upload_dir['path'] . '/' . $filename;
        } else {
            $file = $upload_dir['basedir'] . '/' . $filename;
        }

        // Je reconstruit mon image
        file_put_contents($file, $dataDecoded);


        $attachment = array(
            //'guid'=> $upload_dir['url'] . '/' . basename($name),
            'post_mime_type' => "image/{$type}",
            'post_author' => $userID,
            'post_type' => 'attachment',
            'post_status' => 'inherit'
        );

        $image_id = wp_insert_attachment($attachment, $file);

        // Make sure that this file is included, as wp_generate_attachment_metadata() depends on it.
        require_once(ABSPATH . 'wp-admin/includes/image.php');
        // Generate the metadata for the attachment, and update the database record.
        $attach_data = wp_generate_attachment_metadata($image_id, $file);
        wp_update_attachment_metadata($image_id, $attach_data);

        update_user_meta($userID, 'avatar', $image_id);
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
        $latitude = $request->get_param('latitude');
        $longitude = $request->get_param('longitude');
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
        if (
            in_array('contributor', (array) $user->roles) ||
            in_array('administrator', (array) $user->roles)
        ) {
            $skateparkCreateResult = wp_insert_post(
                [
                    'post_title' => $title,
                    'post_author' => $user->ID,
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
                update_post_meta($skateparkCreateResult, 'latitude', $latitude);
                update_post_meta($skateparkCreateResult, 'longitude', $longitude);
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
                if (!in_array($type, ['jpg', 'jpeg', 'png'])) {
                    echo "nop!";
                } else {
                    echo "yes!";
                    $dataDecoded = base64_decode($data);
                    //$datajson = $dataDecoded;
                }

                // nom de mon image
                $userImageID = uniqid();
                $name = $userImageID . $type;
                // nom de mon image (sans l'extension)
                $filename = basename($name);
                // je demande à WP les chemins de téléchargement
                $upload_dir = wp_upload_dir();

                // si il n'existe pas, WP va me créer un dossier (ici uploads/2021/)
                if (wp_mkdir_p($upload_dir['path'])) {
                    $file = $upload_dir['path'] . '/' . $filename;
                } else {
                    $file = $upload_dir['basedir'] . '/' . $filename;
                }

                // Je reconstruit mon image
                file_put_contents($file, $dataDecoded);

                $attachment = array(
                    //'guid'=> $upload_dir['url'] . '/' . basename($name),
                    'post_mime_type' => "image/{$type}",
                    'post_title' => $title,
                    'post_content' => '',
                    'post_status' => 'inherit'
                );

                $image_id = wp_insert_attachment($attachment, $file, $skateparkCreateResult);

                // Make sure that this file is included, as wp_generate_attachment_metadata() depends on it.
                require_once(ABSPATH . 'wp-admin/includes/image.php');
                // Generate the metadata for the attachment, and update the database record.
                $attach_data = wp_generate_attachment_metadata($image_id, $file);
                wp_update_attachment_metadata($image_id, $attach_data);

                // Ajout de l'image d'en-tête
                set_post_thumbnail($skateparkCreateResult, $image_id);

                return [
                    'succes' => true,
                    'title' => $title,
                    //'data' => $datajson
                ];
            }
        }

        return [
            'succes' => false,
            'informations' => 'user is not connected',
            'user' => $user->ID
        ];
    }

    // Modifier un skatepark
    public function updateSkatepark(WP_REST_Request $request)
    {
        $id = $request->get_param('id');
        $title = $request->get_param('title');
        $skatepark = $request->get_param('skatepark');
        $pumptrack = $request->get_param('pumptrack');
        $street = $request->get_param('street');
        $streetspot = $request->get_param('streetspot');
        $zipcode = $request->get_param('zipcode');
        $city = $request->get_param('city');
        $latitude = $request->get_param('latitude');
        $longitude = $request->get_param('longitude');
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

        if (
            in_array('contributor', (array) $user->roles) ||
            in_array('administrator', (array) $user->roles)
        ) {
            $postItem = get_post($id);

            if (!$user->ID == $postItem->post_author) {
                return [
                    'succes' => 'not allowed'
                ];
            }

            $skateparkCreateResult = wp_insert_post(
                [
                    'ID' => $id,
                    'post_title' => $title,
                    'post_author' => $user->ID,
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
                update_post_meta($skateparkCreateResult, 'latitude', $latitude);
                update_post_meta($skateparkCreateResult, 'longitude', $longitude);
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
                if (!in_array($type, ['jpg', 'jpeg', 'png'])) {
                    echo "nop!";
                } else {
                    echo "yes!";
                    $dataDecoded = base64_decode($data);
                    //$datajson = $dataDecoded;
                }

                // nom de mon image
                $name = $title . '-' . uniqid() . $type;
                // nom de mon image (sans l'extension)
                $filename = basename($name);
                // je demande à WP les chemins de téléchargement
                $upload_dir = wp_upload_dir();

                // si il n'existe pas, WP va me créer un dossier (ici uploads/2021/)
                if (wp_mkdir_p($upload_dir['path'])) {
                    $file = $upload_dir['path'] . '/' . $filename;
                } else {
                    $file = $upload_dir['basedir'] . '/' . $filename;
                }

                // Je reconstruit mon image
                file_put_contents($file, $dataDecoded);

                $attachment = array(
                    //'guid'=> $upload_dir['url'] . '/' . basename($name),
                    'post_mime_type' => "image/{$type}",
                    'post_title' => $title,
                    'post_content' => '',
                    'post_status' => 'inherit'
                );

                $image_id = wp_insert_attachment($attachment, $file, $skateparkCreateResult);

                // Make sure that this file is included, as wp_generate_attachment_metadata() depends on it.
                require_once(ABSPATH . 'wp-admin/includes/image.php');
                // Generate the metadata for the attachment, and update the database record.
                $attach_data = wp_generate_attachment_metadata($image_id, $file);
                wp_update_attachment_metadata($image_id, $attach_data);

                // Ajout de l'image d'en-tête
                set_post_thumbnail($skateparkCreateResult, $image_id);

                return [
                    'succes' => true,
                    //'data' => $datajson
                ];
            }
        }

        return [
            'succes' => false,
            'informations' => 'user is not connected',
            'user' => $user->ID
        ];
    }

    // Suppprimer un skatepark
    public function deleteSkatepark(WP_REST_Request $request)
    {
        $id = $request->get_param('id');
        $user = wp_get_current_user();

        if (
            in_array('contributor', (array) $user->roles) ||
            in_array('administrator', (array) $user->roles)
        ) {

            $postItem = get_post($id);

            if (!$user->ID == $postItem->post_author) {
                return [
                    'succes' => 'not allowed'
                ];
            } else {

                wp_delete_post($id);

                return [
                    'succes' => true
                ];
            }
            
        } else {

            return [
                'succes' => false,
                'informations' => 'user is not connected',
                'user' => $user->ID
            ];
        }
    }

    // Ajouter un post sale
    public function addSale(WP_REST_Request $request)
    {
        $title = $request->get_param('title');
        $place = $request->get_param('place');
        $price = $request->get_param('price');
        $description = $request->get_param('content');
        $image = $request->get_param('image');

        $user = wp_get_current_user();

        // Je vérie que l'user a le bon rôle (donc bien inscrit)
        if (
            in_array('contributor', (array) $user->roles) ||
            in_array('administrator', (array) $user->roles)
        ) {
            $addSaleResult = wp_insert_post(
                [
                    'post_title' => $title,
                    'post_author' => $user->ID,
                    'post_content' => $description,
                    'post_status' => 'publish',
                    'post_type' => 'sale'
                ]
            );

            if (is_int($addSaleResult)) {

                update_post_meta($addSaleResult, 'place', $place);
                update_post_meta($addSaleResult, 'price', $price);

                // Je récupère la base64 et le type de l'image
                list($type, $data) = explode(';', $image);
                list(, $data)      = explode(',', $data);
                list(, $type) = explode('/', $type);


                // Si l'image a le bont type alors...
                if (!in_array($type, ['jpg', 'jpeg', 'png'])) {
                    echo "nop!";
                } else {
                    echo "yes!";
                    $dataDecoded = base64_decode($data);
                    //$datajson = $dataDecoded;
                }

                // nom de mon image
                $userImageID = uniqid();
                $name = $userImageID . $type;
                // nom de mon image (sans l'extension)
                $filename = basename($name);
                // je demande à WP les chemins de téléchargement
                $upload_dir = wp_upload_dir();

                // si il n'existe pas, WP va me créer un dossier (ici uploads/2021/)
                if (wp_mkdir_p($upload_dir['path'])) {
                    $file = $upload_dir['path'] . '/' . $filename;
                } else {
                    $file = $upload_dir['basedir'] . '/' . $filename;
                }

                // Je reconstruit mon image
                file_put_contents($file, $dataDecoded);

                $attachment = array(
                    //'guid'=> $upload_dir['url'] . '/' . basename($name),
                    'post_mime_type' => "image/{$type}",
                    'post_title' => $title,
                    'post_content' => '',
                    'post_status' => 'inherit'
                );

                $image_id = wp_insert_attachment($attachment, $file, $addSaleResult);

                // Make sure that this file is included, as wp_generate_attachment_metadata() depends on it.
                require_once(ABSPATH . 'wp-admin/includes/image.php');
                // Generate the metadata for the attachment, and update the database record.
                $attach_data = wp_generate_attachment_metadata($image_id, $file);
                wp_update_attachment_metadata($image_id, $attach_data);

                // Ajout de l'image d'en-tête
                set_post_thumbnail($addSaleResult, $image_id);


                return [
                    'success' => true,
                    'title' => $title,
                    //'data' => $datajson
                ];
            }
        }
        return [
            'succes' => false,
            'informations' => 'user is not connected',
            'user' => $user->ID
        ];
    }

    // Modifier un post sale
    public function updateSale(WP_REST_Request $request)
    {
        $id = $request->get_param('id');
        $title = $request->get_param('title');
        $place = $request->get_param('place');
        $price = $request->get_param('price');
        $description = $request->get_param('content');
        $image = $request->get_param('image');

        $user = wp_get_current_user();

        // Je vérie que l'user a le bon rôle (donc bien inscrit)
        if (
            in_array('contributor', (array) $user->roles) ||
            in_array('administrator', (array) $user->roles)
        ) {
            $postItem = get_post($id);

            if (!$user->ID == $postItem->post_author) {
                return [
                    'succes' => 'not allowed'
                ];
            }
            
            $addSaleResult = wp_update_post(
                [
                    'ID' => $id,
                    'post_title' => $title,
                    'post_author' => $user->ID,
                    'post_content' => $description,
                    'post_status' => 'publish',
                    'post_type' => 'sale'
                ]
            );

            if (is_int($addSaleResult)) {

                update_post_meta($addSaleResult, 'place', $place);
                update_post_meta($addSaleResult, 'price', $price);


                // Je récupère la base64 et le type de l'image
                list($type, $data) = explode(';', $image);
                list(, $data)      = explode(',', $data);
                list(, $type) = explode('/', $type);


                // Si l'image a le bont type alors...
                if (!in_array($type, ['jpg', 'jpeg', 'png'])) {
                    echo "nop!";
                } else {
                    echo "yes!";
                    $dataDecoded = base64_decode($data);
                    //$datajson = $dataDecoded;
                }

                // nom de mon image
                $userImageID = uniqid();
                $name = $userImageID . $type;
                // nom de mon image (sans l'extension)
                $filename = basename($name);
                // je demande à WP les chemins de téléchargement
                $upload_dir = wp_upload_dir();

                // si il n'existe pas, WP va me créer un dossier (ici uploads/2021/)
                if (wp_mkdir_p($upload_dir['path'])) {
                    $file = $upload_dir['path'] . '/' . $filename;
                } else {
                    $file = $upload_dir['basedir'] . '/' . $filename;
                }

                // Je reconstruit mon image
                file_put_contents($file, $dataDecoded);

                $attachment = array(
                    //'guid'=> $upload_dir['url'] . '/' . basename($name),
                    'post_mime_type' => "image/{$type}",
                    'post_title' => $title,
                    'post_content' => '',
                    'post_status' => 'inherit'
                );

                $image_id = wp_insert_attachment($attachment, $file, $addSaleResult);

                // Make sure that this file is included, as wp_generate_attachment_metadata() depends on it.
                require_once(ABSPATH . 'wp-admin/includes/image.php');
                // Generate the metadata for the attachment, and update the database record.
                $attach_data = wp_generate_attachment_metadata($image_id, $file);
                wp_update_attachment_metadata($image_id, $attach_data);

                // Ajout de l'image d'en-tête
                set_post_thumbnail($addSaleResult, $image_id);


                return [
                    'success' => true,
                    'title' => $title,
                    //'data' => $datajson
                ];
            }
        }
        return [
            'succes' => false,
            'informations' => 'user is not connected',
            'user' => $user->ID
        ];
    }

    // Suppprimer un post sale
    public function deleteSale(WP_REST_Request $request)
    {

        $id = $request->get_param('id');
        $user = wp_get_current_user();

        if (
            in_array('contributor', (array) $user->roles) ||
            in_array('administrator', (array) $user->roles)
        ) {

            $postItem = get_post($id);

            if (!$user->ID == $postItem->post_author) {
                return [
                    'succes' => 'not allowed'
                ];
            } else {

                wp_delete_post($id);

                return [
                    'succes' => true
                ];
            }
            
        } else {

            return [
                'succes' => false,
                'informations' => 'user is not connected',
                'user' => $user->ID
            ];
        }
    }

    // Ajouter un post article (forum)
    public function addArticle(WP_REST_Request $request)
    {
        $title = $request->get_param('title');
        $description = $request->get_param('story');
        $date = $request->get_param('date');
        $place = $request->get_param('place');
        $image = $request->get_param('image');

        $user = wp_get_current_user();

        // Je vérie que l'user a le bon rôle (donc bien inscrit)
        if (
            in_array('contributor', (array) $user->roles) ||
            in_array('administrator', (array) $user->roles)
        ) {
            $addArticleResult = wp_insert_post(
                [
                    'post_title' => $title,
                    'post_author' => $user->ID,
                    'post_content' => $description,
                    'post_status' => 'publish',
                    'post_type' => 'article'
                ]
            );

            if (is_int($addArticleResult)) {

                update_post_meta($addArticleResult, 'date', $date);
                update_post_meta($addArticleResult, 'place', $place);

                // Je récupère la base64 et le type de l'image
                list($type, $data) = explode(';', $image);
                list(, $data)      = explode(',', $data);
                list(, $type) = explode('/', $type);

                // Si l'image a le bont type alors...
                if (!in_array($type, ['jpg', 'jpeg', 'png'])) {
                    echo "nop!";
                    echo $data;
                } else {
                    echo "yes!";
                    $dataDecoded = base64_decode($data);
                    //$datajson = $dataDecoded;
                }

                // nom de mon image
                $userImageID = uniqid();
                $name = $userImageID . $type;
                // nom de mon image (sans l'extension)
                $filename = basename($name);
                // je demande à WP les chemins de téléchargement
                $upload_dir = wp_upload_dir();

                // si il n'existe pas, WP va me créer un dossier (ici uploads/2021/)
                if (wp_mkdir_p($upload_dir['path'])) {
                    $file = $upload_dir['path'] . '/' . $filename;
                } else {
                    $file = $upload_dir['basedir'] . '/' . $filename;
                }

                // Je reconstruit mon image
                file_put_contents($file, $dataDecoded);

                $attachment = array(
                    //'guid'=> $upload_dir['url'] . '/' . basename($name),
                    'post_mime_type' => "image/{$type}",
                    'post_title' => $title,
                    'post_content' => '',
                    'post_status' => 'inherit'
                );

                $image_id = wp_insert_attachment($attachment, $file, $addArticleResult);

                // Make sure that this file is included, as wp_generate_attachment_metadata() depends on it.
                require_once(ABSPATH . 'wp-admin/includes/image.php');
                // Generate the metadata for the attachment, and update the database record.
                $attach_data = wp_generate_attachment_metadata($image_id, $file);
                wp_update_attachment_metadata($image_id, $attach_data);

                // Ajout de l'image d'en-tête
                set_post_thumbnail($addArticleResult, $image_id);

                return [
                    'success' => true,
                    'title' => $title,
                    //'data' => $datajson
                ];
            }
        }
        return [
            'succes' => false,
            'informations' => 'user is not connected',
            'user' => $user->ID
        ];
    }

    // Modifier un post article (forum)
    public function updateArticle(WP_REST_Request $request)
    {
        $id = $request->get_param('id');
        $title = $request->get_param('title');
        $description = $request->get_param('story');
        $date = $request->get_param('date');
        $place = $request->get_param('place');
        $image = $request->get_param('image');

        $user = wp_get_current_user();

        // Je vérie que l'user a le bon rôle (donc bien inscrit)
        if (
            in_array('contributor', (array) $user->roles) ||
            in_array('administrator', (array) $user->roles)
        ) {
            $postItem = get_post($id);

            if (!$user->ID == $postItem->post_author) {
                return [
                    'succes' => 'not allowed'
                ];
            }
            $addArticleResult = wp_insert_post(
                [
                    'ID' => $id,
                    'post_title' => $title,
                    'post_author' => $user->ID,
                    'post_content' => $description,
                    'post_status' => 'publish',
                    'post_type' => 'article'
                ]
            );

            if (is_int($addArticleResult)) {

                update_post_meta($addArticleResult, 'date', $date);
                update_post_meta($addArticleResult, 'place', $place);

                // Je récupère la base64 et le type de l'image
                list($type, $data) = explode(';', $image);
                list(, $data)      = explode(',', $data);
                list(, $type) = explode('/', $type);

                // Si l'image a le bont type alors...
                if (!in_array($type, ['jpg', 'jpeg', 'png'])) {
                    echo "nop!";
                    echo $data;
                } else {
                    echo "yes!";
                    $dataDecoded = base64_decode($data);
                    //$datajson = $dataDecoded;
                }

                // nom de mon image
                $userImageID = uniqid();
                $name = $userImageID . $type;
                // nom de mon image (sans l'extension)
                $filename = basename($name);
                // je demande à WP les chemins de téléchargement
                $upload_dir = wp_upload_dir();

                // si il n'existe pas, WP va me créer un dossier (ici uploads/2021/)
                if (wp_mkdir_p($upload_dir['path'])) {
                    $file = $upload_dir['path'] . '/' . $filename;
                } else {
                    $file = $upload_dir['basedir'] . '/' . $filename;
                }

                // Je reconstruit mon image
                file_put_contents($file, $dataDecoded);

                $attachment = array(
                    //'guid'=> $upload_dir['url'] . '/' . basename($name),
                    'post_mime_type' => "image/{$type}",
                    'post_title' => $title,
                    'post_content' => '',
                    'post_status' => 'inherit'
                );

                $image_id = wp_insert_attachment($attachment, $file, $addArticleResult);

                // Make sure that this file is included, as wp_generate_attachment_metadata() depends on it.
                require_once(ABSPATH . 'wp-admin/includes/image.php');
                // Generate the metadata for the attachment, and update the database record.
                $attach_data = wp_generate_attachment_metadata($image_id, $file);
                wp_update_attachment_metadata($image_id, $attach_data);

                // Ajout de l'image d'en-tête
                set_post_thumbnail($addArticleResult, $image_id);

                return [
                    'success' => true,
                    'title' => $title,
                    //'data' => $datajson
                ];
            }
        }
        return [
            'succes' => false,
            'informations' => 'user is not connected',
            'user' => $user->ID
        ];
    }

    // Suppprimer un post article (forum)
    public function deleteArticle(WP_REST_Request $request)
    {
        $id = $request->get_param('id');
        $user = wp_get_current_user();

        if (
            in_array('contributor', (array) $user->roles) ||
            in_array('administrator', (array) $user->roles)
        ) {

            $postItem = get_post($id);

            if (!$user->ID == $postItem->post_author) {
                return [
                    'succes' => 'not allowed'
                ];
            } else {

                wp_delete_post($id);

                return [
                    'succes' => true
                ];
            }
            
        } else {

            return [
                'succes' => false,
                'informations' => 'user is not connected',
                'user' => $user->ID
            ];
        }
    }

    // Méthode qui sauvegarde un commentaire et le rattache au post correspondant 
    public function commentSave(WP_REST_Request $request)
    {
        $comment = $request->get_param('comment');
        $postId = $request->get_param('recipeId');
        $user = wp_get_current_user();

        if (
            in_array('contributor', (array) $user->roles) ||
            in_array('administrator', (array) $user->roles)
        ) {

            $commentSaveResult = wp_insert_comment(
                [
                    'user_id' => $user->ID,
                    'comment_post_ID' => $postId,
                    'comment_content' => $comment,
                ]
            );

            if (is_int($commentSaveResult)) {
                return [
                    'success' => true,
                    'recipe-id' => $postId,
                    'comment' => $comment,
                    'user' => $user,
                    'comment-id' => $commentSaveResult
                ];
            } else {
                return [
                    'success' => false
                ];
            }
        } else {

            return [
                'succes' => false,
                'informations' => 'user is not connected',
                'user' => $user->ID
            ];
        }
    }

    // Save email in table custom newsletter
    public function newsLetter(WP_REST_Request $request)
    {
        $email = $request->get_param('email');

        if ($email) {
            $newsletterModel =   new NewsLetterCustomerModel();
            $newsletterModel->insert($email);

            return [
                'succes' => true,
                'email' => $email
            ];
        }
    }
}
