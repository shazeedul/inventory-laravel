<x-setting::setting-card>
    {{-- create new Settings --}}
    <div class="card-box">
        <form action="{{ route(config('theme.rprefix') . '.store') }}" method="POST" class="needs-validation" novalidate>
            @csrf
            <h3 class="h3 mt-3">
                @localize('Create Setting New')
            </h3>
            <hr>

            <div class="row mt-2">
                <div class="col-md-3 form-group ">
                    <label class="" for="display_name">@localize('Name')</label>
                    <input id="display_name" class="form-control" type="text" name="display_name"
                        placeholder="@localize('Setting name ex: Admin Title')" required focus />
                    @error('display_name')
                        <p class="text-danger">
                            {{ $message }}
                        </p>
                    @enderror
                </div>
                <div class="col-md-3 form-group">
                    <label class="" for="key">@localize('Key')</label>
                    <input id="key" class="form-control" type="text" name="key"
                        placeholder="@localize('Setting key ex: admin_title')" required />
                    @error('key')
                        <p class="text-danger">
                            {{ $message }}
                        </p>
                    @enderror
                </div>
                <div class="col-md-3 form-group">
                    <label class="" for="type">@localize('Type')</label>
                    <select class="select2 form-control" name="type" id="type" required>
                        <option disabled selected>@localize('Choose Type')</option>
                        @foreach ($S_TYPES as $key => $item)
                            <option value="{{ $key }}">{{ $item }}</option>
                        @endforeach
                    </select>
                    @error('type')
                        <p class="text-danger">
                            {{ $message }}
                        </p>
                    @enderror
                </div>
                <div class="col-md-3 form-group">
                    <label class="" for="group">@localize('Group')</label>
                    <select class="select2Tag form-control" name="group" id="group" required>
                        <option selected disabled>@localize('Select Existing Group or Add New')</option>
                        @foreach ($groups as $group)
                            <option value="{{ $group }}">{{ $group }}</option>
                        @endforeach
                    </select>
                    @error('group')
                        <p class="text-danger">
                            {{ $message }}
                        </p>
                    @enderror
                </div>
                <div class="col-md-12 mt-2">
                    <a href="javaScript:void(0);" class="setting-extra-btn my-2 btn btn-outline-primary"
                        id="extraOptionDocOpen">
                        @localize('Extra Option Doc')
                    </a>
                    <a href="javaScript:void(0);" class="setting-extra-btn my-2 btn btn-primary mx-2 "
                        id="extraOptionDocClose" style="display: none">
                        @localize('Close Extra Option Doc')
                    </a>
                    <a href="javaScript:void(0);" class="setting-extra-btn my-2 btn btn-outline-primary mx-2"
                        id="extraOptionOpen">
                        @localize('Add Extra Option')
                    </a>
                    <a href="javaScript:void(0);" class="setting-extra-btn my-2 btn btn-primary mx-2 "
                        id="extraOptionClose" style="display: none">
                        @localize('Close Extra Option')
                    </a>
                    <a href="javaScript:void(0);" class="setting-extra-btn my-2 btn btn-outline-primary mx-2"
                        id="addNoteOpen">
                        @localize('Add Note')
                    </a>
                    <a href="javaScript:void(0);" class="setting-extra-btn my-2 btn btn-primary mx-2 " id="addNoteClose"
                        style="display: none">
                        @localize('Close Note')
                    </a>
                </div>
                <div class="col-md-12 form-group" id="extraOptionDetails" style="display: none">
                    <h5 class="">@localize('Extra option ( JSON DATA ) Doc')</h5>
                    <div class="container-fluid mt-3">
                        <div class="">
                            <h6>@localize('For Text-box Configuration:')</h6>
                            <hr>
                            <p>
                                @localize('You can set the minimum charset value and maximum charset value using the json.')
                            </p>
                            <code>
                                {
                                <br>
                                "min":"0",
                                <br>
                                "max":"255"
                                <br>
                                }
                            </code>
                        </div>
                        <div class="">
                            <h6>@localize('For Number Configuration:')</h6>
                            <hr>
                            <p>
                                @localize('You can set the minimum number and maximum number using the json.')
                            </p>
                            <code>
                                {
                                <br>
                                "min":"0",
                                <br>
                                "max":"255"
                                <br>
                                }
                            </code>
                        </div>
                        <div class="">
                            <h6>@localize('For Text-area Configuration:')</h6>
                            <hr>
                            <p>
                                @localize('You can set the minimum charset value and maximum charset value using the json.')
                            </p>
                            <code>
                                {
                                <br>
                                "minlength":"0", <br>
                                "maxlength":"255"<br>
                                }
                            </code>
                        </div>
                        <div class="">
                            <h6>@localize('For Code Editor Configuration:') }}</h6>
                            <hr>
                            <p>
                                @localize('You can use HTML,CSS, PHP, JavaScript, Java, C#, C, C++, Clojure, Go, Groovy,JSON, Scala,Ruby, XML, and others as languages.')
                            </p>
                            <code>
                                {
                                <br>
                                "theme":"dark",
                                <br>
                                "language":"html"
                                <br>
                                }
                            </code>
                        </div>
                        <div class="">
                            <h6>@localize('For Checkbox Configuration:')</h6>
                            <hr>
                            <p>
                                @localize('You can use Chekbox label and value using the json.')
                            </p>
                            <code>
                                {
                                <br>
                                "label":"key/value",
                                <br>
                                "label2":"key2/value2"
                                <br>
                                }
                            </code>
                        </div>
                        <div class="">
                            <h6>@localize('For Redio Configuration:')</h6>
                            <hr>
                            <p>
                                @localize('You can use redio label and value using the json.')
                            </p>
                            <code>
                                {
                                <br>
                                "label":"key/value",
                                <br>
                                "label2":"key2/value2"
                                <br>
                                }
                            </code>
                        </div>
                        <div class="">
                            <h6>@localize('For Select Dropdowm Configuration:')</h6>
                            <hr>
                            <p>
                                @localize('You can use Select Dropdowm label and value using the json.')
                            </p>
                            <code>
                                {
                                <br>
                                "label":"key/value",
                                <br>
                                "label2":"key2/value2"
                                <br>
                                }
                            </code>
                        </div>
                        <div class="">
                            <h6>@localize('For File Configuration:')</h6>
                            <hr>
                            <p>
                                @localize('You can use File accept using the json.')
                            </p>
                            <code>
                                {
                                <br>
                                "accept":".doc,.docx,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/pdf"
                                <br>
                                }
                            </code>
                        </div>
                        <div class="">
                            <h6>@localize('For image Configuration:')</h6>
                            <hr>
                            <p>
                                @localize('You can use image accept using the json.')
                            </p>
                            <code>
                                {
                                <br>
                                "accept":"image/*"
                                <br>
                                }
                            </code>
                        </div>
                    </div>
                    <hr>
                </div>

                <div class="col-md-12 form-group" id="extraOption" style="display: none">
                    <label class="" for="setting_details">@localize('Extra option ( JSON DATA )') </label>
                    <div id="setting_details" data-theme="clouds" data-language="json" class="ace_editor min_height_400"
                        name="details">{{ $details ?? '' }}</div>
                    <textarea name="details" id="setting_details_textarea" style="display: none">{{ $details ?? '' }}</textarea>
                    <hr>
                </div>

                <div class="col-md-12 form-group" id="addNote" style="display: none">
                    <label class="" for="setting_note">@localize('Add Note') </label>
                    <div id="setting_note" data-theme="clouds" data-language="html" class="ace_editor min_height_400"
                        name="details">{{ $note ?? '' }}</div>
                    <textarea name="note" id="setting_note_textarea" style="display: none">{{ $note ?? '' }}</textarea>
                </div>


            </div>
            <div class="mt-5">
                <button class="btn btn-primary btn-lg" type="submit">@localize('Create') </button>
            </div>
        </form>
    </div>
</x-setting::setting-card>
