<div class="page page-center" x-data="{ showPass: false }">
    <div class="container container-tight py-4">
        <form class="card card-md" wire:submit.prevent='doWork()' autocomplete="off" novalidate>
            <div class="card-body">
                <h2 class="card-title text-center mb-4">Register Account</h2>
                <div class="mb-3">
                    <label class="form-label">Name</label>
                    <input wire:model='name' type="text" class="form-control" placeholder="Enter name">
                    @error('name')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label">Email address</label>
                    <input wire:model='email' type="email" class="form-control" placeholder="Enter email">
                    @error('email')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <div class="input-group input-group-flat">
                        <input wire:model='password' type="password" :type="showPass ? 'text' : 'password'"
                            class="form-control" placeholder="Password" autocomplete="off">
                        <span class="input-group-text">
                            <a href="#" class="link-secondary"  @click=" showPass = !showPass " title="Show password" data-bs-toggle="tooltip">
                                <!-- Download SVG icon from http://tabler-icons.io/i/eye -->
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
                <div class="mb-3">
                    <label class="form-check">
                        <input wire:model='agree' agree type="checkbox" class="form-check-input" />
                        <span class="form-check-label">Agree the <a href="#" tabindex="-1">terms
                                and policy</a>.</span>
                    </label>
                    @error('agree')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-footer">
                    <button type="submit" class="btn btn-primary w-100">Create new account</button>
                </div>
            </div>
        </form>
        <div class="text-center text-muted mt-3">
            Already have account? <a href="{{ route('admin.login') }}" tabindex="-1">Sign in</a>
        </div>
    </div>
</div>
