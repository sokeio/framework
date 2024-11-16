<div class="page page-center">
    <div class="container container-tight py-4">
        <div class="text-center mb-4">
            <a href="{{ url('/') }}" class="navbar-brand navbar-brand-autodark"><img
                    src="{{ setting('SOKEIO_SYSTEM_LOGO') || asset('platform/module/sokeio/sokeio.webp') }}"
                    class="rounded-2" height="100" alt="{{ setting('SOKEIO_SYSTEM_NAME', 'Sokeio Technology') }}"></a>
        </div>
        <form class="card card-md" method="get" autocomplete="off" novalidate="" wire:submit.prevent='doWork()'>
            <div class="card-body">
                <h2 class="card-title text-center mb-4">Forgot password</h2>
                <p class="text-secondary mb-4">Enter your email address and your password will be reset and emailed to
                    you.</p>
                <div class="mb-3">
                    <label class="form-label">Email address</label>
                    <input type="email" class="form-control" placeholder="Enter email">
                </div>
                <div class="form-footer">
                    <button type="submit" class="btn btn-primary w-100">
                        <!-- Download SVG icon from http://tabler-icons.io/i/mail -->
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="icon">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                            <path d="M3 7a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v10a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-10z">
                            </path>
                            <path d="M3 7l9 6l9 -6"></path>
                        </svg>
                        Send me new password
                    </button>
                </div>
            </div>
        </form>
        <div class="text-center text-secondary mt-3">
            Forget it, <a href="{{ route('admin.login') }}">send me back</a> to the sign in screen.
        </div>
    </div>
</div>
