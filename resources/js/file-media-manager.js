document.addEventListener("alpine:init", () => {
  Alpine.data("fileMediaManager", () => ({
    folders: [],
    files: [],
    selectFiles: [],
    selectFolders: [],
    async init() {
      await this.loadAll();
    },
    async loadAll() {
      let rs = await this.$wire.getDiskAll();
      this.setDataMedia(rs);
    },
    async backFolder(){
        this.setDataMedia( await this.$wire.getBackFolder());
    },

    chooseFile($file) {
      this.selectFiles = [];
      this.selectFolders = [];

      if (this.selectFiles.includes($file)) {
        this.selectFiles = this.selectFiles.filter((item) => item !== $file);
      } else {
        this.selectFiles.push($file);
      }
    },
    chooseFolder($folder) {
      this.selectFiles = [];
      this.selectFolders = [];
      if (this.selectFolders.includes($folder)) {
        this.selectFolders = this.selectFolders.filter(
          (item) => item !== $folder
        );
      } else {
        this.selectFolders.push($folder);
      }
    },
    checkFile($file) {
      return this.selectFiles.includes($file);
    },
    checkFolder($folder) {
      return this.selectFolders.includes($folder);
    },

    async selectFolder($name) {
      let rs = await this.$wire.selectFolder($name);
      this.setDataMedia(rs);
    },

    setDataMedia(rs) {
      this.folders = rs.folders ?? [];
      this.files = rs.files ?? [];
    },
  }));
});
