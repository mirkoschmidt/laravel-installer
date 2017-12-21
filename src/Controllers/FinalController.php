<?php

namespace MirkoSchmidt\LaravelInstaller\Controllers;

use Illuminate\Routing\Controller;
use MirkoSchmidt\LaravelInstaller\Helpers\EnvironmentManager;
use MirkoSchmidt\LaravelInstaller\Helpers\FinalInstallManager;
use MirkoSchmidt\LaravelInstaller\Helpers\InstalledFileManager;

class FinalController extends Controller
{
    /**
     * Update installed file and display finished view.
     *
     * @param InstalledFileManager $fileManager
     * @param FinalInstallManager $finalInstall
     * @param EnvironmentManager $environment
     *
     * @return \Illuminate\View\View
     */
    public function finish(InstalledFileManager $fileManager, FinalInstallManager $finalInstall, EnvironmentManager $environment)
    {
        $finalMessages = $finalInstall->runFinal();
        $finalStatusMessage = $fileManager->update();
        $finalEnvFile = $environment->getEnvContent();

        return view('vendor.installer.finished', compact('finalMessages', 'finalStatusMessage', 'finalEnvFile'));
    }
}
