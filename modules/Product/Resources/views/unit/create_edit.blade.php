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
             <legend>@localize('Unit Info'):</legend>
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
             </div>
         </fieldset>
     </div>
     <div class="modal-footer">
         <button type="button" class="btn btn-danger" data-bs-dismiss="modal">@localize('Close')</button>
         <button class="btn btn-primary" type="submit">@localize('Save')</button>
     </div>
 </form>
