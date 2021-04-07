<template>
  <div>
    <div class="seller-content">
      <label class="fontBold">Top</label>
      <select
        id="user-type"
        class=""
        name="userType"
        v-model="userType"
        @change="fetchFilteredUser()"
      >
        <option class="select-items" value="sell">Sellers</option>
        <option class="select-items" value="buy">Buyers</option>
      </select>
      <strong>in</strong>
      <select
        id="filter-time"
        class=""
        name="filterTime"
        @change="filterUser()"
        v-model="filterTime"
      >
        <option class="select-items" value="all">All time</option>
        <option class="select-items" value="1">1 day</option>
        <option class="select-items" value="7">7 days</option>
        <option class="select-items" value="30">30 days</option>
      </select>
    </div>
    <div class="filterList d-none d-md-block">
      <div id="preloader-top-user" class="">
        <div v-for="item in 8" :key="item" class="row placeholder-div">
          <div class="col-3 col-sm-3 col-md-3 col-lg-3">
            <div class="userCircle"></div>
          </div>
          <div class="col-9 col-sm-9 col-md-9 col-lg-9">
            <div class="userName"></div>
            <div class="userPrice"></div>
          </div>
        </div>
      </div>

      <div id="actual-top-user">
        <div v-for="(item, index) in userList" :key="index" class="topUserList">
          <label class="numbering">{{ index + 1 }}</label>
          <a class="black-link" :href="item.profile_url">
            <img class="filterImg" :src="asset_url + item.asset_url" />
          </a>
          <div class="user-info">
            <h6>
              <a class="black-link" :href="item.profile_url">{{
                item.display_name
              }}</a>
            </h6>
            <label>{{ item.totalPrice }} BHC</label>
          </div>
        </div>
      </div>
    </div>

    <div class="filterListMobile d-sm-block d-md-none">
      <div
        v-for="(item, index) in userList"
        :key="index"
        class="filterItemMobile"
      >
        <img class="filterImg" :src="asset_url + item.asset_url" alt="" />
        <label class="profName">
          {{ item.display_name }}
        </label>
        <label class="prof"> {{ item.totalPrice }} BHC </label>
      </div>
    </div>
  </div>
</template>


<script>
import { getMaxSellers, getMaxBuyers } from "../data";

export default {
  props: ["asset_url"],
  data() {
    return {
      userList: [],
      userType: "sell",
      filterTime: "all",
    };
  },
  methods: {
    async filterUser() {
      this.fetchFilteredUser();
    },
    async fetchFilteredUser() {
      if (this.userType == "sell") {
        this.userList = await getMaxSellers(this.filterTime);
      } else {
        this.userList = await getMaxBuyers(this.filterTime);
      }
    },
  },
  mounted() {
    this.fetchFilteredUser();
  },
};
</script>