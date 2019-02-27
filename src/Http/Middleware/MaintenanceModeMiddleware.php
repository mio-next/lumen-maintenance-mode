<?php
/**
 * Author: Enhe <info@wowphp.cn>
 * Date: 2019-02-27
 * Time: 10:30
 */

namespace EnHe\Lumen\MaintenanceMode\Http\Middleware;

use Laravel\Lumen\Application;

class MaintenanceModeMiddleware
{
    /**
     * @var Application
     */
    protected $app;

    /**
     * MaintenanceModeMiddleware constructor.
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * @param $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, \Closure $next)
    {
        if ($this->isDownForMaintenance()) {
            $data = $this->getDownPayload();

            return response()->json([
                'code' => 5210, 'message' => $data['message'] ?? 'Server is down.',
                'expired' => $data['expired'] ?: null
            ]);
        }

        return $next($request);
    }

    /**
     * @return bool
     */
    protected function isDownForMaintenance()
    {
        $maintenanceFile = $this->app->storagePath() . '/framework/down';

        if (! file_exists($maintenanceFile)) {
            return false;
        }

        $downPayload = $this->getDownPayload();

        if ($downPayload && $downPayload['expired'] && time() >= $downPayload['expired']) {
            @unlink($maintenanceFile);
            return false;
        }

        return true;
    }

    /**
     * @return mixed
     */
    protected function getDownPayload()
    {
        $maintenanceFile = $this->app->storagePath() . '/framework/down';

        return json_decode(file_get_contents($maintenanceFile), true);
    }
}
