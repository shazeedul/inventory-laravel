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
     <div class="modal-body text-capitalize">
         <div class="my-2">
             <label for="title" class="fw-bold ">
                 {{ ___('Language Title') }}
                 <span class="text-danger">*</span>
             </label>
             <input type="txt" class="form-control" name="title" id="title"
                 value="{{ $item->title ?? old('title') }}" placeholder="{{ ___('Language Title') }}" required>
         </div>
         <div class="my-2">
             <label for="code" class="fw-bold ">
                 {{ ___('Language Short Code') }}
                 <span class="text-danger">*</span>
             </label>
             <input type="txt" class="form-control" name="code" id="code"
                 value="{{ $item->code ?? old('code') }}" placeholder="{{ ___('Language Code') }}" required>
         </div>
         <div class="my-2">
             <label for="build_from" class="fw-bold ">
                 {{ ___('Choose Builder File') }}
                 <span class="text-danger">*</span>
             </label>
             <select name="build_from" id="build_from" class="form-control">
                 <option value="" selected>-- {{ ___('No Builder File') }} --</option>
                 @foreach (getLocalizeLang() as $language)
                     <option value="{{ $language->code }}">{{ $language->title }}</option>
                 @endforeach
             </select>
         </div>

         <div class="my-2">
             <label for="code" class="fw-bold ">
                 {{ ___('Language Status') }}
             </label>
             <div class="form-check">
                 <input class="form-check-input" type="radio" name="status" id="status-active"
                     @checked(($item->status ?? old('status')) == 1) value="1">
                 <label class="form-check-label" for="status-active">
                     {{ ___('Active') }}
                 </label>
             </div>
             <div class="form-check">
                 <input class="form-check-input" type="radio" name="status" id="status-inactive"
                     @checked(($item->status ?? old('status')) == 0) value="0">
                 <label class="form-check-label" for="status-inactive">
                     {{ ___('Inactive') }}
                 </label>
             </div>

             {{-- <label for="status" class="fw-bold ">
                 {{ ___('Language Short Status') }}
             </label>
             <input type="redio" class="form-control" name="status" id="status"
                 value="{{ $item->status ?? old('status') }}" placeholder="{{ ___('Language status') }}"> --}}
         </div>
     </div>
     <div class="modal-footer text-capitalize">
         <button type="button" class="btn btn-danger" data-bs-dismiss="modal">{{ ___('Close') }}</button>
         <button class="btn btn-primary" type="submit">{{ ___('Save') }}</button>
     </div>
 </form>
