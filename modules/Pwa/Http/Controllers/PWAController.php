<?php

namespace Modules\Pwa\Http\Controllers;

use Illuminate\Routing\Controller;
use Modules\Pwa\Facades\Pwa;

class PWAController extends Controller
{
    /**
     * Manifest file for PWA
     *
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function manifest()
    {
        return response()->json(Pwa::manifestGenerate());
    }

    /**
     * Offline page view for PWA
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function offline()
    {
        return view('pwa::offline');
    }

    /**
     * init js file for PWA
     *
     * @return \Illuminate\Http\Response|mixed
     */
    public function initJs()
    {
        // return js with mime type
        return response(Pwa::initJs(), 200)->header('Content-Type', 'application/javascript');
    }

    /**
     * Service worker js file for PWA
     *
     * @return \Illuminate\Http\Response|mixed
     */
    public function serviceWorkerJs()
    {
        // return js with mime type
        return response(Pwa::serviceWorkerJs(), 200)->header('Content-Type', 'application/javascript');
    }
}
