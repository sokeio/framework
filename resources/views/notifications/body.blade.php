@foreach ($notifications as $item)
<div x-data="{
        isRead: {{ !count($item->userRead)? 'false' : 'true' }},
        TickRead() {
            this.isRead = true;
            $wire.TickRead('{{ $item->id }}');
        }
    }" @click="TickRead()">
    @if($item->view)
        @includeif($item->view,['item'=>$item])
    @elseif($item->type)
        @includeif($item->type,['item'=>$item])
    @else
        <div class="row" >
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
    @endif
</div>
    
@endforeach
