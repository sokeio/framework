<div>
    <template x-if="isShowUpload">
        <div class="modal modal-blur fade show " tabindex="-1" style="display: block;" aria-modal="true"
            role="dialog">
            <div class="modal-dialog modal-dialog-centered" role="document" @click.outside="isShowUpload = false">
                <div class="modal-content">
                    <div class="dropzone p-8 text-center fw-bold">
                        @lang('Drop files here to upload')
                    </div>
                </div>
            </div>
        </div>
    </template>
</div>
