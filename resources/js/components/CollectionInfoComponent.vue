<template>
  <div class="collection-container">
    <div class="collection-info">
      <div class="collection-photo">
        <img :src="collectionPhoto" />
      </div>
      <h1 class="collection-name">{{ collection.name }}</h1>

      <!--   <div class="action-container">
        <a class="btn btn-social share-link" href="javascript:void(0)">
          <i class="fa fa-upload"></i>
        </a>
        <a class="btn btn-social options-link" href="javascript:void(0)">
          <i class="fas fa-ellipsis-h"></i>
        </a>

        <div class="share-drop d-none">
          <label class="share-title">Share link to this page</label>
          <div class="row">
            <div class="col-4 col-md-4">
              <a
                href="javascript:void(0)"
                data-sharer="twitter"
                :data-title="collection.name + ' collection'"
                :data-url="asset_url + 'collection/' + collection.short_url"
              >
                <i class="fa fa-twitter s-btn"></i>
                <label>Twitter</label>
              </a>
            </div>
            <div class="col-4 col-md-4">
              <a
                href="javascript:void(0)"
                data-sharer="facebook"
                :data-title="collection.name + ' collection'"
                :data-url="asset_url + 'collection/' + collection.short_url"
              >
                <i class="fa fa-facebook s-btn"></i>
                <label class="fb-label">Facebook</label>
              </a>
            </div>
            <div class="col-4 col-md-4">
              <a
                href="javascript:void(0)"
                data-sharer="telegram"
                :data-title="collection.name + ' collection'"
                :data-url="asset_url + 'collection/' + collection.short_url"
              >
                <i class="fab fa-telegram-plane s-btn"></i>
                <label>Telegram</label>
              </a>
            </div>
            <div class="col-4 col-md-4">
              <a
                href="javascript:void(0)"
                data-sharer="email"
                :data-title="collection.name + ' collection'"
                :data-url="asset_url + 'collection/' + collection.short_url"
                data-subject="Hey! Check out that URL"
              >
                <i class="fa fa-envelope s-btn"></i>
                <label>Email</label>
              </a>
            </div>
            <div class="col-4 col-md-4">
              <input
                class="linkToCopy"
                :value="asset_url + 'collection/' + collection.short_url"
              />
              <a href="javascript:void(0)" @click="copyUrl">
                <i class="fa fa-copy s-btn"></i>
                <label class="c-link">Copy link</label>
              </a>
            </div>
          </div>
        </div>

        <div class="options d-none">
          <a class="report-collection" href="javascript:void(0)">Report page</a>
        </div>
      </div>
      -->
    </div>

    <report-modal-component></report-modal-component>
  </div>
</template>

<script>
export default {
  props: ["asset_url", "collection"],
  data() {
    return {
      url: "",
    };
  },
  computed: {
    collectionPhoto: function () {
      return this.collection.icon;
    },
  },
  methods: {
    copyUrl() {
      $("input.linkToCopy").select();
      document.execCommand("copy");
    },
  },
  mounted() {
    this.url = window.location.href;
  },
};
</script>