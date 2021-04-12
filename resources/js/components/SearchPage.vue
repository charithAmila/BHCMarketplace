<template>
  <div class="search-page">
    <div class="hwNWpv">
      <h2>
        <span class="ElxpA">Search results for </span
        ><span class="izNbhn">{{ query }}</span>
      </h2>
    </div>

    <collectible-component
      :div_id="div_id"
      :collectible_asset="collectible_asset"
      :show_collectible="show_collectible"
      :current_user="current_user"
      :base_url="base_url"
      :asset_url="asset_url"
      :collectibles="collectibles_searched"
      :page="'search'"
      :isLoading="isLoading"
    ></collectible-component>
  </div>
</template>
<script>
import { getAllSalesSearch } from "../data";
import { checkConnection } from "../etherFunc";

export default {
  props: [
    "query",
    "collectible_asset",
    "show_collectible",
    "base_url",
    "asset_url",
  ],
  data() {
    return {
      collectibles_searched: [],
      filtered_collectibles: [],
      current_user: null,
      div_id: "searched_collectibles",
      isLoading: true,
    };
  },
  methods: {
    checkSearch() {
      var acc = checkConnection();
      this.current_user = acc;
      var q = this.query;
      var _this = this;
      getAllSalesSearch(acc, q).then((data) => {
        _this.collectibles_searched = data;

        _this.isLoading = false;
      });
    },
  },
  mounted() {
    this.checkSearch();

    this.filtered_collectibles = this.collectibles_searched.filter((post) => {
      return post.category.toLowerCase().includes(this.query.toLowerCase());
    });
  },
};
</script>
<style>
.ElxpA {
  text-decoration: none;
  color: rgba(4, 4, 5, 0.5);
  font-size: inherit;
  line-height: inherit;
  font-family: inherit;
  font-weight: 900;
}

.search-page {
  margin-top: 50px;
}

.izNbhn {
  text-decoration: none;
  font-size: inherit;
  line-height: inherit;
  font-family: inherit;
  font-weight: 900;
  color: rgb(4, 4, 5);
}
.hwNWpv {
  margin: 0px;
  padding: 0px 16px;
  -webkit-box-align: stretch;
  align-items: stretch;
  border-width: 0px;
  border-style: solid;
  border-color: rgb(4, 4, 5);
  display: flex;
  flex: 1 0 auto;
  flex-direction: column;
  min-height: 0px;
  min-width: 0px;
  max-width: 100%;
  z-index: 0;
  -webkit-box-flex: 1;
}
</style>