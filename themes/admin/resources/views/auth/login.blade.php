<div class="page page-center" x-data="{ showPass: false }">
    <div class="container container-tight py-4">
        <div class="text-center mb-4">
        </div>
        <div class="card card-md">
            <div class="card-body">
                <h2 class="h2 text-center mb-4">Login</h2>
                <form wire:submit.prevent='doWork()' autocomplete="off" novalidate>
                    <div class="mb-3">
                        <label class="form-label">Email address</label>
                        <input wire:model='username' type="email" class="form-control" placeholder="your@email.com"
                            autocomplete="off">
                        @error('username')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-2">
                        <label class="form-label">
                            Password
                            <span class="form-label-description">
                                <a href="{{ route('admin.forgot-password') }}">I forgot password</a>
                            </span>
                        </label>
                        <div class="input-group input-group-flat">
                            <input wire:model='password' type="password" :type="showPass ? 'text' : 'password'"
                                class="form-control" placeholder="Your password" autocomplete="off">
                            <span class="input-group-text">
                                <a href="#" class="link-secondary" @click=" showPass = !showPass "
                                    title="Show password" data-bs-toggle="tooltip">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                        viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M12 12m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                                        <path
                                            d="M22 12c-2.667 4.667 -6 7 -10 7s-7.333 -2.333 -10 -7c2.667 -4.667 6 -7 10 -7s7.333 2.333 10 7" />
                                    </svg>
                                </a>
                            </span>
                        </div>
                        @error('password')
                            <span class="error">{{ $message }}</span>
                        @enderror

                    </div>
                    <div class="mb-2">
                        <label class="form-check">
                            <input wire:model='isRememberMe' type="checkbox" class="form-check-input" />
                            <span class="form-check-label">Remember me on this device</span>
                        </label>
                        @error('account_error')
                            <span class="error">{{ $message }}</span>
                        @enderror

                    </div>
                    <div class="form-footer">
                        <button type="submit" class="btn btn-primary w-100">Sign in</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="text-center text-muted mt-3">
            Don't have account yet? <a href="{{ route('admin.sign-up') }}" tabindex="-1">Sign up</a>
        </div>
    </div>
</div>
