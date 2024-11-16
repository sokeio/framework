<div>
    <h2 class="h3 text-center mb-3">
        Login to your account
    </h2>
    <form autocomplete="off" novalidate="" wire:submit.prevent='login()'>
        <div class="mb-3">
            <label class="form-label">Email address</label>
            <input type="email" class="form-control" placeholder="your@email.com" autocomplete="off" wire:model='email'>
            @error('email')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>
        <div class="mb-2">
            <label class="form-label">
                Password
                @if (Route::has('admin.forgot-password'))
                    <span class="form-label-description">
                        <a href="{{ route('admin.forgot-password') }}" title="I forgot password" wire:navigate.hover>
                            I forgot password
                        </a>
                    </span>
                @endif
            </label>
            <div class="input-group input-group-flat" x-data="{ showPass: false }">
                <input type="password" :type="showPass ? 'text' : 'password'" class="form-control"
                    placeholder="Your password" autocomplete="off" wire:model='password'>
                <span class="input-group-text">
                    <a @click=" showPass = !showPass " class="link-secondary link-decoration-none"
                        data-bs-toggle="tooltip" :title="showPass ? 'Hide password' : 'Show password'">
                        <i class="ti ti-eye-off" x-show="showPass"></i>
                        <i class="ti ti-eye" x-show="!showPass"></i>
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
    @if (Route::has('admin.register'))
        <div class="text-center text-secondary mt-3">
            Don't have account yet?
            <a href="{{ route('admin.register') }}" tabindex="-1" wire:navigate.hover>Sign up</a>
        </div>
    @endif
</div>
