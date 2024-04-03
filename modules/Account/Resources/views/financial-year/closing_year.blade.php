<form action="{{ route(config('theme.rprefix') . '.close.store') }}" method="POST" class="needs-validation modal-content"
    novalidate="novalidate" onsubmit="submitFormAxios(event)">
    @csrf
    <div class="header my-3 p-2 border-bottom">
        <h4>{{ config('theme.title') }}</h4>
    </div>
    <div class="modal-body">
        <fieldset class="mb-5 py-3 px-4 ">
            <legend>@localize('Financial Year Info'):</legend>
            <div class=" row">
                <div class="col-md-6">
                    <div class="form-group pt-1 pb-1">
                        <label for="name" class="font-black">
                            @localize('Financial Year')
                            <span class="text-danger">*</span>
                        </label>
                        <select class="form-control show-tick" name="year" id="year" required>
                            <option value="">@localize('Select')</option>
                            @foreach ($financialYears as $year)
                                <option value="{{ $year->id }}">
                                    {{ $year->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('name')
                            <p class="text-danger pt-2">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </fieldset>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">@localize('Close')</button>
        <button class="btn btn-primary" type="submit">@localize('Save')</button>
    </div>
</form>
