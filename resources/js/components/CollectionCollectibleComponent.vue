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
      :div_id="div_id"
      :collectible_asset="collectible_asset"
      :show_collectible="show_collectible"
      :current_user="current_user"
      :base_url="base_url"
      :collectibles="collectionNft || collectibles"
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
      collectionNft: null,
    };
  },
  methods: {
    filterCollection(filter) {
      /*axios
        .get("/collection/" + this.collection.short_url + "/" + filter)
        .then((res) => {
          console.log(res.data.collectibles);
          this.collectionNft = res.data.collectibles;
        })
        .catch((error) => {
          alert("error");
        });

		}*/
    },
  },
  mounted() {
    this.collectionNft = this.collectibles;
  },
};
</script>