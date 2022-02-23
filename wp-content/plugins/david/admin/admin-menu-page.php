<?php
$siteUrl = plugin_dir_url(__DIR__);
wp_enqueue_style('david_css', $siteUrl. '/css/style.css');
?>
<link rel="preconnect" href="https://fonts.gstatic.com">
<link href="https://fonts.googleapis.com/css2?family=Kalam:wght@700&display=swap" rel="stylesheet">
<body>
<header>
<img src='<?php echo $siteUrl.'/img/david_module.png';?>'/>
<!--<div class='title'>
<h1 class='david'>DAVID</h1>
<h2 class='security'>SECURITY SOLUTIONS</h2>
</div>
</div>
</div>
<h1 class='agarta'>by AGARTA</h1>-->
<!--<h3>VERSION 1.0</h3>-->

</header>
<h3><span class='firstLetter'>D</span>omain <span class='firstLetter'>A</span>ccess <span class='firstLetter'>V</span>erification <span class='firstLetter'>I</span>nternal <span class='firstLetter'>D</span>aemon</h3>
<div class="pres">
<p>David est un outil de monitoring qui va s'assurer que les sites gérés par Agarta sont disponibles.
</br>24h/24, 7j/7, David vous informera par mail si il détecte une erreur.</p>
</div>
<div class="mauryl">
<img src='<?php echo $siteUrl.'/img/circle-mauryl.png';?>' class="img_background"/>
</div>
<footer>
<h2><span class='firstLetter'>D</span>evelopped by <span class='firstLetter'>D</span>elphine</h2>
</footer>
</body>
