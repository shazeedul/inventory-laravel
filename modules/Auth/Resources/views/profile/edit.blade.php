<x-app-layout>
    <div class="tile">

        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-4">
                                <div>
                                    <h6 class="fs-17 fw-semi-bold mb-0"> @localize('Profile')</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('user-profile-information.update') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <table class="table table-hover">
                                <tr>
                                    <td>
                                        <label for="name" class="mb-0 fw-bold">
                                            @localize('Name')
                                            <span class="text-danger">*</span></label>
                                    </td>
                                    <td>
                                        <input class="form-control input-py" id="name" type="text"
                                            name="name" value="{{ auth()->user()->name ?? old('name') }}" required>
                                        @error('name')
                                            <span class="text-danger"> {{ $message }}</span>
                                        @enderror
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label for="email" class="mb-0 fw-bold"> @localize('Email')
                                            <span class="text-danger">*</span></label>
                                    </td>
                                    <td>
                                        <input class="form-control input-py" id="email" type="text"
                                            name="email" value="{{ auth()->user()->email }}"
                                            placeholder=" @localize('Email')" required>
                                        @error('email')
                                            <span class="text-danger"> {{ $message }}</span>
                                        @enderror
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label for="phone" class="font-black  fw-bold">
                                            @localize('Phone')
                                        </label>
                                    </td>
                                    <td>
                                        <input type="number" class="form-control arrow-hidden" name="phone"
                                            id="phone" value="{{ auth()->user()->phone }}"
                                            placeholder="@localize('Enter phone')" required>
                                        @error('phone')
                                            <p class="text-danger pt-2">{{ $message }}</p>
                                        @enderror
                                    </td>
                                </tr>

                                <tr>
                                    <td>
                                        <label for="gender" class="font-black  fw-bold">
                                            @localize('Gender')
                                        </label>
                                    </td>
                                    <td>
                                        <select class="form-control show-tick" name="gender" id="gender" required>
                                            <option selected disabled>-- @localize('Select Gender') --</option>
                                            @foreach (App\Models\User::genderList() as $gender)
                                                <option {{ selected(auth()->user()->gender, $gender) }}>
                                                    {{ $gender }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('gender')
                                            <p class="text-danger pt-2">{{ $message }}</p>
                                        @enderror
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label for="age" class="font-black  fw-bold"> @localize('Age') </label>
                                    </td>
                                    <td>
                                        <input type="number" class="form-control arrow-hidden" name="age"
                                            id="age" placeholder="@localize('Enter your age')"
                                            value="{{ auth()->user()->age }}" required>
                                        @error('age')
                                            <p class="text-danger pt-2">{{ $message }}</p>
                                        @enderror
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label for="address" class="font-black  fw-bold"> @localize('Address') </label>
                                    </td>
                                    <td>
                                        <textarea name="address" id="address" class="form-control" placeholder="@localize('Enter your address')" style="min-height: 100px;"
                                            required>{{ auth()->user()->address }}</textarea>
                                        @error('address')
                                            <p class="text-danger pt-2">{{ $message }}</p>
                                        @enderror
                                    </td>
                                </tr>
                            </table>
                            <div class="row">
                                <div class="col-md-12 text-center mt-5">
                                    <button type="submit" class="btn btn-primary input-py float-right">
                                        @localize('Update')
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
