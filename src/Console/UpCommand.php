<?php
/**
 * Author: Enhe <info@wowphp.cn>
 * Date: 2019-02-27
 * Time: 10:15
 */

namespace EnHe\Lumen\MaintenanceMode\Console;

use Illuminate\Console\Command;

class UpCommand extends Command
{
    /**
     * @var string
     */
    protected $name = 'up';

    /**
     * @var string
     */
    protected $description = 'Exit the application from maintenance mode.';

    /**
     *
     */
    public function handle()
    {
        @unlink($this->laravel->storagePath() . '/framework/down');

        $this->info('Application is now live.');
    }
}
