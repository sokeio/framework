<div>
    <div wire:ignore class="offcanvas offcanvas-end" tabindex="-1" id="noticationUserManager"
        aria-labelledby="offcanvasEndLabel" aria-modal="true" role="dialog" x-data="{
            pageHtml: [],
            scrollPositionCache: 0,
            type: 0,
            getPageHtml() {
                return this.pageHtml.join('');
            },
            flgLoadMore: false,
            async loadMoreJs() {
                if (this.flgLoadMore) return;
                this.flgLoadMore = true;
                let html = await $wire.loadMore();
                if (html) {
                    this.pageHtml.push(html);
                }
                this.flgLoadMore = false;
            },
            initNotification() {
                const divElement = $refs.notificationBody;
                let self = this;
                divElement.addEventListener('scroll', function() {
                    const scrollPosition = divElement.scrollTop + divElement.clientHeight + 300;
                    const totalHeight = divElement.scrollHeight;
        
                    if (scrollPosition >= totalHeight && (this.scrollPositionCache != scrollPosition)) {
                        this.scrollPositionCache = scrollPosition;
                        setTimeout(async function() {
                            await self.loadMoreJs();
                        }, 0);
        
                    }
                });
                setTimeout(async function() {
                    await self.loadMoreJs();
                }, 0);
            },
            async changeType() {
                this.flgLoadMore = true;
                let html = await $wire.changeType(this.type);
                this.pageHtml = [];
                if (html) {
                    this.pageHtml.push(html);
                }
                this.flgLoadMore = false;
            },
            async readAll() {
                this.flgLoadMore = true;
                let html = await $wire.TickReadAll();
                this.pageHtml = [];
                if (html) {
                    this.pageHtml.push(html);
                }
                this.flgLoadMore = false;
            }
        }" x-init="initNotification">
        <div class="offcanvas-header bg-blue text--bold text-bg-blue p-2">
            <h2 class="offcanvas-title" id="offcanvasEndLabel">@lang('Notication')</h2>
            <button type="button" class="btn-close text-reset bg-white p-2 me-1" data-bs-dismiss="offcanvas"
                aria-label="Close"></button>
        </div>
        <div class="offcanvas-header p-2">
            <div class="row w-100">
                <div class="col-auto">
                    <select class="form-select" x-model="type" @change="changeType()">
                        <option value="0">@lang('All')</option>
                        <option value="1">@lang('Only Read')</option>
                        <option value="-1">@lang('Only not Read')</option>
                    </select>
                </div>
                <div class="col-auto ms-auto">
                    <a href="#" @click='readAll()' class=" btn btn-secondary">
                        @lang('Read All')
                    </a>
                </div>
            </div>
            <div x-show="flgLoadMore" x-text="flgLoadMore ? 'Loading...' : ''" x-cloak></div>
        </div>
        <div class="offcanvas-body p-2" x-ref="notificationBody">
            <div class="divide-y" x-html="getPageHtml()">
            </div>
        </div>
    </div>
</div>
