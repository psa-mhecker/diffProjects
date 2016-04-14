<?php
include_once 'config.php';
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $aBind [':SITE_ID'] = $_GET['id'];
    $sql    =   prepareSql('delete from psa_media_format_intercept where media_id in (select media_id from psa_media where media_directory_id in (select media_directory_id from #pref#_media_directory where site_id = :SITE_ID))', $aBind);
    echo $sql.';<br/>';

    $cascadeDelete = array(
            array('#pref#_page_zone_multi', 'page'),
            array('#pref#_page_multi_zone_multi', 'page'),
            '#pref#_form',
            '#pref#_content_type_site',
            '#pref#_directory_site',
            array('#pref#_profile_directory', 'profile'),
            array('#pref#_user_profile', 'profile'),
            '#pref#_profile',
            '#pref#_site_parameter_dns',
            '#pref#_site_parameter',
            array('#pref#_content_version', 'content'),
            array('#pref#_content_version_media', 'content'),
            array('#pref#_content_zone_multi', 'content'),
            '#pref#_content',
            '#pref#_content_category',
            array('#pref#_navigation', 'page' ),
            array('#pref#_page_zone', 'page' ),
            array('#pref#_page_zone_content', 'page' ),
            array('#pref#_page_zone_media', 'page' ),
            array('#pref#_page_version', 'page' ),
            array('#pref#_page_version', 'template_page' ),
            '#pref#_page', '#pref#_template_site',
            array('#pref#_template_page_area', 'template_page' ),
            array('#pref#_zone_template', 'template_page' ),
            '#pref#_template_page',
            array('#pref#_user_profile', 'profile' ),
            array('#pref#_profile_directory', 'profile' ),
            '#pref#_profile',
            '#pref#_service',
            '#pref#_comment',
            array('#pref#_terms_group_rel', 'terms_group' ),
            array('#pref#_terms', 'terms_group' ),
            '#pref#_terms_group', '#pref#_rewrite',
            '#pref#_pub', '#pref#_tag',
            '#pref#_research_log',
            '#pref#_research',
            '#pref#_research_param',
            '#pref#_research_param_field',
            '#pref#_site_code',
            '#pref#_site_language',
            '#pref#_site_dns',
            '#pref#_barre_outils',
            '#pref#_media_directory',
            '#pref#_site',
    );
    cascadeDelete('site', $cascadeDelete);
} else {
    echo utf8_decode('Vous avez oublié le site ID');
}

/**
 * __DESC__.
 *
 * @access public
 *
 * @param __TYPE__ $table __DESC__
 * @param __TYPE__ $cascadeDelete __DESC__
 *
 * @return __TYPE__
 */
function cascadeDelete($table, $cascadeDelete)
{
    $aBind[':ID'] = $_GET['id'];
    if (is_array($cascadeDelete)) {
        foreach ($cascadeDelete as $child) {
            if (is_array($child)) {
                $sql = 'delete from '.$child[0].' where '.$child[1].'_id in (select '.$child[1].'_id from #pref#_'.$child[1].' where '.$table.'_id = :ID)';
            } else {
                $sql = 'delete from '.$child.' where '.$table.'_id = :ID';
            }
            $sql    =   prepareSql($sql, $aBind);
            echo $sql.';<br/>';
        }
    }
}

function prepareSql($sql, $aBind)
{
    $oConnection = Pelican_Db::getInstance();
    $oConnection->prepareBind($sql, $aBind);

    return $oConnection::replacePrefix($sql);
}
