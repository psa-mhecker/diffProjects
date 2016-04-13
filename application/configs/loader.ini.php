<?php
/**
 * Tableau de type : chemin, class
 */

$_LOADER['External.Smarty'] = array(Pelican::$config['LIB_ROOT'].Pelican::$config['CLASS_SMARTY'],'Smarty');
$_LOADER['Form'] = array(Pelican::$config['APPLICATION_LIBRARY'] .'/Citroen/Form.php', 'Citroen_Form');
$_LOADER['User.Backoffice'] = array(Pelican::$config['APPLICATION_LIBRARY'] .'/Citroen/User/Backoffice.php', 'Citroen_User_Backoffice');
$_LOADER['Request.Route.Citroen'] = array(Pelican::$config['APPLICATION_LIBRARY'] .'/Citroen/Request/Route/Citroen.php', 'Citroen_Request_Route_Citroen');
$_LOADER['Request.Route.FormPerso'] = array(Pelican::$config['APPLICATION_LIBRARY'] .'/Citroen/Request/Route/FormPerso.php', 'Citroen_Request_Route_FormPerso');
$_LOADER['Request.Route.EncyclopediqueUrl'] = array(Pelican::$config['APPLICATION_LIBRARY'] .'/Citroen/Request/Route/EncyclopediqueUrl.php', 'Citroen_Request_Route_EncyclopediqueUrl');
$_LOADER['Request.Route.Sitemap'] = array(Pelican::$config['APPLICATION_LIBRARY'] .'/Citroen/Request/Route/Sitemap.php', 'Citroen_Request_Route_Sitemap');
$_LOADER['Request.Route.CriteoCatalogFeed'] = array(Pelican::$config['APPLICATION_LIBRARY'] .'/Citroen/Request/Route/CriteoCatalogFeed.php', 'Citroen_Request_Route_CriteoCatalogFeed');
$_LOADER['List'] = array(Pelican::$config['APPLICATION_LIBRARY'] .'/Citroen/List.php', 'Citroen_List');
$_LOADER['PageComposite'] = array(Pelican::$config['APPLICATION_LIBRARY'] .'/Citroen/Page/PageComposite.php', 'Citroen_Page_PageComposite');
$_LOADER['Layout'] = array(Pelican::$config['APPLICATION_LIBRARY'] .'/Citroen/Layout.php', 'Citroen_Layout');
$_LOADER['Layout.Desktop'] = array(Pelican::$config['APPLICATION_LIBRARY'] .'/Citroen/Layout/Desktop.php', 'Citroen_Layout_Desktop');
$_LOADER['Media'] = array(Pelican::$config['APPLICATION_LIBRARY'] .'/Citroen/Media.php', 'Citroen_Media');

// La surchage de la view ne fonctionne pas
$_LOADER['View'] = array(Pelican::$config['APPLICATION_LIBRARY'] .'/Citroen/View.php', 'Citroen_View');
?>
