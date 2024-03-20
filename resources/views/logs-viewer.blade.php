<div class="page-body mt-2">
    <div class="container-fluid" x-data="{
        logs: [],
        page: 1,
        searchText: '',
        logType: '',
        async setFilePath(path) {
            this.logs = await this.$wire.setFilePath(path);
        },
        async loadLogs() {
            this.logs = await this.$wire.loadLogs(this.page++);
        },
        countByType(type) {
            return this.logs.filter(log => log['levelType'] == type).length;
        },
        getLogs() {
            return this.logs
                .filter(log => this.logType == '' || log['levelType'] == this.logType)
                .filter(log => log['message'].includes(this.searchText) ||
                    log['stacktrace'].includes(this.searchText) ||
                    log['levelType'].includes(this.searchText) || this.searchText.length == 0
                );
        }
    }" x-init="await loadLogs()">
        <div class="row">
            <div class="col-12 col-md-3" wire:key='filePath'>
                <h3>File Logs(<span x-text="$wire.files.length">0</span>): </h3>
                <div class="overflow-auto pb-3" style="max-height: 500px;">
                    <template x-for="file in $wire.files">
                        <div class="border p-2 mb-2 bg-light item-hover"
                            :class="$wire.filePath == file['path'] ? 'item-active' : ''"
                            @click="setFilePath(file['path'])">
                            <span x-text="file['name']"></span>
                        </div>
                    </template>
                </div>
            </div>
            <div class="col-12 col-md-9">
                <h3>Message Logs(<span x-text="logs.length">0</span>)
                    <span class="text-muted" wire:loading>Loading ...</span>
                </h3>
                <div class="row">
                    <div class="col-auto" x-show="logType" style="display: none">
                        <button class="btn btn-primary p-2" @click="logType=''">Clear</button>
                    </div>
                    <div class="col">
                        <div class="d-flex">
                            <template x-for="n,k in $wire.colorTypes">
                                <div @click="logType = k"
                                    class="border p-2 mb-2 bg-light rounded-1 item-hover me-3  cursor-pointer"
                                    :class="n + (k == logType ? ' item-active' : '')">
                                    <span x-text="k"></span>
                                    [<span x-text="countByType(k)"></span>]
                                </div>
                            </template>
                        </div>
                    </div>
                    <div class="col-auto">
                        <button class="btn btn-primary p-2" @click="loadLogs()">Refresh</button>
                    </div>
                    <div class="col-auto">
                        <input class="form-control" type="text" placeholder="Search…" x-model="searchText">
                    </div>
                </div>

                <div class="overflow-auto pb-3 mb-2" style="max-height: 500px;">
                    <template x-for="log in getLogs()">
                        <div class="border p-2 mb-2 bg-light item-hover rounded-2"
                            :class="$wire.colorTypes[log['levelType']] ?? 'bg-light'" x-data="{
                                showStack: false,
                                hasStack: log['stacktrace'].length > 0,
                                toggleStack() {
                                    this.showStack = !this.showStack;
                                },
                                copyStack() {
                                    navigator.clipboard.writeText(log['stacktrace']);
                                }
                            }">
                            <span x-text="log['dateText']" class=" me-1"></span>[<span
                                x-text="log['levelType']"></span>]:
                            <span x-text="log['message']"></span>
                            <span @click="toggleStack()" class="cursor-pointer bg-blue p-1 ms-1"
                                x-show="hasStack">[...]</span>
                            <div x-show="showStack" class="mt-2 ">
                                <button class="btn btn-sm btn-primary my-2" @click="copyStack()">Copy Stack</button>
                                <pre x-text="log['stacktrace']" class="overflow-auto" style="max-height: 300px;"></pre>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </div>
</div>
