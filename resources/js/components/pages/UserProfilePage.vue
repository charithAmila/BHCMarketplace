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
      :user_id="user_id"
      :current_user="auth_id"
    ></profile-component>

    <update-cover-modal-component
      :csrf_token="csrf_token"
    ></update-cover-modal-component>

    <footer-component :base_url="base_url"></footer-component>
  </div>
</template>

<script>
import { getUserDetails, checkFollowing, tempUserData } from "./../../data";
import { toAddress, checkConnection } from "./../../etherFunc";
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
      user: tempUserData(toAddress(this.user_id)),
      auth_id: "",
      following: true,
    };
  },
  methods: {
    checkConnection: function () {
      const _this = this;
      var connectionInterval = setInterval(function () {
        var acc = checkConnection();
        if (acc) {
          _this.auth_id = toAddress(acc);
          clearInterval(connectionInterval);
        } else {
          _this.auth_id = toAddress("");
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