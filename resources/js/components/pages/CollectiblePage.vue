<template>
  <div>
    <div v-if="loaded">
      <show-collectible-component
        :collectible="collectible"
        :onWishList="onWishList"
        :transactions="transactions"
        :is_liked="is_liked"
        :asset_url="asset_url"
        :auth_check="is_auth"
        :user_profile="user_profile"
        :current_user="current_user"
        :base_url="base_url"
      ></show-collectible-component>

      <show-fullscreen-component
        :collectible="collectible"
        :asset_url="asset_url"
      ></show-fullscreen-component>
    </div>
  </div>
</template>
<script>
import { getTokenData } from "./../../data";
export default {
  props: [
    "asset_url",
    "user_profile",
    "current_user",
    "base_url",
    "contract",
    "owner",
    "id",
  ],
  data() {
    return {
      is_liked: false,
      is_auth: false,
      onWishList: false,
      collectible: { creator: "dddd" },
      transactions: [],
      loaded: false,
    };
  },
  methods: {
    getCollectible: async function () {
      const _this = this;
      var data = await getTokenData(
        _this.contract,
        _this.owner,
        Number(_this.id)
      );
      return data;
    },
  },
  async mounted() {
    this.collectible = await this.getCollectible();
    this.loaded = true;
  },
};
</script>