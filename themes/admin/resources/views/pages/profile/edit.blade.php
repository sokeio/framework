<div class="page-body">
    <div class="container-fluid">
        <div class="card">
            <div class="row g-0">
                <div class="col-3 d-none d-md-block border-end">
                    <div class="card-body">
                        <h4 class="subheader">Account settings</h4>
                        <div class="list-group list-group-transparent">
                            <a href="{{ route('admin.profile') }}"
                                class="list-group-item list-group-item-action d-flex align-items-center active">My
                                Account</a>
                        </div>
                    </div>
                </div>
                <div class="col d-flex flex-column">
                    <div class="card-body">
                        <h2 class="mb-4">My Account</h2>
                        <div class="row">
                            {!! form_render($itemManager, $form) !!}
                            <div class="col-6">

                                <h3 class="card-title mt-4">Password</h3>
                                <p class="card-subtitle">You can set a permanent password if you don't want to use
                                    temporary
                                    login
                                    codes.</p>
                                <div>
                                    <a byte:modal='{{ route('admin.user-change-password-form') }}'
                                        byte:modal-title="Change password" class="btn">
                                        Set new password
                                    </a>
                                </div>
                            </div>
                        </div>

                        <h3 class="card-title">Public profile</h3>
                        <p class="card-subtitle">Making your profile public means that anyone on the Dashkit network
                            will be
                            able to find
                            you.</p>
                        <div>
                            <label class="form-check form-switch form-switch-lg">
                                <input class="form-check-input" type="checkbox">
                                <span class="form-check-label form-check-label-on">You're currently visible</span>
                                <span class="form-check-label form-check-label-off">You're
                                    currently invisible</span>
                            </label>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent mt-auto">
                        <div class="btn-list justify-content-end">
                            <a href="{{route('admin.profile')}}" class="btn">
                                Cancel
                            </a>
                            <a wire:click='saveUser()' class="btn btn-primary">
                                Submit
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
