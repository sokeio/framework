@extends('theme::layouts.none')
@section('content')
    <div class="page page-center">
        <div class="container-tight py-4">
            <div class="empty">
                <div class="empty-header">500</div>
                <p class="empty-title">Oops… You just found an error page</p>
                <p class="empty-subtitle text-muted">
                    We are sorry but our server encountered an internal error
                </p>
                <div class="empty-action">
                    <a href="{{ url(adminUrl()) }}" class="btn btn-primary">
                        <!-- Download SVG icon from http://tabler-icons.io/i/arrow-left -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                            viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                            <path d="M5 12l14 0"></path>
                            <path d="M5 12l6 6"></path>
                            <path d="M5 12l6 -6"></path>
                        </svg>
                        Take me home
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
