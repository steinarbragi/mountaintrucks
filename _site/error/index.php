<?php
$bodyTag = "error";
require_once "../Locale/locale.php";
switch ($_REQUEST['er']) {
	//Error 400 - Bad Request
	case 400:
		$errorname = _('400 - Bad Request');
		$errordesc = _('Error 400') . '<br>' .
		_('The request was invalid or cannot be served.');
		break;

	//Error 401 - Authorization Required
	case 401:
		$errorname = _('401 - Unauthorized');
		$errordesc = _('Error 401') . '<br>' .
		_("The page you requested requires authentication. The credentials are either missing or incorrect.");
		break;

	//Error 403 - Access Forbidden
	case 403:
		$errorname = _('403 - Access Forbidden');
		$errordesc = _('Error 403') . '<br>' .
		_('You do not have permission to retrive the URL or link you requested.');
		break;

	//Error 404 - Not Found
	case 404:
		$errorname = _('404 - Page not found');
		$errordesc = _('Error 404') . '<br>' .
		_("I'm sorry, but you got a 404 error. It could be many reasons, like an out-of-date bookmark, a bad listing in a search engine or mistyped address.") . '<br>' .
		_("I recommend going back to our front page and find what you're looking for there!");
		break;

	//Error 500 - Server Configuration Error
	case 500:
		$errorname = _('Error 500 - Internal Server Error');
		$errordesc = _('Error 500') . '<br>' .
		_('Internal Server Error') . '<br>' .
		_('The URL that you requested, resulted in a server configuration error.
  It is possible that the condition causing the problem will be gone by
  the time you finish reading this.'	);
		break;

	//Unknown error
	default:
		$errorname = _('Unknown Error');
		$errordesc = '<br>' .
		_('The URL that you requested, resulted in an unknown error.
  It is possible that the condition causing the problem will be gone by
  the time you finish reading this.'	);

}
$pageTitle = $errorname;
$pageDescription = _("Icelandic mountain trucks error page");
$pageKeywords = _("Error");
include "../header.php";
?>
<div class="section" id="bugreport">
	<div class="container fullwidth">
    <div class="section-inner" style="margin: 100px 0 150px 0">
      <h1 style="color: #EF3939"><strong>Oops!</strong><br>
      <?php echo $errorname?></h1>
      <p><?php echo $errordesc?></p>
        <a href="/" class="btn-download">
        <span><strong><?php echo _("Take me to safety");?></strong></span>
      </a>
    </div>
  </div>
</div>

<?php include "../footer.php";?>
