<span class="touch-sidebar-menu-admin" @click="miniSidebar=!miniSidebar">
    <i x-show="!miniSidebar" class="ti ti-chevrons-left fs-3"></i>
    <i x-show="miniSidebar" class="ti ti-chevrons-right  fs-3" style="display: none"></i>
</span>
<aside class="navbar navbar-vertical navbar-expand-md navbar-transparent border-end">
    <div class="container-fluid">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#sidebar-menu"
            aria-controls="sidebar-menu" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <h1 class="navbar-brand navbar-brand-autodark">
            @includeIf(applyFilters('THEME_ADMIN_LOGO', 'theme::partials.logo'))
        </h1>
        <div class="navbar-nav flex-row d-md-none">
            @php
                doAction('THEME_ADMIN_RIGHT');
            @endphp
            @includeIf('theme::partials.change-theme')
            @includeIf('theme::partials.notication')
            @includeIf(applyFilters('THEME_ADMIN_USER_PROFILE', 'theme::partials.user-profile'))
        </div>
        <div class="collapse navbar-collapse sidebar-menu" id="sidebar-menu" x-data="{
            initEvent() {
                setTimeout(() => {
                    this.$el.addEventListener('hidden.bs.collapse', function() {
                        document.body.classList.remove('sidebar-menu-opened');
                    })
                    this.$el.addEventListener('show.bs.collapse', function() {
                        document.body.classList.add('sidebar-menu-opened');
                    })
                })
            }
        }"
            x-init="initEvent()">
            {!! applyFilters('THEME_SIDEBAR_BEFORE', '') !!}
            {!! menuAdmin(true) !!}
            {!! applyFilters('THEME_SIDEBAR_AFTTER', '') !!}
            <div class=" text-center p-1 text-bold">
                @includeIf('theme::partials.copyright')
            </div>
        </div>
    </div>
</aside>
