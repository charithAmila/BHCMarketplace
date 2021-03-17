<template>
  <div>
    <div>
      <profile-info-component
        :user_id="user_id"
        :asset_url="asset_url"
        :csrf_token="csrf_token"
        :user="user"
        :following="following"
      ></profile-info-component>
    </div>

    <profile-component
      :collectible_asset="collectible_asset"
      :show_collectible="show_collectible"
      :base_url="base_url"
      :asset_url="asset_url"
      :marketplace_url="marketplace_url"
    ></profile-component>

    <update-cover-modal-component
      :csrf_token="csrf_token"
    ></update-cover-modal-component>

    <footer-component :base_url="csrf_token"></footer-component>
  </div>
</template>

<script>
import { getUserDetails, checkFollowing, tempUserData } from "./../../data";
export default {
  props: [
    "asset_url",
    "base_url",
    "csrf_token",
    "collectible_asset",
    "show_collectible",
    "base_url",
    "asset_url",
    "marketplace_url",
    "user_id",
  ],
  data() {
    return {
      user: tempUserData(this.user_id),
      auth_id: "",
      following: true,
    };
  },
  methods: {
    checkConnection: function () {
      const _this = this;
      var connectionInterval = setInterval(function () {
        var acc = window.ethereum.selectedAddress;
        if (acc) {
          _this.auth_id = ethers.utils.getAddress(acc);
          clearInterval(connectionInterval);
        }
      }, 300);
    },
  },
  async mounted() {
    this.checkConnection();
    this.user = await getUserDetails(this.user_id);
    this.following = await checkFollowing(this.auth_id, this.user_id);
  },
};
</script>