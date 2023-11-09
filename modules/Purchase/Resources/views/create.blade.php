<x-app-layout>
    <x-card>
        <div class="row">
            <div class="col-md-4">
                <div class="md-3">
                    <label for="example-text-input" class="form-label">@localize('Purchase Date')</label>
                    <input class="form-control example-date-input" name="date" type="date" id="date">
                </div>
            </div>
            <div class="col-md-4">
                <div class="md-3">
                    <label for="example-text-input" class="form-label">@localize('Supplier')</label>
                    <select name="supplier_id" id="supplier_id" class="form-control">
                        @foreach ($suppliers as $key => $s)
                            <option value="{{ $s->id }}">{{ $s->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </x-card>
    @push('lib-styles')
        <link href="{{ nanopkg_asset('vendor/select2/select2.min.css') }}" rel="stylesheet" type="text/css" />
    @endpush
    @push('lib-scripts')
        <script src="{{ nanopkg_asset('vendor/select2/select2.min.js') }}"></script>
    @endpush
    @push('js')
        <script src="{{ module_asset('Purchase/js/app.min.js') }}"></script>
    @endpush
</x-app-layout>
