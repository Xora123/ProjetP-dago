<?php
/*
Plugin Name: David Mauryl
Plugin URI: https://agarta.co
Description: Monitoring pour la galaxie de sites gérés par Agarta
Author: Delphine @ AGARTA
Version: 1.0
*/

if (!function_exists('write_log')) {
    function write_log($log) {
        if (true === WP_DEBUG) {
            if (is_array($log) || is_object($log)) {
                error_log(print_r($log, true));
            } else {
                error_log($log);
            }
        }
    }
}

function __construct(){	
}  

// Création du menu
function david_admin_menu() {
    add_menu_page('David','David','manage_options','admin-menu-page', 'david_page_admin','dashicons-visibility');
}
    add_action('admin_menu','david_admin_menu');
    
function david_page_admin() {
    if ( !current_user_can( 'manage_options' ) )  {
        wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
    }
    require_once('admin/admin-menu-page.php'); 
}

add_action( 'init', 'function_requete_david' );

// Fonction qui réceptionne la requête du module principal et envoie un accusé de réception pour signaler que la requête a bien été reçue

function function_requete_david() {
    if (isset($_GET['requete_david']) && $_GET['requete_david']!='') {
        if ($_GET['requete_david']==1) {
            write_log(get_option('module', 0));
            if (get_option('module', 0)==1) {
            write_log('requete detectee');
            echo 'Ok Mauryl';
            die();
            }
        }
    }
}
