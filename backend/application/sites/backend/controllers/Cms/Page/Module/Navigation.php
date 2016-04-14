<?php
include_once Pelican::$config['CONTROLLERS_ROOT'].'/Cms/Page/Ndp.php';
class Cms_Page_Module_Navigation extends Cms_Page_Ndp
{
    public static $second = false;

    public static $usage = false;

    public static $max = 5;

    public static $maxRow = 8;

    public static $type = "";
    public static $type1 = "";
    public static $type2 = "";

    public static function save(Pelican_Controller $controller)
    {
        $oConnection = Pelican_Db::getInstance();

        $DBVALUES_SAVE = Pelican_Db::$values;
        if (Pelican_Db::$values['form_action'] != Pelican_Db::DATABASE_DELETE) {
            $ZONE_TEMPLATE_ID = Pelican_Db::$values["ZONE_TEMPLATE_ID"];

            parent::save();

            $navigation_count = 0;
            for ($i = 0; $i < sizeOf($controller->monoValues["NAVIGATION_LEVEL"]); $i ++) {
                if ($ZONE_TEMPLATE_ID == $controller->monoValues["NAVIGATION_ZONE_TEMPLATE_ID"][$i]) {
                    ++$navigation_count;
                    Pelican_Db::$values = array();
                    Pelican_Db::$values["ZONE_TEMPLATE_ID"] = $ZONE_TEMPLATE_ID;
                    Pelican_Db::$values["NAVIGATION_ORDER"] = $navigation_count;
                    Pelican_Db::$values["NAVIGATION_ID"] = $navigation_count;
                    if ($controller->monoValues["NAVIGATION_LEVEL"][$i] == 1) {
                        $j = $navigation_count;
                        Pelican_Db::$values["NAVIGATION_PARENT_ID"] = "";
                    } else {
                        Pelican_Db::$values["NAVIGATION_PARENT_ID"] = $j;
                    }

                    Pelican_Db::$values["PAGE_ID"] = $controller->monoValues["PAGE_ID"];
                    Pelican_Db::$values["PAGE_VERSION"] = $controller->monoValues["PAGE_VERSION"];
                    Pelican_Db::$values['LANGUE_ID'] = $controller->monoValues['LANGUE_ID'];
                    Pelican_Db::$values["NAVIGATION_BOLD"] = $controller->monoValues["NAVIGATION_BOLD"][$i];
                    Pelican_Db::$values["NAVIGATION_IMG"] = $controller->monoValues["NAVIGATION_IMG"][$i];
                    if ($controller->monoValues["NAVIGATION_IMG"][$i]) {
                        Pelican_Db::$values["NAVIGATION_MEDIA_ID"] = (int) basename($controller->monoValues["NAVIGATION_IMG"][$i]);
                    }
                    Pelican_Db::$values["NAVIGATION_IMG2"] = $controller->monoValues["NAVIGATION_IMG2"][$i];
                    Pelican_Db::$values["NAVIGATION_IMG3"] = $controller->monoValues["NAVIGATION_IMG3"][$i];
                    Pelican_Db::$values["NAVIGATION_TITLE"] = $controller->monoValues["NAVIGATION_TITLE"][$i];
                    Pelican_Db::$values["NAVIGATION_URL"] = $controller->monoValues["NAVIGATION_URL"][$i];
                    Pelican_Db::$values["NAVIGATION_PARAMETERS"] = $controller->monoValues["NAVIGATION_PARAMETERS"][$i];

                    Pelican_Db::$values["PAGE_NAVIGATION_ID"] = "";
                    Pelican_Db::$values["CONTENT_NAVIGATION_ID"] = "";
                    if (Pelican_Db::$values["NAVIGATION_URL"]) {
                        $parsed_url = parse_url(Pelican_Db::$values["NAVIGATION_URL"]);
                        parse_str($parsed_url['query'], $url_query);
                        if ($url_query["pid"]) {
                            Pelican_Db::$values["PAGE_NAVIGATION_ID"] = $url_query["pid"];
                        }
                        if ($url_query["cid"]) {
                            Pelican_Db::$values["CONTENT_NAVIGATION_ID"] = $url_query["cid"];
                        }
                    }
                    $oConnection->insertQuery("#pref#_navigation");
                }
            }
            /* suppression des références vers des contenus pour les anciennes versions */
            if ($ZONE_TEMPLATE_ID && $controller->publication) {
                $aBind[":ZONE_TEMPLATE_ID"] = $ZONE_TEMPLATE_ID;
                $aBind[":PAGE_ID"] = $controller->monoValues["PAGE_ID"];
                $aBind[":PAGE_VERSION"] = $controller->monoValues["PAGE_VERSION"];
                $sql = "UPDATE #pref#_navigation
				SET CONTENT_NAVIGATION_ID = NULL, PAGE_NAVIGATION_ID = NULL
				where ZONE_TEMPLATE_ID = :ZONE_TEMPLATE_ID
				AND PAGE_ID = :PAGE_ID
				AND PAGE_VERSION < :PAGE_VERSION";
                $oConnection->query($sql, $aBind);
            }
        }

        Pelican_Db::$values = $DBVALUES_SAVE;
    }

