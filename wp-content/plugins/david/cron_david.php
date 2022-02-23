<?php 
include( explode( "wp-content" , __FILE__ )[0] . "wp-load.php" );
//echo home_url();

//$hostname = 'https://david.agartaworld.com';
$hostname=home_url();

exec("wget -qO- ".$hostname."/?cron_david=1 &> /dev/null");
echo "wget -qO- ".$hostname."/?cron_david=1 &> /dev/null";

?>