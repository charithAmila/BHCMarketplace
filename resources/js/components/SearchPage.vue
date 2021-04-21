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
            :collectibles="
                isFiltered ? filtered_collectibles : collectibles_searched
            "
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
        "asset_url"
    ],
    data() {
        return {
            collectibles_searched: [],
            filtered_collectibles: [],
            current_user: null,
            div_id: "searched_collectibles",
            isLoading: true,
            isFiltered: false
        };
    },
    methods: {
        async checkSearch() {
            var acc = await checkConnection();
            this.current_user = acc;
            var q = this.query;
            var _this = this;
            getAllSalesSearch(acc, q).then(data => {
                var collectibles_searched = data;
                _this.collectibles_searched = collectibles_searched;
                _this.isLoading = false;
            });
            var interval = setInterval(function() {
                if (window.searches.length > 0) {
                    _this.isLoading = false;
                    _this.collectibles_searched = window.searches;
                    clearInterval(interval);
                }
            }, 10);
        }
    },
    async mounted() {
        await this.checkSearch();
    }
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
