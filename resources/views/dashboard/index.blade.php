<div class="page-dashboard">
    <div class="page-header d-print-none mt-2">
        <div class="container-fluid">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <h2 class="page-title">
                        @lang('Dashboard')
                    </h2>
                </div>
                <!-- Page title actions -->
                <div class="col-auto ms-auto d-print-none">
                    <div class="btn-list">
                        <a class="btn btn-primary p-2"
                            sokeio:modal="{{ route('admin.dashboard-setting') }}" sokeio:modal-size="modal-lg"
                            sokeio:modal-title="@lang('Setting')">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-home-cog"
                                width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <path d="M9 21v-6a2 2 0 0 1 2 -2h1.6"></path>
                                <path d="M20 11l-8 -8l-9 9h2v7a2 2 0 0 0 2 2h4.159"></path>
                                <path d="M18 18m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0"></path>
                                <path d="M18 14.5v1.5"></path>
                                <path d="M18 20v1.5"></path>
                                <path d="M21.032 16.25l-1.299 .75"></path>
                                <path d="M16.27 19l-1.3 .75"></path>
                                <path d="M14.97 16.25l1.3 .75"></path>
                                <path d="M19.733 19l1.3 .75"></path>
                            </svg>
                            @lang('Setting')
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="page-body mt-2">
        <div class="container-fluid">
            <livewire:sokeio::widget.widget-list wire:key='widget-list-{{ $soNumberLoading }}' />
        </div>
    </div>
</div>
