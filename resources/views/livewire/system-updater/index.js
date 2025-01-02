export default {
  components: {},
  state: {
    isRunning: false,
    productName: "",
    productVersion: "",
    framework: "",
    modules: [],
    themes: [],
    isUpdated: false,
  },
  register() {
    this.$wire.getProductInfo().then(function (res) {
      this.productName = res.name;
      this.productVersion = res.version;
      this.framework = res.framework;
      this.modules = res.modules;
      this.themes = res.themes;
      console.log({ res });
    });
  },
  boot() {},
  runningRender() {
    if (!this.isRunning) {
      return "";
    }
    return `
      <div class="p-3">
          <h3 class="fw-bold">System Updater</h3>
          <div class="alert alert-danger" role="alert">
              <div>
                <h4 class="alert-title">Don't Close This Page</h4>
                <div class="alert-message">System Update is running</div>
              </div>
            </div>
      </div>
      `;
  },
  render() {
    return `
      <div class="p-3">
          <h3 class="fw-bold">System Updater</h3>
         ${this.runningRender()}
         <div class="card">
                      <div class="card-header">
                        <h3 class="card-title">Last commits</h3>
                      </div>
                      <div class="list-group list-group-flush list-group-hoverable">
                        <div class="list-group-item">
                          <div class="row align-items-center">
                            <div class="col-auto"><span class="badge bg-red"></span></div>
                            <div class="col-auto">
                              <a href="#">
                                <span class="avatar" style="background-image: url(./static/avatars/000m.jpg)"></span>
                              </a>
                            </div>
                            <div class="col text-truncate">
                              <a href="#" class="text-reset d-block">Paweł Kuna</a>
                              <div class="d-block text-secondary text-truncate mt-n1">Change deprecated html tags to text decoration classes (#29604)</div>
                            </div>
                            <div class="col-auto">
                              <a href="#" class="list-group-item-actions"><!-- Download SVG icon from http://tabler-icons.io/i/star -->
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon text-secondary"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M12 17.75l-6.172 3.245l1.179 -6.873l-5 -4.867l6.9 -1l3.086 -6.253l3.086 6.253l6.9 1l-5 4.867l1.179 6.873z"></path></svg>
                              </a>
                            </div>
                          </div>
                        </div>
                        <div class="list-group-item">
                          <div class="row align-items-center">
                            <div class="col-auto"><span class="badge"></span></div>
                            <div class="col-auto">
                              <a href="#">
                                <span class="avatar">JL</span>
                              </a>
                            </div>
                            <div class="col text-truncate">
                              <a href="#" class="text-reset d-block">Jeffie Lewzey</a>
                              <div class="d-block text-secondary text-truncate mt-n1">justify-content:between ⇒ justify-content:space-between (#29734)</div>
                            </div>
                            <div class="col-auto">
                              <a href="#" class="list-group-item-actions"><!-- Download SVG icon from http://tabler-icons.io/i/star -->
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon text-secondary"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M12 17.75l-6.172 3.245l1.179 -6.873l-5 -4.867l6.9 -1l3.086 -6.253l3.086 6.253l6.9 1l-5 4.867l1.179 6.873z"></path></svg>
                              </a>
                            </div>
                          </div>
                        </div>
                        <div class="list-group-item">
                          <div class="row align-items-center">
                            <div class="col-auto"><span class="badge bg-"></span></div>
                            <div class="col-auto">
                              <a href="#">
                                <span class="avatar" style="background-image: url(./static/avatars/002m.jpg)"></span>
                              </a>
                            </div>
                            <div class="col text-truncate">
                              <a href="#" class="text-reset d-block">Mallory Hulme</a>
                              <div class="d-block text-secondary text-truncate mt-n1">Update change-version.js (#29736)</div>
                            </div>
                            <div class="col-auto">
                              <a href="#" class="list-group-item-actions"><!-- Download SVG icon from http://tabler-icons.io/i/star -->
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon text-secondary"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M12 17.75l-6.172 3.245l1.179 -6.873l-5 -4.867l6.9 -1l3.086 -6.253l3.086 6.253l6.9 1l-5 4.867l1.179 6.873z"></path></svg>
                              </a>
                            </div>
                          </div>
                        </div>
                        <div class="list-group-item">
                          <div class="row align-items-center">
                            <div class="col-auto"><span class="badge bg-green"></span></div>
                            <div class="col-auto">
                              <a href="#">
                                <span class="avatar" style="background-image: url(./static/avatars/003m.jpg)"></span>
                              </a>
                            </div>
                            <div class="col text-truncate">
                              <a href="#" class="text-reset d-block">Dunn Slane</a>
                              <div class="d-block text-secondary text-truncate mt-n1">Regenerate package-lock.json (#29730)</div>
                            </div>
                            <div class="col-auto">
                              <a href="#" class="list-group-item-actions"><!-- Download SVG icon from http://tabler-icons.io/i/star -->
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon text-secondary"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M12 17.75l-6.172 3.245l1.179 -6.873l-5 -4.867l6.9 -1l3.086 -6.253l3.086 6.253l6.9 1l-5 4.867l1.179 6.873z"></path></svg>
                              </a>
                            </div>
                          </div>
                        </div>
                        <div class="list-group-item">
                          <div class="row align-items-center">
                            <div class="col-auto"><span class="badge bg-red"></span></div>
                            <div class="col-auto">
                              <a href="#">
                                <span class="avatar" style="background-image: url(./static/avatars/000f.jpg)"></span>
                              </a>
                            </div>
                            <div class="col text-truncate">
                              <a href="#" class="text-reset d-block">Emmy Levet</a>
                              <div class="d-block text-secondary text-truncate mt-n1">Some minor text tweaks</div>
                            </div>
                            <div class="col-auto">
                              <a href="#" class="list-group-item-actions"><!-- Download SVG icon from http://tabler-icons.io/i/star -->
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon text-secondary"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M12 17.75l-6.172 3.245l1.179 -6.873l-5 -4.867l6.9 -1l3.086 -6.253l3.086 6.253l6.9 1l-5 4.867l1.179 6.873z"></path></svg>
                              </a>
                            </div>
                          </div>
                        </div>
                        <div class="list-group-item">
                          <div class="row align-items-center">
                            <div class="col-auto"><span class="badge bg-yellow"></span></div>
                            <div class="col-auto">
                              <a href="#">
                                <span class="avatar" style="background-image: url(./static/avatars/001f.jpg)"></span>
                              </a>
                            </div>
                            <div class="col text-truncate">
                              <a href="#" class="text-reset d-block">Maryjo Lebarree</a>
                              <div class="d-block text-secondary text-truncate mt-n1">Link to versioned docs</div>
                            </div>
                            <div class="col-auto">
                              <a href="#" class="list-group-item-actions"><!-- Download SVG icon from http://tabler-icons.io/i/star -->
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon text-secondary"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M12 17.75l-6.172 3.245l1.179 -6.873l-5 -4.867l6.9 -1l3.086 -6.253l3.086 6.253l6.9 1l-5 4.867l1.179 6.873z"></path></svg>
                              </a>
                            </div>
                          </div>
                        </div>
                        <div class="list-group-item">
                          <div class="row align-items-center">
                            <div class="col-auto"><span class="badge"></span></div>
                            <div class="col-auto">
                              <a href="#">
                                <span class="avatar">EP</span>
                              </a>
                            </div>
                            <div class="col text-truncate">
                              <a href="#" class="text-reset d-block">Egan Poetz</a>
                              <div class="d-block text-secondary text-truncate mt-n1">Copywriting edits</div>
                            </div>
                            <div class="col-auto">
                              <a href="#" class="list-group-item-actions"><!-- Download SVG icon from http://tabler-icons.io/i/star -->
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon text-secondary"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M12 17.75l-6.172 3.245l1.179 -6.873l-5 -4.867l6.9 -1l3.086 -6.253l3.086 6.253l6.9 1l-5 4.867l1.179 6.873z"></path></svg>
                              </a>
                            </div>
                          </div>
                        </div>
                        <div class="list-group-item">
                          <div class="row align-items-center">
                            <div class="col-auto"><span class="badge bg-yellow"></span></div>
                            <div class="col-auto">
                              <a href="#">
                                <span class="avatar" style="background-image: url(./static/avatars/002f.jpg)"></span>
                              </a>
                            </div>
                            <div class="col text-truncate">
                              <a href="#" class="text-reset d-block">Kellie Skingley</a>
                              <div class="d-block text-secondary text-truncate mt-n1">Enable RFS for font resizing</div>
                            </div>
                            <div class="col-auto">
                              <a href="#" class="list-group-item-actions"><!-- Download SVG icon from http://tabler-icons.io/i/star -->
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon text-secondary"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M12 17.75l-6.172 3.245l1.179 -6.873l-5 -4.867l6.9 -1l3.086 -6.253l3.086 6.253l6.9 1l-5 4.867l1.179 6.873z"></path></svg>
                              </a>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
      </div>
      `;
  },
};
