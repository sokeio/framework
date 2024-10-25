<div>
    <h2 class="h3 text-center mb-3">
        Create new account
    </h2>
    <form autocomplete="off" novalidate="" wire:submit.prevent="register()">
        <div class="mb-3">
            <label class="form-label">Name</label>
            <input type="text" class="form-control" placeholder="Enter name" wire:model='name'>
            @error('name')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>
        <div class="mb-3">
            <label class="form-label">Email address</label>
            <input type="email" class="form-control" placeholder="Enter email" wire:model='email'>
            @error('email')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>
        <div class="mb-3">
            <label class="form-label">Password</label>
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
        <div class="mb-3">
            <label class="form-check">
                <input type="checkbox" class="form-check-input">
                <span class="form-check-label">Agree the <a href="#" tabindex="-1">terms
                        and policy</a>.</span>
            </label>
        </div>
        @error('account_error')
            <span class="error">{{ $message }}</span>
        @enderror
        <div class="form-footer">
            <button type="submit" class="btn btn-primary w-100">Create new account</button>
        </div>
    </form>
    <div class="text-center text-secondary mt-3">
        Already have account?
        <a href="{{ route('admin.login') }}" tabindex="-1" wire:navigate.hover>Sign
            in</a>
    </div>
</div>
