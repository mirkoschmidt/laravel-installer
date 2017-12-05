<?php

namespace RachidLaasri\LaravelInstaller\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Routing\Redirector;
use RachidLaasri\LaravelInstaller\Helpers\EnvironmentManager;
use RachidLaasri\LaravelInstaller\Requests\EnviromentRequest;

class EnvironmentController extends Controller
{
    /**
     * @var EnvironmentManager
     */
    protected $EnvironmentManager;

    /**
     * @param EnvironmentManager $environmentManager
     */
    public function __construct(EnvironmentManager $environmentManager)
    {
        $this->EnvironmentManager = $environmentManager;
    }

    /**
     * Display the Environment menu page.
     *
     * @return \Illuminate\View\View
     */
    public function environmentMenu()
    {
        $appKeys   = [];
        $envConfig = $this->EnvironmentManager->getEnvContent();
        foreach ($this->splitLines($envConfig) as $line) {
            $lineExplodes              = explode('=', $line);
            if (!empty($lineExplodes[0])) {
                $appKeys[$lineExplodes[0]] = isset($lineExplodes[1]) ? $lineExplodes[1] : '';
            }
        }

        return view('vendor.installer.environment', ['envConfig' => $appKeys, 'url' => url('/')]);
    }

    /**
     * Processes the newly saved environment configuration (Form Wizard).
     *
     * @param EnviromentRequest $request
     * @param Redirector $redirect
     * @return \Illuminate\Http\RedirectResponse
     */
    public function save(EnviromentRequest $request, Redirector $redirect)
    {
        $results = $this->EnvironmentManager->saveFileWizard($request);

        return $redirect->route('LaravelInstaller::database')
                        ->with(['results' => $results]);
    }

    /**
     * @param $output
     * @param $pattern
     * @return array
     */
    public function splitLines($output, $pattern = null)
    {
        $output = trim($output);

        return ((string) $output === '')
            ? [] : array_map('trim', preg_split($pattern ?? '{\r|\n}', $output));
    }
}
