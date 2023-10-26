<div class="no-internet-popup" id="noInternetPopup">
    <div class="no-internet-icon"></div>
    <div class="no-internet-text">@localize('No Internet Connection')</div>
</div>

<div id="userOnlinePopup" class="user-online-popup">
    <div class="user-online-icon"></div>
    <div class="user-online-text">@localize('Online')</div>
</div>

@push('js')
    <script src="{{ nanopkg_asset('js/online-status.min.js') }}"></script>
@endpush
