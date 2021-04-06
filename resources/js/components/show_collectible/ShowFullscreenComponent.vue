<template>
  <div>
    <div class="nft-fullscreen d-none">
      <img
        v-if="collectible.fileType == 'image'"
        class="nft-item"
        :src="collectible.file"
      />
      <video
        v-if="
          collectible.fileType == 'video' || collectible.fileType == 'audio'
        "
        class="nft-item"
        autoplay
        loop
        controls
        muted
      >
        <source :src="collectible.file" type="video/mp4" />
      </video>

      <div class="nft-shrink">
        <i class="fa fa-compress" aria-hidden="true"></i>
      </div>

      <div class="nft-details">
        <h4>{{ collectible.name }}</h4>
        <p>
          by <span> {{ collectible.creator.name }} </span> on
          <span>{{ collectible.collection.name }}</span>
        </p>
      </div>
    </div>
  </div>
</template>

<script>
import CollectibleDetailsComponent from "./CollectibleDetailsComponent.vue";
export default {
  components: { CollectibleDetailsComponent },
  props: ["collectible", "asset_url"],
};
</script>