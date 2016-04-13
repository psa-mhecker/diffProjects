<?php

use Citroen\Perso\Score\ScoreManager;
use Citroen\Perso\Score\IndicateurManager;

include_once ('config.php');
ini_set("display_errors", 1);
$aBind = array(
    ':SITE_ID' => $_SESSION[APP]['SITE_ID'],
    ':LANGUE_ID' => $_SESSION[APP]['LANGUE_ID'],
);

if(empty($aBind[':SITE_ID'])||empty($aBind[':SITE_ID'])){
	die("merci de visiter d'abord une page du site front avant de venir sur cette page");
}

//$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
$protocol = Pelican::$config["SERVER_PROTOCOL"];
$media_path =sprintf('%s://%s/%s/',$protocol,Pelican::$config['HTTP_MEDIA'],'design/frontend/mongo');

if (isset($_SESSION[APP]['LANGUE_ID']) && isset($_SESSION[APP]['SITE_ID'])) {
    $oConnection = Pelican_Db::getInstance();
    $sSQL = "SELECT * FROM  psa_perso_product WHERE SITE_ID=:SITE_ID";
    $aProducts = $oConnection->queryTab($sSQL, $aBind);

  $aVehiculesIndexed =  Pelican_Cache::fetch("Frontend/Citroen/Perso/VehiculesNamesInDebug",array($_SESSION[APP]['SITE_ID'],$_SESSION[APP]['LANGUE_ID']));
}



if ($_GET['v'] == 'get_psf') {
    include '_score_product.php';
    exit();
}

if ($_POST) {
    if (isset($_SESSION[APP]['USER'])) {
        $user_id = $_SESSION[APP]['USER']->getId();
    } else {
        $user_id = null;
    }
    //$session_id = session_id();
    $session_id = $_SESSION[APP]['perso_sess'];
    
    if (isset($_POST['product_score']) && !empty($_POST['product_score'])) {
        //product score form

        $data = array(
            'site_id' => $_SESSION[APP]['SITE_ID'],
            'time' => time(),
            'session_id' => $session_id,
            'user_id' => $user_id
        );

        
        $oSm = new ScoreManager();
        //($iUserId = null, $iProductId, $sSessionId, $fScore, $time,$siteId) {
        $oSm->pruneData(array(
            'session_id' => $session_id,
            'user_id' => $user_id
                )
        );
        foreach ($_POST['product_score'] as $aOneProductScore) {
            $aProductScoreToInsert = array_merge($aOneProductScore, $data);
            
            $oSm->saveProductScore(
                    $aProductScoreToInsert['user_id'], $aProductScoreToInsert['product'], $aProductScoreToInsert['session_id'], $aProductScoreToInsert['score'], $aProductScoreToInsert['time'], $aProductScoreToInsert['site_id']
            );
        }
        $posted = 'Scores ajoutés';
    }

    if (isset($_POST['indicateur']) && !empty($_POST['indicateur'])) {
        

        $oIm = new IndicateurManager();
        $oIm->pruneData(array(
            'session_id' => $session_id,
            'user_id' => $user_id
                )
        );
        $aIndicateurToInsert = array();
        foreach ($_POST['indicateur'] as $k => $v) {
            if (is_numeric($v) && $k != 'current_product') {
                $aIndicateurToInsert[$k] = (bool) (int) ($v);
            } else {
                $aIndicateurToInsert[$k] = $v;
            }
        }
        
        $oIm->saveIndicateur($user_id, $session_id, $_POST['indicateur']);
        $posted = 'Indicateur ajouté';
    }
    
}


?>

