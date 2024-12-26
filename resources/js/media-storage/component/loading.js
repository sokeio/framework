export default {
  state: {
    isShow: false,
  },
  boot() {
    this.$parent.$loading = this;
  },
  showLoading() {
    this.isShow = true;
    this.refresh();
  },
  hideLoading() {
    this.isShow = false;
    this.refresh();
  },
  render() {
    if (!this.isShow) return "<div style='display:none'></div>";
    return `
                  <div class="so-media-storage-loading">
                         <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 200"><circle fill="#253447" stroke="#253447" stroke-width="15" r="15" cx="40" cy="65"><animate attributeName="cy" calcMode="spline" dur="2" values="65;135;65;" keySplines=".5 0 .5 1;.5 0 .5 1" repeatCount="indefinite" begin="-.4"></animate></circle><circle fill="#253447" stroke="#253447" stroke-width="15" r="15" cx="100" cy="65"><animate attributeName="cy" calcMode="spline" dur="2" values="65;135;65;" keySplines=".5 0 .5 1;.5 0 .5 1" repeatCount="indefinite" begin="-.2"></animate></circle><circle fill="#253447" stroke="#253447" stroke-width="15" r="15" cx="160" cy="65"><animate attributeName="cy" calcMode="spline" dur="2" values="65;135;65;" keySplines=".5 0 .5 1;.5 0 .5 1" repeatCount="indefinite" begin="0"></animate></circle></svg>
                  </div>
          `;
  },
};
