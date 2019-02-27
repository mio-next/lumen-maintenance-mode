<?php
/**
 * Author: Enhe <info@wowphp.cn>
 * Date: 2019-02-27
 * Time: 10:07
 */

namespace EnHe\Lumen\MaintenanceMode;

use Illuminate\Support\ServiceProvider;
use EnHe\Lumen\MaintenanceMode\Console\UpCommand;
use EnHe\Lumen\MaintenanceMode\Console\DownCommand;
use EnHe\Lumen\MaintenanceMode\Http\Middleware\MaintenanceModeMiddleware;

class MaintenanceServiceProvider extends ServiceProvider
{
    /**
     * @var bool
     */
    protected $defer = true;

    /**
     *
     */
    public function register()
    {
        $this->app->middleware([MaintenanceModeMiddleware::class]);

        $this->app->singleton('command.up', function () {
            return new UpCommand();
        });

        $this->app->singleton('command.down', function () {
            return new DownCommand();
        });

        $this->commands($this->provides());
    }

    /**
     * @return array
     */
    public function provides()
    {
        return ['command.up', 'command.down'];
    }
}
