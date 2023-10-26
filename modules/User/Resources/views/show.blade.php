        <div class="" style="padding: 50px 0">
            <div class="row gutters-sm">
                <div class="col-md-4 mb-3">
                    <div class="d-flex flex-column align-items-center text-center">
                        <img src="{{ $user->profile_photo_url }}" alt=" " class="rounded-circle border-success"
                            style="width: 140px; height: 140px; border: 3px solid;">
                        <div class="mt-3">
                            <h4>{{ $user->name }}</h4>
                            <p class="text-secondary mb-1">
                                @forelse (get_role($user) as $r)
                                    {{ $r->name }}<br>
                                @empty
                                    <span class="badge badge-danger">User role not found</span>
                                @endforelse
                            </p>
                            <p class="text-secondary mb-1">{{ $user->user_id }}</p>
                        </div>
                    </div>
                    @if ($user->nid)
                        <div class="text-center pt-3">
                            <img src="{{ image_url($user->nid, admin_asset('images/no-img.png')) }}"
                                class="w-100 shadow">
                        </div>
                    @endif
                </div>
                <div class="col-md-8">
                    <div class="row">
                        <div class="col-sm-3">
                            <h6 class="mb-0">@localize('Name')</h6>
                        </div>
                        <div class="col-sm-9 text-secondary">
                            {{ $user->name ?? 'N/A' }}
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-sm-3">
                            <h6 class="mb-0">@localize('Email')</h6>
                        </div>
                        <div class="col-sm-9 text-secondary">
                            {{ $user->email ?? 'N/A' }}
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-sm-3">
                            <h6 class="mb-0">@localize('Public Email')</h6>
                        </div>
                        <div class="col-sm-9 text-secondary">
                            {{ $user->public_email ?? 'N/A' }}
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-sm-3">
                            <h6 class="mb-0">@localize('Phone')</h6>
                        </div>
                        <div class="col-sm-9 text-secondary">
                            {{ $user->phone ?? 'N/A' }}
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-sm-3">
                            <h6 class="mb-0">@localize('Gender')</h6>
                        </div>
                        <div class="col-sm-9 text-secondary">
                            {{ $user->gender ?? 'N/A' }}
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-sm-3">
                            <h6 class="mb-0">@localize('Age')</h6>
                        </div>
                        <div class="col-sm-9 text-secondary">{{ $user->age ?? 'N/A' }}</div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-sm-3">
                            <h6 class="mb-0">@localize('Address')</h6>
                        </div>
                        <div class="col-sm-9 text-secondary">{{ $user->address ?? 'N/A' }}</div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-sm-3">
                            <h6 class="mb-0">@localize('Company')</h6>
                        </div>
                        <div class="col-sm-9 text-secondary">{{ $user->company ?? 'N/A' }}</div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-sm-3">
                            <h6 class="mb-0">@localize('Address')</h6>
                        </div>
                        <div class="col-sm-9 text-secondary">{{ $user->company_address ?? 'N/A' }}
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-sm-3">
                            <h6 class="mb-0">@localize('Status')</h6>
                        </div>
                        <div class="col-sm-9 text-secondary">
                            {{ $user->status->name ?? 'N/A' }}
                        </div>
                    </div>
                    <hr>


                    <div class="row">
                        <div class="col-sm-3">
                            <h6 class="mb-0">@localize('Account Created')</h6>
                        </div>
                        <div class="col-sm-9 text-secondary">
                            {{ $user->created_at }}
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">@localize('Close')</button>
        </div>
