<ul class="navbar-nav">
    @foreach ($items as $item)
        {!! $item->render() !!}
    @endforeach
</ul>
