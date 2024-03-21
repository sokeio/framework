<div class=" m-2 h-min-100 bg-white" x-data="{
    selected: 0,
    directories: [],
    files: [],
    directory: '',
    filePath: '',
    isShowUpload: false,
    async loadAll() {
        let rs = await $wire.getDiskAll();
        console.log(rs);
        this.directories = rs.directories ?? [];
        this.files = rs.files ?? [];
    },
    showModalUpload() {
        this.isShowUpload = !this.isShowUpload
    }
}" x-init="loadAll()">
    @include('sokeio::media-file.toolbar')
    <div class="border-top"></div>
    <div class="row g-2 p-1">
        <div class="col">
            <div class="overflow-auto">
                <div class="d-flex flex-wrap" style="min-width: 650px; max-height: 500px;">
                    <template x-for="directory in directories">
                        <div class="card text-center m-1 item-hover rounded-1" style="width: calc(20% - .5rem);">
                            <div class="card-body">
                                <i class="bi bi-folder2-open" style="font-size: 64px"></i>
                            </div>
                            <div class="card-footer text-body-secondary p-2" x-text="directory">

                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>
        <div class="col-3">
            <div class="card">
                <svg class="bd-placeholder-img card-img-top" width="100%" height="180"
                    xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder"
                    preserveAspectRatio="xMidYMid slice" focusable="false">
                    <title>Placeholder</title>
                    <rect width="100%" height="100%" fill="#20c997"></rect>
                </svg>
                <div class="card-body">
                    <h5 class="card-title">Card title</h5>
                    <p class="card-text">Some quick example text to build on the card title and make up the bulk of the
                        card's content.</p>
                </div>
            </div>
        </div>
    </div>
    @include('sokeio::media-file.modal')
</div>
