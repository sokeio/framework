<div>
    <template x-if="isShowUpload">
        <div class="modal modal-blur fade show " tabindex="-1" style="display: block;" aria-modal="true" role="dialog">
            <div class="modal-dialog" role="document" @click.outside="isShowUpload = false">
                <div class="modal-content modal-content border border-2 rounded-1">
                    <div class="dropzone m-1 p-5 text-center fw-bold">
                        @lang('Drop files here to upload')
                    </div>
                </div>
            </div>
        </div>
    </template>
    <template x-if="isShowFolderCreate">
        <div class="modal modal-blur fade show " tabindex="-1" style="display: block;" aria-modal="true"
            role="dialog">
            <div class="modal-dialog" x-data="{
                name: '',
                async createFolder() {
                    let rs = await $wire.createFolder(this.name);
                    this.isShowFolderCreate = false;
                    this.name = '';
                    this.setDataMedia(rs);
                }
            }" role="document"
                @click.outside="isShowFolderCreate = false">
                <div class="modal-content border border-2 rounded-1">
                    <div class="modal-header">
                        <h5 class="modal-title">@lang('Create Folder')</h5>
                        <button type="button" class="btn-close" @click="isShowFolderCreate = false"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row mb-3 align-items-end">
                            <div class="col">
                                <label class="form-label">@lang('Name')</label>
                                <input x-model="name" type="text" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer p-2">
                        <button type="button" class="btn btn-primary" @click="createFolder()">
                            @lang('Create')
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </template>
</div>
