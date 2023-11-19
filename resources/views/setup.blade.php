<div class="page page-center">
    <div class="container container-tight py-2">
        @includeIf('byte::setup.step-' . $step_index)
        @if ($errors->any())
            <div class="alert alert-danger mt-3">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="row align-items-center mt-3">
            <div class="col-4">
                <div class="progress ">
                    <div class="progress-bar" style="width: {{ (100 * $step_index) / $step_max }}%" role="progressbar"
                        aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"
                        aria-label="{{ (100 * $step_index) / $step_max }}% @lang('Complete')">
                        <span class="visually-hidden">{{ (100 * $step_index) / $step_max }}% @lang('Complete')</span>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="btn-list justify-content-end">
                    <button class="btn btn-warning {{ $step_index == 0 ? 'd-none' : '' }}" wire:click='stepBack()'     wire:loading.attr="disabled">
                        @lang('Back Step')
                    </button>
                    <button class="btn btn-primary {{ $step_index == $step_max ? 'd-none' : '' }}"     wire:loading.attr="disabled"
                        wire:click='stepNext()'>
                        @lang('Next Step')
                    </button>
                    <button class="btn btn-purple {{ $step_index == $step_max ? '' : 'd-none' }}"     wire:loading.attr="disabled"
                        wire:click='stepFinish()'>
                        @lang('Finish Setup')
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
