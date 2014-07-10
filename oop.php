<?php

session_start();

require("inc/validateForm.php");
require("inc/crtForm.php");
require("inc/dataClass.php");
require("inc/paginator.php");
include_once("tpl/head.php");
$page=$html;

include_once("tpl/formprocess.php");
$page.=$form;
echo $page;

$message=dataOperations::getPageData();
echo $message;



$paginator=new paginator();
$page=$paginator::getPaginator();
echo ($page['PAGE']);
echo ($page['numberOutput']);