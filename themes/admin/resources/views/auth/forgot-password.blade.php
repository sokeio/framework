<div class="page page-center">
    <div class="container container-tight py-4">
        <form class="card card-md" wire:submit.prevent='doWork()' autocomplete="off" novalidate>
            <div class="card-body">
                <h2 class="card-title text-center mb-4">{!! $page_title ?? '' !!}</h2>
                <p class="text-muted mb-4">Enter your email to recover your password
                </p>
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="text" class="form-control" placeholder="Số điện thoại">
                </div>
                <div class="form-footer">
                    <button type="submit" class="btn btn-primary w-100">
                        <!-- Download SVG icon from http://tabler-icons.io/i/mail -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                            viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                            <path d="M3 5m0 2a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v10a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2z">
                            </path>
                            <path d="M3 7l9 6l9 -6"></path>
                        </svg>
                        Send code.
                    </button>
                </div>
            </div>
        </form>
        <div class="text-center text-muted mt-3">
            Forget it, <a href="{{ route('admin.login') }}">send me back</a> to the sign in screen.
        </div>
    </div>
</div>
