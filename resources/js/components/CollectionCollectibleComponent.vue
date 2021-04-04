<template>
  <div>
    <div class="filter-collection">
      <a
        id="on-sale"
        class="filter active"
        href="javascript:void(0)"
        @click="filterCollection('on-sale')"
        >On sale</a
      >
      <a
        id="collectibles"
        class="filter"
        href="javascript:void(0)"
        @click="filterCollection('collectibles')"
        >Collectibles</a
      >
    </div>

    <collectible-component
      v-if="loaded"
      :div_id="div_id"
      :collectible_asset="collectible_asset"
      :show_collectible="show_collectible"
      :current_user="current_user"
      :base_url="base_url"
      :collectibles="collectionNft"
      :page="page"
      :filter="filter"
    ></collectible-component>
  </div>
</template>

<script>
export default {
  props: [
    "div_id",
    "collectible_asset",
    "show_collectible",
    "current_user",
    "base_url",
    "collectibles",
    "page",
    "filter",
    "collection",
  ],
  data() {
    return {
      collectionNft: [],
      loaded: false,
    };
  },
  watch: {
    current_user() {},
    collectibles() {
      this.filterCollection("on-sale");
    },
  },
  methods: {
    filterCollection(filter) {
      var filtered;
      if (filter == "on-sale") {
        filtered = this.collectibles.filter(function (elem) {
          if (elem.is_selling) return true;
        });
      } else {
        filtered = this.collectibles;
      }
      this.collectionNft = filtered;
    },
  },
  mounted() {
    this.filterCollection("on-sale");
    this.loaded = true;
  },
};
</script>