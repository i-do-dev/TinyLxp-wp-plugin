<?php
$livePath = dirname( __FILE__ );
// require_once ABSPATH . 'wp-load.php';
// require_once $livePath.'/lxp/functions.php';
lxp_login_check();

$treks_src = content_url().'/plugins/TinyLxp-wp-plugin/lms/templates/tinyLxpTheme/treks-src/';
$userdata = get_userdata(get_current_user_id());

$userRole = count($userdata->roles) > 0 ? array_values($userdata->roles)[0] : '';
switch ($userRole) {
  // case 'lxp_client_admin':
  //   echo 'Not a valid User role';
  //   get_template_part('lxp/client-students');
  //   break;
  // case 'lxp_school_admin':
  //   echo 'Not a valid User role';
  //   get_template_part('lxp/school-students');
  //   break;
  case 'lxp_teacher':
    include $livePath.'/lxp/teacher-classes.php';
    break;
  case 'administrator':
    include $livePath.'/lxp/admin-classes.php';
    break;
  default:
    echo 'Not a valid User role';
    break;
}

?>