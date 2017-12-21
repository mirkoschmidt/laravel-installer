<?php

namespace MirkoSchmidt\LaravelInstaller\Helpers;

use Exception;
use Illuminate\Support\Facades\Artisan;
use Symfony\Component\Console\Output\BufferedOutput;

class FinalInstallManager
{
    /**
     * Run final commands.
     *
     * @return string
     */
    public function runFinal()
    {
        $outputLog = new BufferedOutput;

        $this->publishVendorAssets($outputLog);

        return $outputLog->fetch();
    }

    /**
     * Publish vendor assets.
     *
     * @param BufferedOutput $outputLog
     * @return BufferedOutput|array
     */
    public static function publishVendorAssets($outputLog)
    {
        try{
            Artisan::call('vendor:publish', ['--all' => true], $outputLog);
        }
        catch(Exception $e){
            return static::response($e->getMessage(), $outputLog);
        }

        return $outputLog;
    }

    /**
     * Return a formatted error messages.
     *
     * @param $message
     * @param string $status
     * @param BufferedOutput $outputLog
     * @return array
     */
    private static function response($message, $outputLog, $status = 'danger')
    {
        return [
            'status' => $status,
            'message' => $message,
            'dbOutputLog' => $outputLog->fetch()
        ];
    }
}
