<?php

namespace App\Http\Controllers;

use Modules\Language\Facades\Localizer;

class TestController extends Controller
{
    public function index()
    {
        // $local = Localizer::getLocalizeData('en');
        // $data  = [];
        // dd('dadnakj');
        // for ($i = 1000; $i < 1500; $i++) {
        //     foreach ($local as $key => $value) {
        //         $data[$key . '_' . $i] = $value;
        //     }
        // }
        // Localizer::bulkStore($data, 'en');
        Localizer::autoTranslate('en', 'bn');
        // dd(Localizer::getLocalizeData('bn'));
    }
}
