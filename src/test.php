<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 2017/11/7
 * Time: 10:04
 */

include "./view.php";

define("PATH", dirname(__FILE__));

$view = new \Kay\View(['cachePath'=>PATH.'/cache/']);
$view->assign('abc', '123');
$view->assign('a', '123');
$view->assign('b', '123');
$view->display('template');

