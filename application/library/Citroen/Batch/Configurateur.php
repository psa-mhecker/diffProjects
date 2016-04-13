<?php

namespace Citroen\Batch;

use Itkg\Batch;
use Citroen\Perso\Score\IndicateurManager;
use Citroen\Perso\Flag\Detail;
require_once 'External/Cpw/GRCOnline/Abstract.php';
require_once 'External/Cpw/GRCOnline/Customer.php';
require_once 'External/Cpw/GRCOnline/Customermanager.php';
require_once 'External/Cpw/GRCOnline/Customerxmlloader.php';
require_once 'External/Cpw/GRCOnline/Customerfields.php';
require_once 'External/Cpw/GRCOnline/CustomerAt/User.php';
require_once 'External/Cpw/GRCOnline/CustomerAt/Vehicle.php';
require_once 'External/Cpw/GRCOnline/CustomerAt/Dealer.php';
require_once 'External/Cpw/GRCOnline/CustomerAt/InterestVehicle.php';
require_once 'External/Cpw/GRCOnline/CustomerAt/UserMock.php';
require_once 'External/Cpw/GRCOnline/CustomerAt/Subscription.php';

define('PUBLIC_PATH', \Pelican::$config['APPLICATION_LIBRARY'] . "/Citroen/User");

/**
 * Classe IPass
 *
 * Batch IPass : Permet de mettre à jour les règles de macros et 
 * micros-éligibilités depuis le WS getApplications
 * 
 * @author Pascal DENIS <pascal.denis@businessdecision.com>
 */
class Configurateur extends Batch
{
    public function execute()
    {
        
        $oConnection = \Pelican_Db::getInstance();
        $sSQL = "SELECT SITE_ID FROM  #pref#_site";
        $oMongo = new \MongoClient(
                \Pelican::$config['MONGODB_URI'], \Pelican::$config['MONGODB_PARAMS']
        );
        $aSites = $oConnection->queryTab($sSQL);
        
        $days = 5; //days ago
        //script will delete all entrie older tha 5 days
        $bttf = time()-(86400*$days);
        //clean old entries
        //print "Debut de nettoyage de base Mongo";
        error_log("Debut de nettoyage de base Mongo");
        echo "\n Debut de nettoyage de base Mongo\n";
        $sCleanFunc ='function(bttf){db.user_actions.remove({\'time\':{$lt:bttf}});}';
        $aCleanReturn = $oMongo->__get(\Pelican::$config["MONGODB_PARAMS"]['db'])->execute(
                $sCleanFunc, 
                array(
                    $bttf,
                )
        );
        //print $aCleanReturn['retval'];
        $sRecalculateFunc = 'function(site_id,score_collection_name){
                     //fetch the rest
                    var pipeline = [
                        {
                            "$match":{
                                \'products_scores\':{$exists:true,$not:{$size:0}},
                                \'products_scores.site_id\' : site_id
            
                            }
                        },
                    {
                        "$group":{
                            \'_id\':{
                                session_id:\'$session_id\',
                                product: \'$products_scores.product\'
                
                            },
                            score_max:{$max:\'$products_scores.score\'}
                        }
                    }
     ];
     
    results  = db.user_actions.runCommand("aggregate", {pipeline: pipeline});
    return results;
    /*if(results.length>0){
        for(var resultIndex in results){
            product = results[resultIndex]._id.product[0];
            session_id = results[resultIndex]._id.session_id;
            if(results[resultIndex]._id.user_id){
                user_id = results[resultIndex]._id.user_id;
                query ={\'product\':product,$or:[{\'session_id\':session_id},{\'user_id\':user_id}]}
            }else{
                query ={\'product\':product,\'session_id\':session_id}
            }
            new_score = results[resultIndex].score_max;
            db.getCollection(score_collection_name).update(
                query,
                {$set:{\'score\':new_score}}
                );   
            }
            }
        }*/
        return pipeline;
     }//end function
                  ';
        if(count($aSites)){
            foreach($aSites as $aOneSite){

                $return = $oMongo->__get(\Pelican::$config["MONGODB_PARAMS"]['db'])->execute(
                    $sRecalculateFunc, 
                    array(
                        $aOneSite['SITE_ID'],
                        $bttf,
                        \Pelican::$config['MONGODB_CITROEN']['PERSO_SCORE_COLLECTION_NAME']
                    )
                );
                //print $return['retval'];

            }
        }
        error_log("Fin de nettoyage de base Mongo");
        echo "Fin de nettoyage de base Mongo\n";

        //Mise à jour des indicateurs
        $indicateur = new IndicateurManager();
        $users = $indicateur->getAllUsers();

        if($users){
            $processes =  array(
                'trancheScoreCalcul',
                'bestScore',
                'getRecentProduct',
                'isClient',
                'isNotClient',
                'isClientBdi',
                'isNotClientBdi',
                'productOwned',
                'isClientAndHasVU',
                'isRecentClient',
                'isRecentClientBdi',
                'isNotRecentClientBdi',
                'datePurchase',
                'preferedProduct',
                'saveIndicateur'
            );

            foreach($users as $user){
                $infos = array();
                if(is_array($processes) && !empty($processes)){
                    $infos = $indicateur->getAllByUser(null,$user);
                    $email = '';
                    if($infos){
                        foreach($infos as $info){
                            $email = $info['email'];
                            $user_id = $info['user_id'];
                        }
                    }
                    if($email){
                        $userBdi = new  \Cpw_GRCOnline_CustomerAt_User();
                        $userBdi->loadUser($email);
                        //echo $email."\n";
                        $userInfo['IS_CUSTOMER'] = $userBdi->IsCustomer;
                        if($userBdi->LastBoughtVehicle  != null) {
                            $userInfo['LAST_BOUGHT'] = $userBdi->LastBoughtVehicle->UserSinceDate;
                        }
                        if($userBdi->LastMainDrivedVehicle  != null) {
                            $userInfo['PRODUCT_OWNED'] = $userBdi->LastMainDrivedVehicle->LCDV;
                            $userInfo['DATE_PURCHASE'] = $userBdi->LastMainDrivedVehicle->ReleaseDate;
                        }
                        $indicateur->saveIndicateur($user, null, $userInfo);
                    }
                    $detail = new Detail();
                    Detail::$idBatch = $user;
                    $detail->init();
                    foreach($processes as $process){
                        if($detail->$process()){
                            break;
                        }
                    }
                }
            }
        }
    }
}
