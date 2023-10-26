<x-app-layout>
    <div class="row">
        <div class="col-md-4 col-xl-6 ">
            <div class="card py-5" style="color: #4b515d; border-radius: 35px">
                <div class="card-body p-4 ">
                    {{-- welcome gretting --}}
                    <h3 class="text-center">@localize('Welcome Back'), {{ Auth::user()->name }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-xl-3 my-2">
            <weather-component api-key="{{ setting('weather.open_weather_api_key') }}"
                asserts-path="{{ nanopkg_asset('image') }}"></weather-component>
        </div>
        <div class="col-md-4 col-xl-3  my-2">
            <div class="card py-5" style="color: #4b515d; border-radius: 35px">
                <div class="card-body p-4 ">
                    <clock-component></clock-component>
                </div>
            </div>
        </div>

        <div class="col-12 col-xl-12 stretch-card">
            <div class="row flex-grow-1">
                {{-- <div class="col-md-12 my-2">
                        <h3>Portfolio Summary</h3>
                    </div>
                    <x-dashbord-summary-card :items="$dashboard['portfolio']" /> --}}



            </div>
        </div>
    </div>

</x-app-layout>
