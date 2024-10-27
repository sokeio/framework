<div class="row row-cards">
    @if ($themes)
        @foreach ($themes as $theme)
            <div class="col-6 col-md-4 col-lg-3">
                <div class="card card-sm">
                    <a href="#" class="d-block"><img src="https://preview.tabler.io/static/photos/beautiful-blonde-woman-relaxing-with-a-can-of-coke-on-a-tree-stump-by-the-beach.jpg" class="card-img-top"></a>
                    <div class="card-body">
                      <div class="d-flex align-items-center">
                        <span class="avatar me-3 rounded" style="background-image: url(https://preview.tabler.io/static/avatars/000m.jpg)"></span>
                        <div>
                          <div> {{ $theme->name }}</div>
                          <div class="text-secondary">3 days ago</div>
                        </div>
                      </div>
                    </div>
                  </div>
            </div>
        @endforeach
    @endif
</div>
