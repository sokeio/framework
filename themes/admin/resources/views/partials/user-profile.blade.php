<div class="nav-item dropdown">
    <a href="#" class="nav-link d-flex lh-1 text-reset" data-bs-toggle="dropdown" aria-label="Open user menu">
        <div class="d-none d-xl-block ps-2">
            <div>{{ auth()->user()->name }}</div>
            <div class="mt-1 small text-muted"></div>
        </div>
    </a>
    <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow ">
        <a sokeio:modal='{{ route('admin.system.user.change-password', ['dataId' => auth()->user()->id]) }}'
            sokeio:modal-title="Change password" class="dropdown-item">Change password</a>
        <div class="dropdown-divider"></div>

        <a href="{{ route('admin.logout') }}" class="dropdown-item">Logout</a>
    </div>
</div>
