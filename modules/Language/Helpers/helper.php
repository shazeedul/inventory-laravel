<?php

use Modules\Language\Entities\Language;

function localize(string|null $key, string $default_value = null, string $locale = null) : string|null
{
    if (is_null($key) || $key == "" || $key == " " || empty($key)) return '';
    return Modules\Language\Facades\Localizer::localize($key, $default_value, $locale);
}

function ___(string|null $key, string $default_value = null, string $locale = null) : string|null
{
    if (is_null($key) || $key == "" || $key == " " || empty($key)) return '';
    return Modules\Language\Facades\Localizer::localize($key, $default_value, $locale);
}



function getLocalizeLang()
{

    return Language::cacheDataQuery('_active_', Language::active()->get());
}

