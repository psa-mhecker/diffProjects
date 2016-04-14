<?php
/**
 * Alimentation des logs à partir des events Restler
 *
 * @author Vincent Paré <vincent.pare@businessdecision.com>
 */

namespace MediaApi\v1\Logging;

use Pelican;
use Psr\Log\LogLevel;
use Monolog\Logger;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Formatter\LineFormatter;

class Probe
{
    protected $restler;
    protected $logger;
    protected $endpointStartTime;
    
    /**
     * Constructeur
     *
     * @param Restler $restler Objet Restler source de données
     * @param float $endpointStartTime Timestamp de lancement du script
     */
    public function __construct($restler, $endpointStartTime = null)
    {
        $this->restler = $restler;
        $this->logger = $this->getLogger();
        $this->endpointStartTime = $endpointStartTime;
    }
    
    /**
     * Instanciation du logger
     */
    protected function getLogger()
    {
        $dateFormat = "Y-m-d H:i:s";
        $output = "%channel%|%datetime%|%level_name%|%message%|%trace%|%app%|%pid%|%session%|%referer%|%brand%"
            ."|%method%|%url%|%request%|%response%|%requestSize%|%responseSize%|%chrono%\n";
        $formatter = new LineFormatter($output, $dateFormat);
        
        $handler = new RotatingFileHandler(Pelican::$config["LOG_ROOT"] . '/ws_media.log');
        $handler->setFormatter($formatter);
        
        $logger = new Logger('ws_media');
        $logger->pushHandler($handler);
        $logger->pushProcessor(array($this, 'metaProcessor'));
        
        return $logger;
    }
    
    /**
     * Processor Monolog qui ajoute les métadonnées à stocker dans les logs
     *
     * @param array $record
     */
    public function metaProcessor($record)
    {
        // Type de réponse
        $code = $this->restler->responseCode;
        $isError = $code >= 200 && $code < 300 ? false : true;
        
        // Calcul du temps d'exécution global
        $endTime = microtime(true);
        $execTime = $endTime - $this->endpointStartTime;
        
        // Assemblage paramètres
        $parameters = '';
        if (isset($this->restler->apiMethodInfo)) {
            $parameters = array_combine(
                array_keys($this->restler->apiMethodInfo->arguments),
                $this->restler->apiMethodInfo->parameters
            );
        }
        
        $app = isset(Pelican::$config['API']['MEDIA']['APP']) ? Pelican::$config['API']['MEDIA']['APP'] : '';
        $brand = isset(Pelican::$config['API']['MEDIA']['BRANDID']) ? Pelican::$config['API']['MEDIA']['BRANDID'] : '';
        $trace = isset($this->restler->exception) ? $this->restler->exception->getTrace() : '';
        
        $meta = array(
            "trace"        => $trace,
            "app"          => $app,
            "pid"          => '',
            "session"      => '',
            "referer"      => isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '',
            "brand"        => $brand,
            "method"       => $this->restler->requestMethod,
            "url"          => $_SERVER['REQUEST_URI'],
            "request"      => $this->restler->requestMethod !== 'GET' || $isError ? $parameters : '',
            "response"     => $isError ? $this->restler->_responseData : '',
            "requestSize"  => $isError || !isset($_SERVER['CONTENT_LENGTH']) ? '' : $_SERVER['CONTENT_LENGTH'],
            "responseSize" => $isError ? '' : strlen($this->restler->_responseData),
            "chrono"       => $execTime,
        );
        
        $record = array_merge($record, $meta);
        return $record;
    }
    
    /**
     * Réagit à l'événement Restler onComplete
     */
    public function onComplete()
    {
        $level = LogLevel::INFO;
        $message = "Success";
        
        if (isset($this->restler->exception)) {
            $message = $this->restler->exception->getMessage();
            $stage = $this->restler->exception->getStage();
            $details = $this->restler->exception->getDetails();
            if ($stage === 'route') {
                $level = LogLevel::WARNING;
                $message = "No route";
            } elseif (isset($details['dbError'])) {
                $level = LogLevel::ERROR;
            } elseif ($this->restler->exception->getCode() == 500) {
                $level = LogLevel::ERROR;
            }
        }
        
        $this->logger->log($level, $message);
    }
}
