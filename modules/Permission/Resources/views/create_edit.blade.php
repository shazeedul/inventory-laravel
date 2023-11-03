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
        <div class=" form-group mb-3 mx-0 pb-3 row">
            <label for="group" class="col-lg-3 col-form-label ps-0 ">
                @localize('Permission Group')
                <span class="text-danger">*</span>
            </label>
            <div class="col-lg-9 p-0">
                <select name="group" id="group" class="form-control" style="width: 100%">
                    @foreach ($groups as $key => $g)
                        <option @selected(($item->group ?? old('group')) == $g) value="{{ $g }}">{{ $g }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class=" form-group mb-3 mx-0 pb-3 row">
            <label for="name" class="col-lg-3 col-form-label ps-0 ">
                @localize('Permission Name')
            </label>
            <div class="col-lg-9 p-0">
                <input type="txt" class="form-control" name="name" id="name"
                    value="{{ $item->name ?? old('name') }}" placeholder="@localize('Permission Name')">
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">@localize('Close')</button>
        <button class="btn btn-primary" type="submit">@localize('Save')</button>
    </div>
</form>
