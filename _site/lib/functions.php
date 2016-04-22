<?php
/**
 * @return string
 */
function get_platform() {
  // we need a default fallback platform
  $platform = 'windows';
  if (isset($_SERVER['HTTP_USER_AGENT'])) {
   $u_agent = $_SERVER['HTTP_USER_AGENT'];

    if (preg_match('/linux/i', $u_agent)) {
      $platform = 'linux';
    } elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
      $platform = 'mac';
    }
  }
  return $platform;
}

/**
 * @return array
 * ['name', 'version', 'platform', 'userAgent']
 */
function get_browser_local() { 
  if (isset($_SERVER['HTTP_USER_AGENT'])) {
    $u_agent = $_SERVER['HTTP_USER_AGENT']; 
    $ub = $bname = $platform = 'unknown';
    $version = "";

    // get platform
    if (preg_match('/linux/i', $u_agent)) {
      $platform = 'linux';
    }
    elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
      $platform = 'mac';
    }
    elseif (preg_match('/windows|win32/i', $u_agent)) {
      $platform = 'windows';
    }

    // get useragent name
    if (preg_match('/Vivaldi/i',$u_agent)) { 
      $bname = 'Vivaldi'; 
      $ub = "Vivaldi"; 
    } elseif (preg_match('/MSIE/i', $u_agent) && !preg_match('/Opera/i',$u_agent)) { 
      $bname = 'Internet Explorer'; 
      $ub = "MSIE"; 
    } elseif (preg_match('/EDGE/i', $u_agent)) { 
      $bname = 'Spartan (masking as Chrome)'; 
      $ub = "EDGE";
    } elseif (preg_match('/Firefox/i', $u_agent)) { 
      $bname = 'Mozilla Firefox'; 
      $ub = "Firefox"; 
    } elseif (preg_match('/Chrome/i', $u_agent)) { 
      $bname = 'Google Chrome'; 
      $ub = "Chrome"; 
    } elseif (preg_match('/Safari/i', $u_agent)) { 
      $bname = 'Apple Safari'; 
      $ub = "Safari"; 
    } elseif (preg_match('/Opera/i', $u_agent)) { 
      $bname = 'Opera'; 
      $ub = "Opera"; 
    } elseif (preg_match('/Netscape/i',$u_agent)) { 
      $bname = 'Netscape'; 
      $ub = "Netscape"; 
    }

    // get the correct version number
    $known = array('Version', $ub, 'other');
    $pattern = '#(?<browser>' . join('|', $known) . ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
    if (!preg_match_all($pattern, $u_agent, $matches)) {
      // no matching number
    }
    $i = count($matches['browser']);

    if ($i == 0) {
      $version = 'unknown';
    } elseif ($i != 1) {
      //see if version is before or after the name
      if (strripos($u_agent,"Version") < strripos($u_agent,$ub)){
        $version = $matches['version'][0];
      } else {
        $version = $matches['version'][1];
      }
    }
    else {
      $version= $matches['version'][0];
    }
    // check if we have a number
    if ($version==null || $version=="") {$version="?";}
    return array(
      'userAgent' => $u_agent,
      'name'      => $bname,
      'version'   => $version,
      'platform'  => $platform,
      'pattern'   => $pattern
    );
  }
  else {
    return array(
      'userAgent' => 'unknown',
      'name'      => 'unknown',
      'version'   => 'unknown',
      'platform'  => 'unknown',
      'pattern'   => 'unknown'
    );
  }
}

/**
 * @param  string  $filename
 * @return string
 */
function asset_path($filename,$siteroot) {

  $manifest_path = $siteroot.'/assets/rev-manifest.json';

  if (file_exists($manifest_path)) {
    $manifest = json_decode(file_get_contents($manifest_path), TRUE);
  } else {
    $manifest = [];
  }

  if (array_key_exists($filename, $manifest)) {
    return $manifest[$filename];
  }

  return $filename;
}
?>