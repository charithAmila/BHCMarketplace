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
import { checkConnection, getOwnersOf } from "./../../etherFunc";
export default {
  props: ["asset_url", "user_profile", "base_url", "contract", "owner", "id"],
  data() {
    return {
      is_liked: false,
      is_auth: false,
      onWishList: false,
      collectible: { creator: "dddd" },
      transactions: [],
      loaded: false,
      current_user: "",
      owners: [],
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
    getOwners: async function () {
      const _this = this;
      var _owners = await getOwnersOf(_this.contract, _this.id);
      _this.collectible.owners = _owners;
    },
    authCheck: function () {
      const _this = this;
      _this.current_user = checkConnection();
      _this.current_user == _this.owner ? (_this.is_auth = true) : null;
    },
  },
  async mounted() {
    this.collectible = await this.getCollectible();
    await this.getOwners();
    this.authCheck();
    this.loaded = true;
  },
};
</script>