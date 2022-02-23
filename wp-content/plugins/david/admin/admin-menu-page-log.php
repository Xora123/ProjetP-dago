<?php
$siteUrl = plugin_dir_url(__DIR__);

wp_enqueue_style('david_css', $siteUrl. '/css/style.css');




// Tableau récapitulatif des logs
global $wpdb;

$table_sites = $wpdb->prefix . 'david_sites'; 
$table_incidents = $wpdb->prefix . 'david_incidents';

// *******************  Supprimer un log
$message_delete='';
if (isset($_POST['SubmitDeleteLog'])) {
    
    $delete = $wpdb->query("TRUNCATE TABLE $table_incidents");
    $message_delete='Log effacés avec succès';
}
//***************************************


$requete = "SELECT *
FROM $table_sites
INNER JOIN $table_incidents
WHERE $table_sites.david_site_id = $table_incidents.david_id_site
ORDER BY $table_incidents.david_date_heure DESC";

$incidents = $wpdb->get_results($requete, ARRAY_A);


require_once('log-form.php');