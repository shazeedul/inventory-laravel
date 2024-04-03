<form action="{{ config('theme.update') ?? route(config('theme.rprefix') . '.store') }}" method="POST"
    class="needs-validation modal-content" novalidate="novalidate" enctype="multipart/form-data"
    onsubmit="submitFormAxios(event)">
    @csrf
    @if (config('theme.update'))
        @method('PUT')
    @endif
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
                        <input type="text" class="form-control" name="name" id="name"
                            placeholder="@localize('Enter Financial Year')" value="{{ isset($item) ? $item->name : old('name') }}"
                            required>
                        @error('name')
                            <p class="text-danger pt-2">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group pt-1 pb-1">
                        <label for="status" class="font-black">
                            @localize('Status')
                            <span class="text-danger">*</span>
                        </label>
                        <select class="form-control show-tick" name="status" id="status" required>
                            <option value="">@localize('Select Status')</option>
                            <option value="1" {{ isset($item) && $item->status == 1 ? 'selected' : '' }}>
                                @localize('Active')</option>
                            <option value="0" {{ isset($item) && $item->status == 0 ? 'selected' : '' }}>
                                @localize('Inactive')</option>
                        </select>
                        @error('status')
                            <p class="text-danger pt-2">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group pt-1 pb-1">
                        <label for="start_date" class="font-black">
                            @localize('Start Date')
                        </label>
                        <input type="date" class="form-control" name="start_date" id="start_date"
                            placeholder="@localize('Enter Start Date')"
                            value="{{ isset($item) ? $item->start_date : old('start_date') }}">
                        @error('start_date')
                            <p class="text-danger pt-2">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group pt-1 pb-1">
                        <label for="end_date" class="font-black">
                            @localize('End Date')
                        </label>
                        <input type="date" class="form-control" name="end_date" id="end_date"
                            placeholder="@localize('Enter Start Date')"
                            value="{{ isset($item) ? $item->end_date : old('end_date') }}">
                        @error('end_date')
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
