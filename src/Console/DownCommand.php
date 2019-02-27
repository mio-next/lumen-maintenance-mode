<?php
/**
 * Author: Enhe <info@wowphp.cn>
 * Date: 2019-02-27
 * Time: 10:21
 */

namespace EnHe\Lumen\MaintenanceMode\Console;

use Carbon\Carbon;
use Illuminate\Console\Command;

class DownCommand extends Command
{
    /**
     * @var string
     */
    protected $signature = 'down {--message=: The message for the maintenance mode.}
                            {--expired=: The number of seconds after which the request may be retried.}';

    /**
     * @var string
     */
    protected $description = 'Set the application into maintenance mode';

    /**
     *
     */
    public function handle()
    {
        file_put_contents(
            $this->laravel->storagePath() . '/framework/down',
            json_encode($this->getDownFilePayload(), JSON_PRETTY_PRINT)
        );

        $this->info('Application is now in maintenance mode.');
    }

    /**
     * @return array
     */
    private function getDownFilePayload()
    {
        return [
            'expired' => $this->getExpiredTime(),
            'message' => $this->option('message'),
            'time' => Carbon::now()->getTimestamp()
        ];
    }

    /**
     * @return int|null
     */
    private function getExpiredTime()
    {
        $expired = $this->option('expired');

        if (is_numeric($expired) && $expired > 0) {
            return Carbon::now()->addSecond($expired)->getTimestamp();
        }

        return null;
    }
}