    public static function render(Pelican_Controller $controller)
    {
        $oConnection = Pelican_Db::getInstance();

        /* Initialisation */
        /* Droit de modification : par défaut oui */
        $modify = true;
        if ($controller->readO) {
            $modify = false;
        }

        $max = self::$max;
        $maxRow = self::$maxRow;

         $aMenu = array();

        $strSqlZone = "
		SELECT
		NAVIGATION_ID as \"id\",
		NAVIGATION_PARENT_ID as \"pid\",
		NAVIGATION_TITLE as \"lib\",
		NAVIGATION_ORDER as \"order\",
		n.*
		FROM
		#pref#_navigation as n
		inner join #pref#_page as p on (n.PAGE_ID=p.PAGE_ID and n.PAGE_VERSION=p.PAGE_DRAFT_VERSION and n.LANGUE_ID=p.LANGUE_ID)
		WHERE
		ZONE_TEMPLATE_ID=".$controller->zoneValues["ZONE_TEMPLATE_ID"]."
		and p.PAGE_ID=".$controller->zoneValues["PAGE_ID"]."
		and p.LANGUE_ID = ".$controller->zoneValues['LANGUE_ID'];

        $data = $oConnection->queryTab($strSqlZone);

        $oTree = Pelican_Factory::getInstance('Hierarchy', "header", "id", "pid");
        $oTree->addTabNode($data);
        $oTree->setOrder("order", "ASC");
        $i = -1;
        foreach ($oTree->aNodes as $menu) {
            if ($menu->level == 2) {
                $i ++;
                $aMenu[$i]["menu"] = $menu;
            } else {
                if ($menu->id) {
                    $aMenu[$i]["ssmenu"][] = $menu;
                }
            }
        }

        $i = 0;
        $strMenu = "";
        foreach ($aMenu as $ligne) {
            $i ++;
            $menu = $ligne["menu"];
            $j = 0;
            $strTemp = "";
            if (self::$second) {
                if ($ligne["ssmenu"]) {
                    foreach ($ligne["ssmenu"] as $ligne2) {
                        $j ++;
                        $menu2 = $ligne2;
                        $strTemp .= self::makeNavigation($menu2, $controller->zoneValues["ZONE_TEMPLATE_ID"], "nav".$controller->zoneValues["ZONE_TEMPLATE_ID"]."_".$i, $j, 2, $modify);
                        $strTemp .= "</div>";
                    }
                }
                if ($modify) {
                    $strTemp .= self::makeNavigation("+", $controller->zoneValues["ZONE_TEMPLATE_ID"], "nav".$controller->zoneValues["ZONE_TEMPLATE_ID"]."_".$i, ($j + 1), 2, $modify, $maxRow)."</div>";
                }
            }
            $strMenu .= self::makeNavigation($menu, $controller->zoneValues["ZONE_TEMPLATE_ID"], "nav".$controller->zoneValues["ZONE_TEMPLATE_ID"], $i, 1, $modify);
            $strMenu .= $strTemp;
            $strMenu .= "</div>";
        }
        if ($modify) {
            $strMenu .= self::makeNavigation("+", $controller->zoneValues["ZONE_TEMPLATE_ID"], "nav".$controller->zoneValues["ZONE_TEMPLATE_ID"], ($i + 1), 1, true, $max)."<br /></div>";
        }
        $return = $strMenu;
        if ($modify) {
            $params = new \stdClass();
            $params->NAVIGATION_TITLE = "%title%";
            $params->NAVIGATION_TITLE2 = "%title2%";
            $params->NAVIGATION_URL = "%url%";
            $nav1 = self::makeNavigation($params, $controller->zoneValues["ZONE_TEMPLATE_ID"], "nav".$controller->zoneValues["ZONE_TEMPLATE_ID"], "%i%", 1);
            if (self::$second) {
                $nav1 .= self::makeNavigation("+", $controller->zoneValues["ZONE_TEMPLATE_ID"], "nav".$controller->zoneValues["ZONE_TEMPLATE_ID"]."_%i%", 1, 2)."</div>";
            }
            $nav1 .= "</div>";
            $nav2 = self::makeNavigation($params, $controller->zoneValues["ZONE_TEMPLATE_ID"], "nav".$controller->zoneValues["ZONE_TEMPLATE_ID"]."_%i%", "%j%", 2)."</div>";
            $return .= "<input type=\"hidden\" id=\"nav".$controller->zoneValues["ZONE_TEMPLATE_ID"]."1\" value=\"".rawurlencode($nav1)."\">";
            $return .= "<input type=\"hidden\" id=\"nav".$controller->zoneValues["ZONE_TEMPLATE_ID"]."2\" value=\"".rawurlencode($nav2)."\">";
        }

        if (!self::$usage) {
            $head = $controller->getView()->getHead();
            $head->setScript(self::getJs());
            self::$usage = true;
        }

        return $return;
    }

