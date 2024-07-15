<x-app-layout>
    <x-card>
        <div class="alert alert-warning text-center" role="alert">
            <h3>@localize('warning'): @localize('Please don\'t change any Predefined Account').</h3>
            <p>
                @localize('if you are not sure about your accounts otherwise you will get wrong accounting report in your system').
            </p>
        </div>
        <form id="leadForm" action="{{ route(config('theme.rprefix') . '.store') }}" method="POST">
            @csrf
            <div class="row">
                @foreach ($predefines as $key => $p)
                    <div class="col-md-6 px-2 py-2">
                        <label for="{{ $key }}" class="fw-bold py-2">
                            {{ localize($p['title'] ?? implode(' ', explode('_', $key))) }}
                            <span class="text-danger">*</span>
                        </label>
                        <select name="predefines[{{ $key }}]" id="{{ $key }}"
                            class="form-control select2">
                            @foreach ($levels[$p['level']] ?? [] as $coa)
                                <option value="{{ $coa->id }}" @selected(isset($raw_predefines[$key]) ? $raw_predefines[$key]->chart_of_account_id == $coa->id : $p['chart_of_account_id'] == $coa->id)>
                                    {{ $coa->name }}
                                </option>
                            @endforeach
                        </select>
                        @error($key)
                            <div class="error text-danger text-start">{{ $message }}</div>
                        @enderror
                    </div>
                @endforeach
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" id="create_submit">{{ localize('save') }}</button>
                </div>
            </div>
        </form>
    </x-card>
    @push('lib-styles')
        <link href="{{ nanopkg_asset('vendor/select2/select2.min.css') }}" rel="stylesheet" type="text/css" />
    @endpush
    @push('lib-scripts')
        <script src="{{ nanopkg_asset('vendor/select2/select2.min.js') }}"></script>
    @endpush
    @push('js')
        <script>
            $(document).ready(function() {
                // init select2
                $(".select2").select2({
                    width: '100%',
                });
            });
        </script>
    @endpush
</x-app-layout>
