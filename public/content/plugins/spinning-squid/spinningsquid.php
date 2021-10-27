<?php

/**
* Plugin Name: SpinningSquidPlugin
*/

require __DIR__ . '/vendor-spinningsquid/autoload.php';

use SpinningSquid\Plugin;

$spinningsquid= new Plugin;

// Méthode que l'on "branche" sur l'activation du plugin
// J'utilise ici la notation [$monObjet, 'maMéthode']
    //register_activation_hook(__FILE__, [$spinningsquid, 'activate']);
// Méthode que l'on "branche" sur la désactivation du plugin
    //register_deactivation_hook(__FILE__, [$spinningsquid, 'deactivate']);