    public function makeNavigation($params = "", $zoneTemplate, $id, $i, $level = 1, $modify = true, $max = 0, $multi_media = false)
    {
        global $url, $multiple;

        if ($level == 1 && self::$type1 != "") {
            $type = self::$type1;
        } elseif ($level == 2 && self::$type2 != "") {
            $type = self::$type2;
        } else {
            $type = self::$type;
        }
        $return = "<div id=\"".$id."_".$i."\" class=\"menu".$level."\">";
        if ($params->NAVIGATION_TITLE2) {
            $text = str_replace("&nbsp;", "", $params->NAVIGATION_TITLE2);
        } else {
            $text = str_replace("&nbsp;", "", $params->NAVIGATION_TITLE);
            if ($params->NAVIGATION_BOLD) {
                if ($type != 'plan_site') {
                    $text = Pelican_Html::b($text);
                } else {
                    $text = "&nbsp;&nbsp;&nbsp;&nbsp;".$text;
                }
            }
            if ($params->NAVIGATION_URL) {
                $urltemp = $params->NAVIGATION_URL;
                if (strpos($urltemp, "http") === false) {
                    $urltemp = "http://".$url['path']."/".$params->NAVIGATION_URL;
                }
                $text = Pelican_Html::a(array(
                    'href' => $urltemp,
                    'target' => "_blank",
                    'title' => t("CLICK_TO_SEE"),
                ), $text);
            }
        }

        if ($params == "+" && $modify) {
            $return .= Pelican_Html::img(array(
                'align' => "top",
                'alt' => t('POPUP_LABEL_ADD'),
                'border' => "0",
                'height' => "12",
                'width' => "12",
                'hspace' => "5",
                'src' => "/images/"."add_menu.gif",
                'style' => "cursor:pointer;",
                'onclick' => "addMenu(this,".$level.",'','','',".$max.")",
            ));
            if ($level > 1 && $multiple) {
                $return .= Pelican_Html::img(array(
                    'align' => "top",
                    'alt' => t("ADD_MENUS"),
                    'border' => "0",
                    'height' => "12",
                    'width' => "12",
                    'hspace' => "5",
                    'src' => "/images/"."add_menu2.gif",
                    'style' => "cursor:pointer;",
                    'onclick' => "addMenu2(this)",
                ));
            }
            $return .= "<script type=\"text/javascript\">document.".str_replace("-", "", $id)."_menuCount=".($i - 1).";</script>";
        } else {
            if ($modify) {
                $return .= Pelican_Html::img(array(
                    'align' => "top",
                    'alt' => t("UP"),
                    'border' => "0",
                    'height' => "12",
                    'width' => "12",
                    'src' => Pelican::$config["LIB_PATH"].Pelican::$config['LIB_LIST']."/images/ordre_plus.gif",
                    'style' => "cursor:pointer;",
                    'onclick' => "swapRow(this, 1)",
                ));
                $return .= Pelican_Html::img(array(
                    'align' => "top",
                    'alt' => t("DOWN"),
                    'border' => "0",
                    'height' => "12",
                    'width' => "12",
                    'src' => Pelican::$config["LIB_PATH"].Pelican::$config['LIB_LIST']."/images/ordre_moins.gif",
                    'style' => "cursor:pointer;",
                    'onclick' => "swapRow(this, -1)",
                ));
                $return .= Pelican_Html::img(array(
                    'align' => "top",
                    'alt' => t('POPUP_LABEL_DEL'),
                    'border' => "0",
                    'height' => "12",
                    'width' => "12",
                    'src' => "/images/del_menu.gif",
                    'style' => "cursor:pointer;",
                    'onclick' => "deleteMenu(this)",
                ));
                $return .= Pelican_Html::img(array(
                    'align' => "top",
                    'alt' => "",
                    'border' => "0",
                    'height' => "12",
                    'src' => "/images/xtrans.gif",
                    'width' => ($level * 12),
                ));
            }
            if ($modify) {
                //$hidField variable permettant de cacher un champ dans le formulair d'edition de la navigation
                if ($level == 1 && $type == 'plan_site') {
                    $hidField = 1;
                } else {
                    $hidField = 0;
                }
                $return .= Pelican_Html::img(array(
                    'align' => "top",
                    'alt' => "Editer",
                    'border' => "0",
                    'height' => "12",
                    'width' => "12",
                    'src' => "/images/edit_menu.gif",
                    'style' => "cursor:pointer;",
                    'onclick' => "editMenu(this, ".($type ? "'".$type."'" : "''").", '".$hidField."','".$zoneTemplate."', ".($multi_media ? "true" : "false").")",
                ));
            }
            $return .= "&nbsp;&nbsp;".Pelican_Html::span(array(
                'class' => "navigation",
                'name' => "NAVIGATION_TITLE2[]",
            ), $text);
            $return .= self::createInputNavigation("NAVIGATION_LEVEL", $level, "hidden");
            $return .= self::createInputNavigation("NAVIGATION_TITLE", $params->NAVIGATION_TITLE, "hidden");
            $return .= self::createInputNavigation("NAVIGATION_BOLD", $params->NAVIGATION_BOLD, "hidden");
            $return .= self::createInputNavigation("NAVIGATION_URL", $params->NAVIGATION_URL, "hidden");
            $return .= self::createInputNavigation("NAVIGATION_IMG", $params->NAVIGATION_IMG, "hidden");
            $return .= self::createInputNavigation("NAVIGATION_IMG2", $params->NAVIGATION_IMG2, "hidden");
            $return .= self::createInputNavigation("NAVIGATION_IMG3", $params->NAVIGATION_IMG3, "hidden");
            $return .= self::createInputNavigation("PAGE_NAVIGATION_ID", $params->PAGE_NAVIGATION_ID, "hidden");
            $return .= self::createInputNavigation("CONTENT_NAVIGATION_ID", $params->CONTENT_NAVIGATION_ID, "hidden");
            $return .= self::createInputNavigation("NAVIGATION_PARAMETERS", $params->NAVIGATION_PARAMETERS, "hidden");
            $return .= self::createInputNavigation("NAVIGATION_ZONE_TEMPLATE_ID", $zoneTemplate, "hidden");
        }
        $return .= '</div>';

        return $return;
    }

