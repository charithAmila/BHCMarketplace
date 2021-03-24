<template>
  <div>
    <div class="nft-fullscreen d-none">
      <img
        v-if="collectible.type == 'image'"
        class="nft-item"
        :src="asset_url + 'storage/collectibles/' + collectible.nft"
      />
      <video
        v-if="collectible.type == 'video'"
        class="nft-item"
        autoplay
        loop
        controls
        muted
      >
        <source
          :src="asset_url + 'storage/collectibles/' + collectible.nft"
          type="video/mp4"
        />
      </video>

      <div class="nft-shrink">
        <i class="fa fa-compress" aria-hidden="true"></i>
      </div>

      <div class="nft-details">
        <h4>{{ collectible.name }}</h4>
        <p>
          by <span> {{ collectible.creator }} </span> on
          <span>{{ collectible.collection }}</span>
        </p>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: ["collectible", "asset_url"],
};
</script>