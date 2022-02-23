<?php
$siteUrl = plugin_dir_url(__DIR__);
wp_enqueue_style('david_css', $siteUrl. '/css/style.css');
?>

<link rel="preconnect" href="https://fonts.gstatic.com">
<link href="https://fonts.googleapis.com/css2?family=Kalam:wght@700&display=swap" rel="stylesheet">
<body>
    <header>
        <img src='<?php echo $siteUrl.'/img/david_module.png';?>'/>
    </header>

    <h3><span class='firstLetter'>D</span>omain <span class='firstLetter'>A</span>ccess <span class='firstLetter'>V</span>erification <span class='firstLetter'>I</span>nternal <span class='firstLetter'>D</span>aemon</h3>
    
    <div class="pres">
        <p>David est un outil de monitoring qui va s'assurer que les sites gérés par Agarta sont disponibles.
        </br>24h/24, 7j/7, David vous informera par mail si il détecte une erreur.</p>
    </div>

    <div class='body'>

<?php
// Activer ou désactiver les paramètres 
if (isset($_POST['submit_settings'])){
    
    if (isset($_POST['module'])){
        update_option('module' ,1);  
    } else{
        update_option( 'module', 0 ); 
    }
}

$module= get_option('module', 0);
?>

<div class='param'>
    <h1>PARAMETRES</h1>
    <div class='parametres'>
        <form action="" method="POST" class='wrap'>
    <div>
        <label for="module"><input type="checkbox" name="module" id="module" value="1" <?php checked($module, 1); ?>>Activer le Module </label>
    </div>
    <div class="submit_settings">
        <input type="submit" name="submit_settings" value="enregistrer" class='checkEnregistrer'>
    </div>
        </form>
    </div>
    </div>
    <div class="mauryl">
        <img src='<?php echo $siteUrl.'/img/circle-mauryl.png';?>' class="img_background"/>
    </div>
</div>
<footer><h2><span class='firstLetter'>D</span>evelopped by <span class='firstLetter'>D</span>elphine</h2></footer>