    /**
     * DESC.
     *
     * @return string
     *
     * @param string $name
     * @param string $value
     * @param string $type
     * @param string $class
     */
    protected function createInputNavigation($name, $value, $type = "texte", $class = "navigation")
    {
        if ($type == "checkbox") {
            $type = "text";
            $style = "width : 20px";

        }
        if ($type == "readonly") {
            $type = "text";
            $readonly = "readonly";
            $style = "cursor : pointer";
        }
        $return = Pelican_Html::input(array(
            "class" => $class,
            'type' => $type,
            'name' => $name."[]",
            'value' => $value,
            'style' => $style,
            'readonly' => $readonly,
            'checked' => null,
        ));

        return $return;
    }

    protected function getJs()
    {
        global $url;

        return "
function swapRow(object, sens) {
	var oDIV = getParentElement(object);
	var aID =analyzeMenu(oDIV);
	var strParentId = getParentId(aID) ;
	var strMenuCount = 'document.' + strParentId.replace('-','') + '_menuCount';
	var newNumber = parseInt(aID[aID.length -1])+parseInt(sens);
	var idMax = eval(strMenuCount) - newNumber;
	var id2 = strParentId + '_' + newNumber;

	// on détermine la ligne de destination
	if (document.getElementById(id2) && idMax >= 0) {
		oDIV2 = document.getElementById(id2);
		debut = oDIV2.innerHTML;
		fin = oDIV.innerHTML;
		oDIV.innerHTML = debut;
		oDIV2.innerHTML = fin;
	}
}

var oCountMenu = new Object();

function addMenu(object, level, title, url, title2,  max) {
	var oDIV = getParentElement(object);
	var aID = analyzeMenu(oDIV);
	var strParentId = getParentId(aID) ;
	var strMenuCount = 'document.' + strParentId.replace('-','') + '_menuCount';

	var bOk = true;
	if (max) {
		if(eval(strMenuCount)>=max) {
			alert('".t("YOU_CAN_CREATE", "js")."' + max + ' ".t("MENU", "js")."');
			bOk = false;
		}
	}
	if (bOk) {
		title = title ||'';
		url = url ||'';
		title2 = title2 ||'';
		id = document.getElementById(aID[0] + level).id;
		html = unescape(document.getElementById(aID[0] + level).value);
		html = html.replace('%i%',aID[1]);
		html = html.replace('%i%',aID[1]);
		html = html.replace('%j%',aID[2]);
		html = html.replace('%title%',title);
		html = html.replace('%url%',url);
		html = html.replace('%title2%',title2);

		aID[level] = parseInt(aID[level])+1;
		oDIV.id = aID.join('_');
		oDIV.insertAdjacentHTML('beforeBegin', html);
		eval('if (!' + strMenuCount +') ' + strMenuCount + '=0;');
		eval(strMenuCount + '++');
	}
}

function addMenu2(obj) {
	var args = new Object;
	args['multiple'] = true;
	args['host'] = '".$url['path']."';
	args['obj'] = obj;

	var arr = showModalDialog('/_/Popup/navigation?multiple=true', args, 'dialogWidth:650px; dialogHeight:360px; scroll:no; status:no; center:yes; help:no' );

	if (arr) {
		returnAddMenu2(obj, arr);
	}
}

function returnAddMenu2(obj, arr) {
	for (var i=0; i < arr.length; i++) {
		addMenu(obj, 2, arr[i].NAVIGATION_TITLE, arr[i].NAVIGATION_URL, arr[i].NAVIGATION_TITLE2)
	}

}

function editMenu(object, type, hidField, zone, multiMedia, url) {
	var oDIV = getParentElement(object);
	var args = new Object;
	var img = '';
	var img2 = '';
	args['obj'] = oDIV;
	if (oDIV.hasChildNodes()) {
		for(var i=0; i<oDIV.childNodes.length; i++) {
			child = oDIV.childNodes[i];
			if (child.name) {
				if (child.name == 'NAVIGATION_IMG[]') {
					img = child.value.replace('".Pelican::$config['MEDIA_VAR']."','');
				}
				if (child.name == 'NAVIGATION_IMG2[]') {
					img2 = child.value.replace('".Pelican::$config['MEDIA_VAR']."','');
				}
				if (child.name == 'NAVIGATION_IMG3[]') {
					img3 = child.value.replace('".Pelican::$config['MEDIA_VAR']."','');
				}
			}
		}
	}
	args['host'] = '".$url['path']."';
	if (!url) {
		url = '/_/Popup/navigation';
	}
	if (url.indexOf('?') == -1) {
		url += '?1=1';
	}
	if(hidField){
		var arr = showModalDialog(url + (type?'&type=' + type:'') + (img?'&img=' + img:'') + (img2?'&img2=' + img2:'') + (img3?'&img3=' + img3:'') + (hidField?'&hidField=' + hidField:'') + (zone?'&zone=' + zone:'') + (multiMedia?'&multiMedia=1':''), args, 'dialogWidth:650px; dialogHeight:360px; scroll:yes; status:no; center:yes; help:no' );
	}else{
		var arr = showModalDialog(url + (type?'&type=' + type:'') + (img?'&img=' + img:'') + (img2?'&img2=' + img2:'') + (img3?'&img3=' + img3:'') + (zone?'&zone=' + zone:'') + (multiMedia?'&multiMedia=1':''), args, 'dialogWidth:650px; dialogHeight:360px; scroll:no; status:no; center:yes; help:no' );
	}
}

function deleteMenu(object) {
	if (confirm('".t("CONFIRM")."')) {
		var oDIV = getParentElement(object);
		var aID =analyzeMenu(oDIV);
		var strParentId = getParentId(aID) ;
		var strMenuCount = 'document.' + strParentId.replace('-','') + '_menuCount';

		var parentDIV = getParentElement(oDIV);
		parentDIV.removeChild(oDIV);

		var countDIV = eval(strMenuCount);
		if (aID[2] != null) {
			parentDIV=document.getElementById(aID[0] + '_' + aID[1]);

			for (i=(parseInt(aID[2])+1); i <= countDIV; i++) {
				obj = document.getElementById(aID[0] + '_' + aID[1] + '_' + i);
				if (obj) {
					obj.id = aID[0] + '_' + aID[1] + '_' + (i-1);
				}
			}
		}
		eval(strMenuCount + '--');
	}
}

function analyzeMenu(object) {
	var aNiveau = new Array();
	id = object.id;
	aNiveau = id.split('_');
	return aNiveau;
}

function getParentId(aID) {
	var str = '';
	if (aID[2] == null) {
		str = aID[0];
	} else {
		str = aID[0] + '_' + aID[1];
	}
	return str;
}

function getParentElement(object) {
	var oParent;
	if (!object.parentElement) {
		oParent = object.parentNode;
	} else {
		oParent = object.parentElement;
	}
	return oParent;
}";
    }
}
