<?php
/**
 * Tableau de type : chemin, class.
 */
$_LOADER['External.Smarty'] = array(Pelican::$config['LIB_ROOT'].Pelican::$config['CLASS_SMARTY'], 'Smarty');
$_LOADER['Form'] = array(Pelican::$config['APPLICATION_LIBRARY'].'/Ndp/Form.php', 'Ndp_Form');
$_LOADER['User.Backoffice'] = array(
    Pelican::$config['APPLICATION_LIBRARY'].'/Ndp/User/Backoffice.php',
    'Ndp_User_Backoffice'
);

$_LOADER['List'] = array(Pelican::$config['APPLICATION_LIBRARY'].'/Ndp/List.php', 'Ndp_List');
$_LOADER['PageComposite'] = array(
    Pelican::$config['APPLICATION_LIBRARY'].'/Ndp/Page/PageComposite.php',
    'Ndp_Page_PageComposite'
);
$_LOADER['Layout'] = array(Pelican::$config['APPLICATION_LIBRARY'].'/Citroen/Layout.php', 'Citroen_Layout');
$_LOADER['Layout.Desktop'] = array(
    Pelican::$config['APPLICATION_LIBRARY'].'/Citroen/Layout/Desktop.php',
    'Citroen_Layout_Desktop'
);
$_LOADER['Media'] = array(Pelican::$config['APPLICATION_LIBRARY'].'/Ndp/Media.php', 'Ndp_Media');
$_LOADER['CtaComposite'] = array(
    Pelican::$config['APPLICATION_LIBRARY'].'/Ndp/Cta/CtaComposite.php',
    'Ndp_Cta_CtaComposite'
);
$_LOADER['CtaDisable'] = array(Pelican::$config['APPLICATION_LIBRARY'].'/Ndp/Cta/CtaDisable.php', 'Ndp_Cta_CtaDisable');
$_LOADER['CtaNew'] = array(Pelican::$config['APPLICATION_LIBRARY'].'/Ndp/Cta/CtaNew.php', 'Ndp_Cta_CtaNew');
$_LOADER['CtaRef'] = array(Pelican::$config['APPLICATION_LIBRARY'].'/Ndp/Cta/CtaRef.php', 'Ndp_Cta_CtaRef');
$_LOADER['ListeDeroulante'] = array(Pelican::$config['APPLICATION_LIBRARY'].'/Ndp/Cta/ListeDeroulante.php', 'Ndp_Cta_Liste_Deroulante');

$_LOADER['Cta'] = array(Pelican::$config['APPLICATION_LIBRARY'].'/Ndp/Cta.php', 'Ndp_Cta');
$_LOADER['PageZoneMulti'] =
    array(Pelican::$config['APPLICATION_LIBRARY'].'/Ndp/Page/Zone/Multi.php', 'Page_Zone_Multi');
$_LOADER['PageMultiZoneMulti'] =
    array(
        Pelican::$config['APPLICATION_LIBRARY'].'/Ndp/Page/Multi/Zone/Multi.php',
        'Page_Multi_Zone_Multi'
    );
$_LOADER['Webservice'] = array(Pelican::$config['APPLICATION_LIBRARY'].'/Ndp/Webservice.php', 'Ndp_Webservice');

// La surchage de la view ne fonctionne pas
$_LOADER['View'] = array(Pelican::$config['APPLICATION_LIBRARY'].'/Citroen/View.php', 'Citroen_View');
$_LOADER['Request.Route.Image'] = array(
    Pelican::$config['APPLICATION_LIBRARY'].'/Ndp/Request/Route/Image.php',
    'Ndp_Request_Route_Image'
);
$_LOADER['Cache.Media'] = array(
    Pelican::$config['APPLICATION_LIBRARY'].'/Ndp/Cache/Media.php',
    'Ndp_Cache_Media'
);
$_LOADER['Media.ImageMagick'] = array(
    Pelican::$config['APPLICATION_LIBRARY'].'/Ndp/Media/ImageMagick.php',
    'Ndp_Media_ImageMagick'
);

$_LOADER['Hierarchy.Tree'] = array(
    Pelican::$config['APPLICATION_LIBRARY'].'/Ndp/Hierarchy/Tree.php',
    'Ndp_Hierarchy_Tree'
);
