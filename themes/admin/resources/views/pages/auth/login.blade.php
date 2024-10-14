<div class="page page-center">
    <div class="container container-tight py-4">
        <div class="text-center mb-4">
            <a href="." class="navbar-brand navbar-brand-autodark">
                {!! \Sokeio\Platform::logoFull('200px') !!}
            </a>
        </div>
        <div class="card card-md">
            <div class="card-body">
                <h2 class="h2 text-center mb-4">Login to your account</h2>
                <form autocomplete="off" novalidate="" wire:submit.prevent='login()'>
                    <div class="mb-3">
                        <label class="form-label">Email address</label>
                        <input type="email" class="form-control" placeholder="your@email.com" autocomplete="off"
                            wire:model='email'>
                        @error('email')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-2">
                        <label class="form-label">
                            Password
                            <span class="form-label-description">
                                <a href="./forgot-password.html" wire:navigate>I forgot password</a>
                            </span>
                        </label>
                        <div class="input-group input-group-flat" x-data="{ showPass: false }">
                            <input type="password" :type="showPass ? 'text' : 'password'" class="form-control"
                                placeholder="Your password" autocomplete="off" wire:model='password'>
                            <span class="input-group-text">
                                <a href="#" @click=" showPass = !showPass " class="link-secondary"
                                    data-bs-toggle="tooltip" :title="showPass ? 'Hide password' : 'Show password'">
                                    <svg style="display: none" x-show="showPass" xmlns="http://www.w3.org/2000/svg"
                                        width="24" height="24" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="icon">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M10.585 10.587a2 2 0 0 0 2.829 2.828" />
                                        <path
                                            d="M16.681 16.673a8.717 8.717 0 0 1 -4.681 1.327c-3.6 0 -6.6 -2 -9 -6c1.272 -2.12 2.712 -3.678 4.32 -4.674m2.86 -1.146a9.055 9.055 0 0 1 1.82 -.18c3.6 0 6.6 2 9 6c-.666 1.11 -1.379 2.067 -2.138 2.87" />
                                        <path d="M3 3l18 18" />
                                    </svg>

                                    <svg x-show="!showPass" xmlns="http://www.w3.org/2000/svg" width="24"
                                        height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                        <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0"></path>
                                        <path
                                            d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6">
                                        </path>
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
                            <input type="checkbox" class="form-check-input" wire:model='isRememberMe'>
                            <span class="form-check-label">Remember me on this device</span>
                        </label>
                    </div>
                    @error('account_error')
                        <span class="error">{{ $message }}</span>
                    @enderror
                    <div class="form-footer">
                        <button type="submit" class="btn btn-primary w-100">Sign in</button>
                    </div>
                </form>
            </div>
            <div class="hr-text">or</div>
            <div class="card-body">
                <div class="row">
                    <div class="col"><a href="#" class="btn w-100">
                            <!-- Download SVG icon from http://tabler-icons.io/i/brand-github -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="icon text-github">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <path
                                    d="M9 19c-4.3 1.4 -4.3 -2.5 -6 -3m12 5v-3.5c0 -1 .1 -1.4 -.5 -2c2.8 -.3 5.5 -1.4 5.5 -6a4.6 4.6 0 0 0 -1.3 -3.2a4.2 4.2 0 0 0 -.1 -3.2s-1.1 -.3 -3.5 1.3a12.3 12.3 0 0 0 -6.2 0c-2.4 -1.6 -3.5 -1.3 -3.5 -1.3a4.2 4.2 0 0 0 -.1 3.2a4.6 4.6 0 0 0 -1.3 3.2c0 4.6 2.7 5.7 5.5 6c-.6 .6 -.6 1.2 -.5 2v3.5">
                                </path>
                            </svg>
                            Login with Github
                        </a></div>
                    <div class="col"><a href="#" class="btn w-100">
                            <!-- Download SVG icon from http://tabler-icons.io/i/brand-x -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="icon text-x">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <path d="M4 4l11.733 16h4.267l-11.733 -16z"></path>
                                <path d="M4 20l6.768 -6.768m2.46 -2.46l6.772 -6.772"></path>
                            </svg>
                            Login with X
                        </a></div>
                </div>
            </div>
        </div>
        <div class="text-center text-secondary mt-3">
            Don't have account yet? <a href="{{ route('admin.register') }}" tabindex="-1" wire:navigate>Sign up</a>
        </div>
    </div>
</div>
