<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 2017/11/7
 * Time: 10:04
 */

include "./view.php";

$view = new \Kay\View([]);
$view->assign('abc', '123');
$view->display('template');