<!DOCTYPE html>
<html lang="fr">
    <head>
        <link rel="stylesheet" href="<?php print $media_path?>bootstrap.min.css">
        <link rel="stylesheet" href="<?php print $media_path?>bootstrap-theme.min.css">
        <link rel="stylesheet" href="<?php print $media_path?>jumbotron-narrow.css">
        <link rel="stylesheet" href="<?php print $media_path?>datepicker.css">
        <script src="<?php print $media_path?>jquery-1.10.1.min.js"></script>
        <script src="<?php print $media_path?>bootstrap.min.js"></script>
        <script src="<?php print $media_path?>bootstrap-datepicker.js"></script>
        <script src="<?php print $media_path?>bootstrap-datepicker.fr.js"></script>

        <style type="text/css">
            .subform_product_score{
                padding: 10px;
                background-color: #FBFFFA;
                margin-bottom: 20px !important; 
            }

        </style>
    </head>
    <body>
        <div class="container">
            <?php if($posted){ ?>
            <div class="alert alert-success"><?php print $posted;?></div>
            <?php } ?>
            <div class="panel-group" id="accordion">
                <!-- Score -->
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
                                Produits & Scores
                            </a>
                        </h4>
                    </div>
                    <div id="collapseOne" class="panel-collapse collapse in">
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <form role="form" method="post" class="form-horizontal" action="<?php print $_SERVER['PHP_SELF'] ?>">
                                        <div id="score_product_container">
                                            <?php include('_score_product.php') ?>
                                        </div>

                                        <button type="submit" class="btn btn-default btn-sm">Sauvegarder</button>
                                        <button id="add_product_score" type="button" class="btn btn-default btn-sm">
                                            <span class="glyphicon glyphicon-plus"></span> Ajouter un produit
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Indicateurs -->
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">
                                Indicateurs
                            </a>
                        </h4>
                    </div>
                    <div id="collapseTwo" class="panel-collapse collapse">
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <form  method="post" role="form" class="form-horizontal" action="<?php print $_SERVER['PHP_SELF'] ?>">
                                        <div class="form-group">
                                            <label for="email" class="col-sm-2 control-label">Email</label>
                                            <div class="col-sm-4">
                                                <input name="indicateur[email]" type="email" class="form-control" id="email">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label  for="pro" class="col-sm-2 control-label">Pro</label>
                                            <div class="col-sm-4">
                                                <select name="indicateur[pro]" id="pro" class="form-control">
                                                    <option value="">Sélectionnez une valeur</option>
                                                    <option value="Non">Non</option>
                                                    <option value="Oui">Oui</option>
                                                </select>

                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label name="indicateur[client]"  for="client" class="col-sm-2 control-label">Client</label>
                                            <div class="col-sm-4">
                                                <select id="client" name="indicateur[client]" class="form-control">
                                                    <option value="">Sélectionnez une valeur</option>
                                                    <option value="Non">Non</option>
                                                    <option value="Oui">Oui</option>
                                                </select>

                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label  for="recent_client" class="col-sm-2 control-label">Client recent</label>
                                            <div class="col-sm-4">
                                                <select name="indicateur[recent_client]" id="recent_client" class="form-control">
                                                    <option value="">Sélectionnez une valeur</option>
                                                    <option value="Non">Non</option>
                                                    <option value="Oui">Oui</option>
                                                </select>

                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label  for="product_owned" class="col-sm-2 control-label">Produit Possedé</label>
                                            <div class="col-sm-4">
                                                <select name="indicateur[product_owned]" id="product_owned" class="form-control">
                                                    <option value="">Sélectionnez un Véhicule</option>
                                                    <?php foreach ($aVehiculesIndexed as $k => $v) { ?>
                                                        <option value="<?php print $k; ?>"><?php print $v; ?></option>
                                                    <?php } ?>
                                                </select>

                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label  for="date_purchase" class="col-sm-2 control-label">Date d'achat</label>
                                            <div class="col-sm-4">                                              
                                                <div class="input-group date">
                                                    <input type="text" name="indicateur[date_purchase]" id="date_purchase" class="form-control">
                                                    <span class="glyphicon glyphicon-calendar"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label  for="extended_warranty" class="col-sm-2 control-label">Extension de Garantie</label>
                                            <div class="col-sm-4">
                                                <select  name="indicateur[extended_warranty]"  id="extended_warranty" class="form-control">
                                                    <option value="">Sélectionnez une valeur</option>
                                                    <option value="Non">Non</option>
                                                    <option value="Oui">Oui</option>
                                                </select>

                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label  for="service_contract" class="col-sm-2 control-label">Contrat Service</label>
                                            <div class="col-sm-4">
                                                <select name="indicateur[service_contract]" id="service_contract" class="form-control">
                                                    <option value="">Sélectionnez une valeur</option>
                                                    <option value="Non">Non</option>
                                                    <option value="Oui">Oui</option>
                                                </select>

                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label  for="current_product" class="col-sm-2 control-label">Produit en Cours</label>
                                            <div class="col-sm-4">
                                                <select name="indicateur[current_product]" id="current_product" class="form-control">
                                                    <option value="">Sélectionnez un produit</option>
                                                    <?php if (count($aProducts)) { ?>
                                                        <?php
                                                        foreach ($aProducts as $aOneProduct) {
                                                            ?>    
                                                            <option value="<?php print $aOneProduct['PRODUCT_ID'] ?>"><?php print $aOneProduct['PRODUCT_LABEL'] ?></option>
                                                        <?php } ?>
                                                    <?php } ?>
                                                </select>

                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label  for="project_open" class="col-sm-2 control-label">Mon Projet Ouvert</label>
                                            <div class="col-sm-4">
                                                <select name="indicateur[project_open]" id="project_open" class="form-control">
                                                    <option value="">Sélectionnez une valeur</option>
                                                    <option value="Non">Non</option>
                                                    <option value="Oui">Oui</option>
                                                </select>

                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-default btn-sm">Sauvegarder</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>    
        </div>    
        <script>
            $(document).ready(function() {
                $('#date_purchase').datepicker({
                    language: "fr",
                    format: "yyyy-mm-dd"
                });
                $('#add_product_score').click(function(e) {
                    e.preventDefault();
                    var i = $('.subform_product_score').length;
                    console.log($('#score_product_container'));
                    $.ajax({
                        type: 'GET', // Le type de ma requete
                        url: '/form_mongo.php?v=get_psf',
                        data: {'i': i},
                        success: function(response) {
                            if (response != '') {
                                $('#score_product_container').append(response);
                            }
                        }
                    });
                });
            });
        </script>
    </body>
</html>

