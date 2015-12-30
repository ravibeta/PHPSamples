<?php
require  __DIR__ . '/../../scalr.net-trunk/app/src/prepend.inc.php';
require(dirname(__FILE__).'/protected/config/config.inc.php');
// change the following paths if necessary
$yii=dirname(__FILE__).'/../../scalr.net-trunk/yii_framework/yii.php';
$config=dirname(__FILE__).'/protected/config/main.php';
require_once '../../scalr.net-trunk/simplesamlphp/lib/_autoload.php';

// remove the following lines when in production mode
defined('YII_DEBUG') or define('YII_DEBUG',true);
// specify how many levels of call stack should be shown in each log message
defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',3);


require_once($yii);

require_once '../../scalr.net-trunk/simplesamlphp/lib/_autoload.php';

require_once("../../scalr.net-trunk/app/src/prepend.inc.php");

require_once "/usr/share/php/libphp-phpmailer/class.phpmailer.php";

require_once "../../scalr.net-trunk/app/www/inject.php";

$yiiAPP = Yii::createWebApplication($config);

@session_start();
if (!empty($_GET['r'])) {

$_SESSION['request'] = $_GET['r'];

}
$session = Scalr_Session::getInstance();
//echo serialize($session);
$as = new SimpleSAML_Auth_Simple('default-sp');
if (!$as->isAuthenticated()) {
    $url = '/rajamani/bills/?r=user/ssologin&as=default-sp&redirectto='.urlencode($_SERVER['REQUEST_URI']);
    $params = array(
        'ErrorURL' => $url,
        'ReturnTo' => $url,
    );
    $as->login($params);
}
$yiiAPP->run();
