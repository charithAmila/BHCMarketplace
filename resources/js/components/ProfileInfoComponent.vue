<template>
  <div>
    <div class="profile-cover">
      <div>
        <img id="current-cover-photo" :src="user.cover_photo" />
        <a
          v-if="auth_id == user_id"
          class="edit-cover d-none d-md-block"
          @click="getCover()"
          >Edit Cover</a
        >
        <a
          v-if="auth_id == user_id"
          class="edit-cover-mobile d-xs-block d-md-none"
          @click="getCover()"
        >
          <i class="fa fa-cog"></i>
        </a>
      </div>
    </div>
    <div class="profile-section">
      <div class="profile-info">
        <div class="actual-img-container">
          <img
            class="profile-photo actual-photo"
            id="current-display-photo"
            :src="user.display_photo"
          />
          <div
            v-if="auth_id == user_id"
            id="dp-option"
            class="custom"
            @click="getUser()"
          >
            <i class="fa fa-cog"></i>
          </div>
        </div>
        <div class="details-container">
          <label id="profile-name">{{ user.name }}</label>
          <label id="profile-bio">{{ user.bio }}</label>
          <label id="profile-wallet">{{ user.wallet }}</label>
          <div class="profile-button">
            <a
              v-if="auth_id != user_id && auth_id != 0"
              id="btn-follow"
              class="btn btn-follow"
              :class="following == false ? '' : 'd-none'"
              :data-user-url="user.short_url"
              href="javascript:void(0)"
              >Follow</a
            >

            <a
              v-if="auth_id != user_id && auth_id != 0"
              id="btn-following"
              class="btn btn-follow"
              :class="following == true ? '' : 'd-none'"
              :data-user-url="user.short_url"
              href="javascript:void(0)"
              >Following</a
            >

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
                    :data-title="user.name + '\'s profile'"
                    :data-url="asset_url + 'profile/' + user.short_url"
                  >
                    <i class="fa fa-twitter s-btn"></i>
                    <label>Twitter</label>
                  </a>
                </div>
                <div class="col-4 col-md-4">
                  <a
                    href="javascript:void(0)"
                    data-sharer="facebook"
                    :data-title="user.name + '\'s profile'"
                    :data-url="asset_url + 'profile/' + user.short_url"
                  >
                    <i class="fa fa-facebook s-btn"></i>
                    <label class="fb-label">Facebook</label>
                  </a>
                </div>
                <div class="col-4 col-md-4">
                  <a
                    href="javascript:void(0)"
                    data-sharer="telegram"
                    :data-title="user.name + '\'s profile'"
                    :data-url="asset_url + 'profile/' + user.short_url"
                  >
                    <i class="fab fa-telegram-plane s-btn"></i>
                    <label>Telegram</label>
                  </a>
                </div>
                <div class="col-4 col-md-4">
                  <a
                    href="javascript:void(0)"
                    data-sharer="email"
                    :data-title="user.name + '\'s profile'"
                    :data-url="asset_url + 'profile/' + user.short_url"
                    data-subject="Hey! Check out that URL"
                  >
                    <i class="fa fa-envelope s-btn"></i>
                    <label>Email</label>
                  </a>
                </div>
                <div class="col-4 col-md-4">
                  <input
                    class="linkToCopy"
                    :value="asset_url + 'profile/' + user.short_url"
                  />
                  <a href="javascript:void(0)" @click="copyUrl">
                    <i class="fa fa-copy s-btn"></i>
                    <label class="c-link">Copy link</label>
                  </a>
                </div>
              </div>
            </div>

            <div class="options d-none">
              <a class="report-page" href="javascript:void(0)">Report page</a>
              <a
                v-if="auth_id == user_id"
                class="profile-preferences"
                @click="getUser()"
                >Edit preferences</a
              >
            </div>
          </div>
        </div>
      </div>
    </div>

    <preferences-modal-component
      :asset_url="asset_url"
      :csrf_token="csrf_token"
      :user_data="user"
      :userPhoto="userPhoto"
      :page="'profile'"
      :user_id="user_id"
    ></preferences-modal-component>

    <update-cover-modal-component
      :asset_url="asset_url"
      :csrf_token="csrf_token"
      :user_data="user"
      :userPhoto="userPhoto"
      :page="'profile'"
      :user_id="user_id"
    ></update-cover-modal-component>
  </div>
</template>

<script>
import { ethers } from "ethers";
import { toAddress, checkConnection } from "./../etherFunc";
export default {
  props: [
    //'auth_id',
    "user_id",
    "following",
    "asset_url",
    "csrf_token",
    "user",
  ],

  data() {
    return {
      auth_id: "",

      userData: {},
      userPhoto: "",
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
        }
      }, 300);
    },

    getUser() {
      $("#preferencesModal").addClass("d-block");
      $(".preferences-content")
        .removeClass("fade-out-bottom")
        .addClass("fade-in-bottom");
    },
    getCover() {
      $("#update-cover").addClass("d-block");
      $(".cover-content")
        .removeClass("fade-out-bottom")
        .addClass("fade-in-bottom");
    },
    customFunc() {
      //alert("customFunc")
      this.getUserData();
    },
    getUserPhoto() {
      var current = this.userData.display_photo;
      if (current == "default.png") {
        this.userPhoto = this.asset_url + "user/photo/" + current;
      } else {
        this.userPhoto = this.asset_url + "storage/user/photo/" + current;
      }
    },
    copyUrl() {
      $("input.linkToCopy").select();
      document.execCommand("copy");
    },
    getUserData() {
      const _this = this;
      _this.user.wallet = _this.user_id;
      axios
        .get("/api/profile/" + _this.user.wallet)
        .then(function (response) {
          axios
            .get("https://ipfs.io/ipfs/" + response.data)
            .then(function (response) {
              _this.user.name = response.data.name;
              _this.user.bio = response.data.description;
              _this.user.display_photo =
                response.data.dp ||
                "https://st3.depositphotos.com/15648834/17930/v/600/depositphotos_179308454-stock-illustration-unknown-person-silhouette-glasses-profile.jpg";
              _this.user.cover_photo =
                response.data.cover ||
                "https://www.shutterstock.com/blog/wp-content/uploads/sites/5/2017/08/nature-design.jpg";
              _this.userPhoto = response.data.dp;
              _this.user.short_url = response.data.short_url;
            })
            .then(function (error) {});
        })
        .then(function (error) {});
    },
    follow() {},
    sign() {},
  },
  mounted() {
    this.checkConnection();
    this.getUserData();
  },
};
</script>