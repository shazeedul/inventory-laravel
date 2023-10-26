@props(['manifest' => config('pwa.manifest'), 'manifestJson' => route('pwa.manifest.json'), 'initJs' => route('pwa.init.js'), 'serviceWorkerJs' => route('pwa.service-worker.js')])

<!-- Web Application Manifest -->
<link rel="manifest" href="{{ $manifestJson }}">
<!-- Chrome for Android theme color -->
<meta name="theme-color" content="{{ $manifest['theme_color'] }}">

<!-- Add to homescreen for Chrome on Android -->
<meta name="mobile-web-app-capable" content="{{ $manifest['display'] == 'standalone' ? 'yes' : 'no' }}">
<meta name="application-name" content="{{ $manifest['short_name'] }}">
<link rel="icon" sizes="{{ data_get(end($manifest['icons']), 'sizes') }}"
    href="{{ data_get(end($manifest['icons']), 'src') }}">
<!-- Add to homescreen for Safari on iOS -->
<meta name="apple-mobile-web-app-capable" content="{{ $manifest['display'] == 'standalone' ? 'yes' : 'no' }}">
<meta name="apple-mobile-web-app-status-bar-style" content="{{ $manifest['status_bar'] }}">
<meta name="apple-mobile-web-app-title" content="{{ $manifest['short_name'] }}">
<link rel="apple-touch-icon" href="{{ data_get(end($manifest['icons']), 'src') }}">

@foreach ($manifest['splash'] as $splash)
    <link href="{{ $splash['path'] }}"
        media="(device-width: {{ $splash['width'] }}) and (device-height: {{ $splash['height'] }}) and (-webkit-device-pixel-ratio: {{ $splash['ratio'] }})"
        rel="apple-touch-startup-image" />
@endforeach
<!-- Tile for Win8 -->
<meta name="msapplication-TileColor" content="{{ $manifest['background_color'] }}">
<meta name="msapplication-TileImage" content="{{ data_get(end($manifest['icons']), 'src') }}">
<meta name="service-worker-url" content="{{ $serviceWorkerJs }}">
<script src="{{ $initJs }}"></script>
