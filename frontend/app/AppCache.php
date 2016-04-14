<?php

require_once __DIR__ . '/AppKernel.php';

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Itkg\CombinedHttpCache\HttpCache\HttpCache;
use PsaNdp\MappingBundle\Utils\UserAgent;

/**
 * Class AppCache
 */
class AppCache extends HttpCache
{
    /**
     * {@inheritdoc}
     *
     * @api
     */
    public function handle(Request $request, $type = HttpKernelInterface::MASTER_REQUEST, $catch = true)
    {
        $config['config_file'] = __DIR__."/../../backend/application/configs/Wurfl/1.5/wurfl-config.php";
        $this->userAgent = new UserAgent($request, $config, $this->getStore()->getCacheClient());
        // Set device type header for Open Orchestra
        $device =    $this->userAgent->getDeviceType();
        if($request->query->get('isMobile') === 'true') {
             $device = 'mobile';
        }
        if($request->query->get('isTablet') === 'true') {
            $device = 'tablet';
        }

        $request->headers->set('x-ua-device',$device );

        // Now set variant depending on internal/external locality of user (PSA / not PSA) thanks to the single and sole param we have for this separation
        $request->headers->set('x-psa-locality', getenv('SYMFONY__HTTP__MEDIA'));

        $response = parent::handle($request, $type, $catch);

        return $response;
    }

}
