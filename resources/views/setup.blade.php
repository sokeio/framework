<div class="page page-center">
    <div class="container container-tight py-4">
        {{ $step_index }}
        @includeIf('byte::setup.step-' . $step_index)
        <div class="row align-items-center mt-3">
            <div class="col-4">
                <div class="progress">
                    <div class="progress-bar" style="width: {{ (100 * $step_index) / $step_max }}%" role="progressbar"
                        aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"
                        aria-label="{{ (100 * $step_index) / $step_max }}% Complete">
                        <span class="visually-hidden">{{ (100 * $step_index) / $step_max }}% Complete</span>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="btn-list justify-content-end">
                    <button class="btn btn-warning {{ $step_index == 0 ? 'd-none' : '' }}" wire:click='stepBack()'>
                        Back Step
                    </button>
                    <button class="btn btn-primary {{ $step_index == $step_max ? 'd-none' : '' }}"
                        wire:click='stepNext()'>
                        Next Step
                    </button>
                    <button class="btn btn-purple {{ $step_index == $step_max ? '' : 'd-none' }}"
                        wire:click='stepNext()'>
                        Finish Setup
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
