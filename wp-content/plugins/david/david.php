<?php
/*
Plugin Name: David
Plugin URI: https://agarta.co
Description: Monitoring pour la galaxie de sites gérés par Agarta
Author: Delphine @ AGARTA
Version: 1.0
*/

require_once("scripts.php");


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

register_activation_hook (__FILE__, 'david_install');
function david_install () {
    write_log('Installation');

    global $wpdb;
    $table_name = $wpdb->prefix . 'david_sites'; 
    $query = "CREATE TABLE IF NOT EXISTS $table_name
        (
        `david_site_id` INT(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
        `david_nom` VARCHAR(255),
        `david_url` VARCHAR(255),
        `david_cms` VARCHAR(255),
        `david_enable` TINYINT(1)
        )";

    $wpdb->query($query);

    $table_name = $wpdb->prefix . 'david_incidents';
    $query = "CREATE TABLE IF NOT EXISTS $table_name
        (
        `david_incident_id` INT(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
        `david_id_site` INT(11),
        `david_date_heure` DATETIME,
        `david_email_envoye` TINYINT(1),
        `david_remarque` VARCHAR(255)
        )";
    
    $wpdb->query($query);

    $table_name = $wpdb->prefix . 'david_settings'; 
    $query = "CREATE TABLE IF NOT EXISTS $table_name
        (
        `david_setting_id` INT(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
        `david_envoi_email_on_off` TINYINT(1),
        `david_module_on_off` TINYINT(1)
        )";
    
    $wpdb->query($query);
    
    update_option('last_request','');
}

register_uninstall_hook (__FILE__, 'david_uninstall');
function david_uninstall (){
    write_log('Desinstallation');

    global $wpdb;
 
    $table_name = $wpdb->prefix . 'david_sites'; 
    $wpdb->query( "DROP TABLE IF EXISTS $table_name" );

    $table_name = $wpdb->prefix . 'david_incidents';
    $wpdb->query( "DROP TABLE IF EXISTS $table_name" );

    $table_name = $wpdb->prefix . 'david_settings'; 
    $wpdb->query( "DROP TABLE IF EXISTS $table_name" );

}

function david_admin_menu() {
    add_menu_page('David','David','manage_options','admin-menu-page', 'david_page_admin','dashicons-visibility');
    add_submenu_page('admin-menu-page','Log_Incident_form','Log_Incidents','manage_options', 'admin-menu-page-log','admin_menu_page_log');
    add_submenu_page('admin-menu-page','Settings_form','Settings','manage_options', 'admin-menu-page-settings','admin_menu_page_settings');
    
}
    add_action('admin_menu','david_admin_menu');

function david_page_admin() {
    if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
    require_once('admin/admin-menu-page.php'); 
}    

function admin_menu_page_log() {
    if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
    require_once('admin/admin-menu-page-log.php'); 
}

function admin_menu_page_settings() {
    if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
    require_once('admin/admin-menu-page-settings.php'); 
}

function admin_menu_page_good_log() {
    if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
    require_once('admin/admin-menu-page-good-log.php'); 
}

add_action( 'init', 'function_cron_david' );

function function_cron_david() {
    //write_log('hook_init');
    if (isset($_GET['cron_david']) && $_GET['cron_david']!='') {
       //write_log('param_cron david détectés');
        if ($_GET['cron_david']==1) {
            write_log(get_option('module', 0));
            if (get_option('module', 0)==1) {
                function_scan_url();
            }
        }
    }
}

function function_scan_url(){
    global $wpdb;
    $table_sites = $wpdb->prefix . 'david_sites'; 
    $requete = "SELECT * FROM `$table_sites`;";
    $sites = $wpdb->get_results($requete, ARRAY_A);
    $nb_defaut = 0;
    $nb_site_scanne = 0;
    foreach($sites as $url){
        
        if ($url['david_enable']){
        
            $nb_site_scanne++;
            
            $cmpt_tentaive_requete=3;

            do{

                write_log('Tentative n°'.$cmpt_tentaive_requete.' sur l\'URL : '.$url['david_url']);

                $cmpt_tentaive_requete--;

                $returnArray = function_curl($url['david_url']); //on scanne le site

                date_default_timezone_set('Europe/Paris');
                $david_date_heure =  date('d-m-Y H:i:s');
                $david_date_heure_sql = date('Y-m-d H:i:s');

                if ($returnArray['data']!='Ok Mauryl' && $returnArray['error']==''){
                    $returnArray['error']= 'Mauvais retour de requête reçue';
                }

            }

            while ($returnArray['error']!='' && $cmpt_tentaive_requete>0);


            if ($returnArray['error']!=''){

                $check_envoi_mail = false;

                if (get_option('mail', 0)==1) {

                    if (mail_admin($url, $david_date_heure, $returnArray)) {
                        $check_envoi_mail = 1;
                    } else {
                        $check_envoi_mail = 0;
                    } 
                } else {
                    $check_envoi_mail = 0;
                }

                write_log('Erreur sur l\'URL : '.$url['david_url']);
                return_incidents($url['david_site_id'],$david_date_heure_sql,$check_envoi_mail,$returnArray['error']);
                $nb_defaut++;

            }else {

                write_log('ok:'.$returnArray['data'].' pour l\'URL : '.$url['david_url']);

            }
        }
    }
    $date_dernier_scan=date('d-m-Y H:i:s');
    $dernier_scan='Dernier scan effectué le '.$date_dernier_scan.'<br>Nb de sites scannés : '.$nb_site_scanne.'<br>Nb de site en défaut : '.$nb_defaut;
    update_option('last_request',$dernier_scan);
}

function changedateusfr($david_date_heure_sql){
    write_log($david_date_heure_sql);
    $jour = substr($david_date_heure_sql, 8,2);
    $mois = substr($david_date_heure_sql, 5,2);
    $annee = substr($david_date_heure_sql, 0,4);
    $heure =  substr($david_date_heure_sql, 11,8);
    $datefr = $jour . '-' .$mois .'-' .$annee . ' ' .$heure;
    return $datefr;
}

function mail_admin($url, $david_date_heure, $returnArray) {
    /*$admin = get_users(array('role__in' => array('administrator')));
    $mail_admin = array();
    foreach ($admin as $user) {
        $mail_admin[] = $user->user_email;
    }*/
    $destinataire_mail=get_option('destinataire_mail','');
    write_log($mail_admin);
    
    $to = $destinataire_mail;
    $subject  = '🆘 Erreur sur le site ' .$url['david_nom']. ' 🆘';
    $message = 'Bonjour, David a détecté une erreur le ' .$david_date_heure. ' . Le site ' .$url['david_nom']. ' (' .$url['david_url']. ') présente l\'erreur: ' .$returnArray['error']. '<br/> Bon courage !!! 🙈 🙉 🙊 <br/><img src="https://media.giphy.com/media/eAkVj1PnzM2c0/giphy.gif"/>';
    $headers = array('Content-Type: text/html; charset=UTF-8');
    
    return wp_mail($to, $subject, $message, $headers); 
}

function function_curl($url) {
    $returnArray = array();
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
    //curl_setopt($curl, CURLOPT_AUTOREFERER, TRUE);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($curl, CURLOPT_URL, $url.'/?requete_david=1');
    curl_setopt($curl, CURLOPT_TIMEOUT, 10);
    curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 10);
    curl_setopt($curl, CURLOPT_POST, FALSE);
	
	/* debug */
	/*curl_setopt($curl, CURLOPT_VERBOSE, 1);
	$fp = fopen(dirname(__FILE__).'/errorlog.txt', 'w+');
   	curl_setopt($curl, CURLOPT_STDERR, $fp);*/
	/* fin debug */
	
    $data = curl_exec($curl);
	
    $response_info = curl_getinfo($curl);
    
	if (($response_info['http_code'] != 200) || ($data===false)){
		//write_log('data2:'.$data);
		//
		/*rewind($fp);
		$verboseLog = stream_get_contents($fp);
		*/
        $returnArray['data']= '';    
        $returnArray['error']=curl_error($curl); //.' - '.$verboseLog;
		
        write_log('erreur2:'.$returnArray['error']);
    } else {
        $returnArray['data']= $data;    
        $returnArray['error']='';
    }

    curl_close($curl);
    return $returnArray;
}

function return_incidents($david_id_site,$david_date_heure,$david_email_envoye,$david_remarque){
global $wpdb;
$table_incidents = $wpdb->prefix . 'david_incidents'; 
    $data = array('david_id_site' => $david_id_site, 'david_date_heure' => $david_date_heure, 'david_email_envoye' => $david_email_envoye, 'david_remarque' => $david_remarque);
    write_log('test'.print_r($data, true));
    $wpdb->insert($table_incidents,$data);
}

function function_scan_url_entry($url_entry){

    $returnArray = function_curl($url_entry); //on scanne le site
         if ($returnArray['data']!='Ok Mauryl' && $returnArray['error']==''){
            //echo "Le site marche";
            return $etat ="succes";
            }
         else 
         {
            //echo "Le site ne marche pas"; 
            return $etat ="error";     
                }

}
// Notification lors d'une erreur lors de l'ajout d'un site a la liste
function sample_admin_notice__error() {
    $class = 'notice notice-error';
    $message = __( 'Le site ne marche pas et ne peut pas etre ajouté a la base de donnée.', 'sample-text-domain' );
    printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message ) ); 
}
// Notification loors d'un succes lors de l'ajout d'un site a la liste 
function sample_admin_notice__sucess() {
    $class = 'notice notice-error';
    $message = __( 'Le site marche et a etait ajouté .', 'sample-text-domain' );
 
    printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message ) ); 
}



