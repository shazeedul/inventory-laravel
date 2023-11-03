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
            <legend>@localize('Supplier Info'):</legend>
            <div class=" row">
                <div class="col-md-6">
                    <div class="form-group pt-1 pb-1">
                        <label for="name" class="font-black">
                            @localize('Name')
                            <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control" name="name" id="name"
                            placeholder="@localize('Enter Name')" value="{{ isset($item) ? $item->name : old('name') }}"
                            required>
                        @error('name')
                            <p class="text-danger pt-2">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group pt-1 pb-1">
                        <label for="email" class="font-black">
                            @localize('Email')
                        </label>
                        <input type="email" class="form-control" name="email" id="email"
                            placeholder="@localize('Enter Email')" value="{{ isset($item) ? $item->email : old('email') }}"
                            required>
                        @error('email')
                            <p class="text-danger pt-2">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group pt-1 pb-1">
                        <label for="mobile_no" class="font-black">@localize('Mobile No')</label>
                        <input type="text" class="form-control arrow-hidden" name="mobile_no" id="mobile_no"
                            placeholder="@localize('Enter Mobile No')"
                            value="{{ isset($item) ? $item->mobile_no : old('mobile_no') }}">
                        @error('mobile_no')
                            <p class="text-danger pt-2">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6 py-1">
                    <div class="form-group pt-1 pb-1">
                        <label for="address" class="font-black">@localize('Address')</label>
                        <textarea name="address" id="address" class="form-control" placeholder="@localize('Enter your address')" style="min-height: 50px;">{{ isset($item) ? $item->address : old('address') }}</textarea>
                        @error('address')
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
