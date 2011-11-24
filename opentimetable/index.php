<?php
 /**
 * Example Application

 * @package Example-application
 */

require('libs/Smarty.class.php');

$smarty = new Smarty;

//$smarty->force_compile = true;
$smarty->debugging = false;
$smarty->caching = true;
$smarty->cache_lifetime = 120;

$smarty->assign("Site_title","Open Timetable",true);
$smarty->assign("Home","Home",true);
$smarty->assign("About","About Us",true);
$smarty->assign("Contacts","Contacts",true);
$smarty->assign("Country","Country",true);
$smarty->assign("City","City",true);
$smarty->assign("Category","Category",true);
$smarty->assign("Org_name","Name",true);
$smarty->assign("Organizations","Organizations",true);
$smarty->assign("Study","Study",true);
$smarty->assign("Add_timetable","Add Your TimeTable",true);
$smarty->assign("date_time", date('l \t\h\e jS'),true);

$smarty->display('index.tpl');
?>
