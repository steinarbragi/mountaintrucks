<!DOCTYPE html>
<?php
// latest build

$siteroot = __DIR__;
// page meta
if (!isset($pageTitle)) {
	$pageTitle = "Title";
} else {
	$pageTitle .= " – Sub";
}
if (!isset($pageDescription)) {
	$pageDescription = "Description";
}
if (!isset($pageKeywords)) {
	$pageKeywords = "keywords";
}

include "lib/functions.php";
$server = $_SERVER['SERVER_NAME'];
$os = get_platform();
$ua = get_browser_local();
// Overriding language logic for bug report (English only)

// $lang = 'en';

?>
<?php # move MSIE junk out of the way when possible
if ($ua['name'] == 'Internet Explorer') {?>
<!--[if lt IE 7]><html lang="<?php echo substr($lang, 0, 2);?>" class="ie6 lte9 lte8 lte7" xmlns="http://www.w3.org/1999/xhtml"><![endif]-->
<!--[if IE 7]><html lang="<?php echo substr($lang, 0, 2);?>" class="ie7 lte9 lte8 lte7" xmlns="http://www.w3.org/1999/xhtml"><![endif]-->
<!--[if IE 8]><html lang="<?php echo substr($lang, 0, 2);?>" class="ie8 lte9 lte8" xmlns="http://www.w3.org/1999/xhtml"><![endif]-->
<!--[if IE 9]><html lang="<?php echo substr($lang, 0, 2);?>" class="ie9 lte9" xmlns="http://www.w3.org/1999/xhtml"><![endif]-->
<!--[if gt IE 9]><html lang="<?php echo substr($lang, 0, 2);?>" xmlns="http://www.w3.org/1999/xhtml"><![endif]-->
<!--[if !IE]><!--><html lang="<?php echo substr($lang, 0, 2);?>" xmlns="http://www.w3.org/1999/xhtml"><!--<![endif]-->
<head>
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <!--[if lt IE 9]><script src="//html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
<?php } else {?>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php }?>
  <meta charset="utf-8" />
  <meta name="author" content="Vivaldi Technologies" />
  <meta name="description" content="<?php echo $pageDescription?>" />
  <meta name="keywords"  content="<?php echo $pageKeywords?>" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <?php /* FILL INN META HERE AND UNCOMMENT 

  <meta property="og:url" content="https://www.vivaldi.com" />
  <meta property="og:title" content="<?php echo $pageTitle;?>" />
  <meta property="og:image" content="/images/viv-fb-og.jpg" />
  <meta property="og:type" content="website" />
  <meta property="og:site_name" content="Vivaldi" />
  */

  ?>
  <title><?php echo $pageTitle?></title>
  <link href="/assets/css/<?php echo asset_path('style.css', $siteroot);?>" rel="stylesheet" type="text/css" />
  <?php echo (isset($headlinks) ? $headlinks : ''); ?>
  <?php echo (isset($headscripts) ? $headscripts : ''); ?>
  <?php if ($server == 'localhost') {?>
  <!-- LIVERELOAD SNIPPET -->
  <script src="http://localhost:35729/livereload.js?snipver=1"></script>
  <!-- LIVERELOAD SNIPPET END -->
  <?php }?>
</head>

<?php
if (isset($bodyTag)) {
	$bodyClasses = $bodyTag;
}
if (isset($menuMarkup)) {
	$bodyClasses .= " has-submenu";
}

?>
<body class="<?=$bodyClasses?>">

  <header id="site-header">
    <div class="site-header-toprow">
      <a class="site-logo" href="/">

      </a>
    </div>

    <?php if (isset($menuMarkup)) {
	print '<div class="site-header-rowbelow">
           <nav id="page-nav">' . $menuMarkup . '</nav>
         </div>';
}?>

  </header>
