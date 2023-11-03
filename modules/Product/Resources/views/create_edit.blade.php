<form action="{{ config('theme.update') ?? route(config('theme.rprefix') . '.store') }}" method="POST"
    class="needs-validation modal-content" novalidate="novalidate" enctype="multipart/form-data"
    onsuumit="suumitFormAxios(event)">
    @csrf
    @if (config('theme.update'))
        @method('PUT')
    @endif
    <div class="header my-3 p-2 border-bottom">
        <h4>{{ config('theme.title') }}</h4>
    </div>
    <div class="modal-body">
        <fieldset class="mb-5 py-3 px-4 ">
            <legend>@localize('Product Info'):</legend>
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
                        <label for="supplier_id" class="font-black">
                            @localize('Supplier')
                            <span class="text-danger">*</span>
                        </label>
                        <select name="supplier_id" id="supplier_id" class="form-control" style="width: 100%">
                            @foreach ($suppliers as $key => $s)
                                <option @selected(($item->supplier_id ?? old('supplier_id')) == $s->id) value="{{ $s->id }}">{{ $s->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('supplier_id')
                            <p class="text-danger pt-2">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group pt-1 pb-1">
                        <label for="category_id" class="font-black">
                            @localize('Category')
                            <span class="text-danger">*</span>
                        </label>
                        <select name="category_id" id="category_id" class="form-control" style="width: 100%">
                            @foreach ($categories as $key => $c)
                                <option @selected(($item->category_id ?? old('category_id')) == $c->id) value="{{ $c->id }}">{{ $c->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <p class="text-danger pt-2">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group pt-1 pb-1">
                        <label for="unit_id" class="font-black">
                            @localize('Unit')
                            <span class="text-danger">*</span>
                        </label>
                        <select name="unit_id" id="unit_id" class="form-control" style="width: 100%">
                            @foreach ($units as $key => $u)
                                <option @selected(($item->unit_id ?? old('unit_id')) == $u->id) value="{{ $u->id }}">{{ $u->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('unit_id')
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
