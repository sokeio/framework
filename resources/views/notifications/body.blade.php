@foreach ($notifications as $item)
    <div class="row" x-data="{
        isRead: {{ !count($item->userRead)? 'false' : 'true' }},
        TickRead() {
            this.isRead = true;
            $wire.TickRead('{{ $item->id }}');
        }
    }">
        <div class="col-auto">
            <span class="avatar" @click="TickRead()"></span>
        </div>
        <div class="col">
            <div class="text-truncate">
                {{ $item->title }}
            </div>
            <div class="text-secondary">{{ $item->description }}</div>
        </div>
        <div class="col-auto align-self-center">
            <div class="badge bg-primary" x-show="!isRead"></div>
        </div>
    </div>
@endforeach
