<?php
$siteUrl = plugin_dir_url(__DIR__);

function my_assets()
{
    var_dump(is_admin());

    if (is_admin()) {
        
        // Google CDN jQuery
        // wp_register_script('jquery', 'http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js', array(), '1.7.1', true);
        
        // // Enregistrement des scripts
        // wp_register_style('datatable-stylesheet2', 'https://cdnjs.cloudflare.com/ajax/libs/foundation/6.4.3/css/foundation.min.css');
        // wp_register_style('datatable-stylesheet', 'https://cdn.datatables.net/v/zf/jq-3.6.0/jszip-2.5.0/dt-1.11.4/af-2.3.7/b-2.2.2/b-colvis-2.2.2/b-html5-2.2.2/b-print-2.2.2/cr-1.5.5/date-1.1.2/fc-4.0.2/fh-3.2.2/kt-2.6.4/r-2.2.9/rg-1.1.4/rr-1.2.8/sc-2.0.5/sb-1.3.1/sp-1.4.0/sl-1.3.4/sr-1.1.0/datatables.min.css');
        // wp_register_script('datatable_pdfmake', 'https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js');
        // wp_register_script('datatable_vfs_font', 'https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js');
        // wp_register_script('datatable_js', 'https://cdn.datatables.net/v/zf/jq-3.6.0/jszip-2.5.0/dt-1.11.4/af-2.3.7/b-2.2.2/b-colvis-2.2.2/b-html5-2.2.2/b-print-2.2.2/cr-1.5.5/date-1.1.2/fc-4.0.2/fh-3.2.2/kt-2.6.4/r-2.2.9/rg-1.1.4/rr-1.2.8/sc-2.0.5/sb-1.3.1/sp-1.4.0/sl-1.3.4/sr-1.1.0/datatables.min.js');
        // wp_register_script('datatable_fundation', 'https://cdnjs.cloudflare.com/ajax/libs/foundation/6.4.3/js/foundation.min.js');
        
        // //Mise en attente des scripts 
        // wp_enqueue_script('jquery');
        // wp_enqueue_style('datatable-stylesheet2', 'https://cdnjs.cloudflare.com/ajax/libs/foundation/6.4.3/css/foundation.min.css');
        // wp_enqueue_style('datatable-stylesheet', 'https://cdn.datatables.net/v/zf/jq-3.6.0/jszip-2.5.0/dt-1.11.4/af-2.3.7/b-2.2.2/b-colvis-2.2.2/b-html5-2.2.2/b-print-2.2.2/cr-1.5.5/date-1.1.2/fc-4.0.2/fh-3.2.2/kt-2.6.4/r-2.2.9/rg-1.1.4/rr-1.2.8/sc-2.0.5/sb-1.3.1/sp-1.4.0/sl-1.3.4/sr-1.1.0/datatables.min.css');
        // wp_enqueue_script('datatable_pdfmake');
        // wp_enqueue_script('datatable_vfs_font');
        // wp_enqueue_script('datatable_js');
        // wp_enqueue_script('datatable_fundation');
        $siteUrl = plugin_dir_url(__DIR__);

        wp_localize_script( 'david_admin-2', 'plugin_ajax_object',['ajax_url' => admin_url( 'admin-ajax.php' ) ] );    
        wp_enqueue_script('david_admin_2', $siteUrl. '/js/admin-script.js');
    
    }
}


add_action('admin_enqueue_scripts', 'my_assets');


// Activer ou désactiver les paramètres 
if (isset($_POST['submit_settings'])){
    
    if (isset($_POST['mail'])) {
        update_option('mail',1);  
    } else{
        update_option( 'mail', 0 ); 
    }
    
    if (isset($_POST['module'])){
        update_option('module' ,1);  
    } else{
        update_option( 'module', 0 ); 
    }
    
    if (isset($_POST['destinataire_mail'])){
       update_option('destinataire_mail',$_POST['destinataire_mail']);
    }
}

$destinataire_mail=get_option('destinataire_mail','');

$mail= get_option('mail', 0);
$module= get_option('module', 0);

// Tableau récapitulatif des sites à scanner
global $wpdb;

$table_sites = $wpdb->prefix . 'david_sites'; 

// Ajouter un site à scanner
if (isset($_REQUEST['david_nom'], $_REQUEST['david_url'], $_REQUEST['david_cms'])){
    $david_nom = $_REQUEST['david_nom'];
    $david_url = $_REQUEST['david_url'];
    $david_cms = $_REQUEST['david_cms'];
    $etat = function_scan_url_entry($david_url);
    if ( $etat != "succes") {
        add_action( 'admin_notices_error', 'sample_admin_notice__error' );
        do_action('admin_notices_error');
    }
    else {
        $data = array('david_nom' => $david_nom, 'david_url' => $david_url, 'david_cms' => $david_cms);
        $wpdb->insert($table_sites,$data);
        add_action( 'admin_notices_sucess', 'sample_admin_notice__sucess' );
        do_action('admin_notices_sucess');
    }
}

if (isset($_POST['SubmitReScan'])){
    $hostname=home_url();
    exec("wget -b -qO- ".$hostname."/?cron_david=1 &> /dev/null");
    $message_scan_manuel='<div class="texte_scan_en_cours">Scan en cours... Veuilllez rafraichier votre page dans 2 minutes.</div>';
}

if (isset($_POST['david_site_id']) && isset($_POST['desactiver_site'])){
    $david_site_id = $_POST['david_site_id'];
    $wpdb->update( $table_sites, array('david_enable' => 0), array( 'david_site_id' => $david_site_id ) );
}

if (isset($_POST['david_site_id']) && isset($_POST['activer_site'])){
    $david_site_id = $_POST['david_site_id'];
   $wpdb->update( $table_sites, array('david_enable' => 1), array( 'david_site_id' => $david_site_id ) );
}


// Supprimer un site
if (isset($_POST['david_site_id']) && isset($_POST['supprimer_site'])) {
    $david_site_id = $_POST['david_site_id'];
    $wpdb->delete( $table_sites, array( 'david_site_id' => $david_site_id ) );
}

$requete = "SELECT * FROM `$table_sites`;";
$sites = $wpdb->get_results($requete, ARRAY_A);

require_once('settings-form.php');
