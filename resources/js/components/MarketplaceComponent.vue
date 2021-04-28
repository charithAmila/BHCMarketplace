<template>
    <div>
        <div class="item-filter">
            <div>
                <button
                    data-filter-id="all"
                    class="filter-btn active"
                    @click="filterNftAll()"
                >
                    All
                </button>
                <button
                    data-filter-id="1"
                    class="filter-btn"
                    @click="getFilterdArts()"
                >
                    Arts
                </button>
                <button
                    data-filter-id="2"
                    class="filter-btn"
                    @click="getFilterdMemes()"
                >
                    Memes
                </button>
            </div>
            <div class="sortDiv">
                <a class="sorter sortDesk" href="javascript:void(0)">
                    <img
                        class="imgFilter"
                        :src="asset_url + 'images/filter.png'"
                        width="25"
                        alt=""
                    />
                    Sort & Filter
                </a>

                <a
                    class="sorter d-sm-block d-md-none"
                    href="javascript:void(0)"
                >
                    <img
                        class="imgFilter"
                        :src="asset_url + '/images/filter.png'"
                        width="25"
                        alt=""
                    />
                </a>

                <div class="sortfilter d-none">
                    <label class="sortLabel">Sort by</label>
                    <ul>
                        <li>
                            <a
                                href="javascript:void(0)"
                                class="sortItem"
                                data-sort-by="updated_at"
                                data-order="desc"
                                @click="sortNft('updated_at', 'desc')"
                            >
                                <div class="row">
                                    <div
                                        class="col-md-9"
                                        @click="sortedItems('recent')"
                                    >
                                        Recently added
                                    </div>
                                    <div class="col-md-3 checkLabel">
                                        <i class="fa fa-check"></i>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a
                                href="javascript:void(0)"
                                class="sortItem"
                                data-sort-by="price"
                                data-order="asc"
                                @click="sortNft('price', 'asc')"
                            >
                                <div class="row">
                                    <div
                                        class="col-md-9"
                                        @click="sortedItems('cheap')"
                                    >
                                        Cheapest
                                    </div>
                                    <div class="col-md-3 checkLabel">
                                        <i class="fa fa-check"></i>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a
                                href="javascript:void(0)"
                                class="sortItem"
                                data-sort-by="price"
                                data-order="desc"
                                @click="sortNft('price', 'desc')"
                            >
                                <div class="row">
                                    <div
                                        class="col-md-9"
                                        @click="sortedItems('hprice')"
                                    >
                                        Highest price
                                    </div>
                                    <div class="col-md-3 checkLabel">
                                        <i class="fa fa-check"></i>
                                    </div>
                                </div>
                            </a>
                        </li>
                    </ul>
                    <!--label class="sortLabel">Options</label-->
                    <!--ul>
                        <li>
                            <a href="javascript:void(0)">
                                <div class="row">
                                    <div class="col-md-9">Verified only</div>
                                    <div class="col-md-3">
                                        <div
                                            class="custom-control custom-switch"
                                        >
                                            <input
                                                type="checkbox"
                                                class="custom-control-input"
                                                id="customSwitch1"
                                                checked
                                            />
                                            <label
                                                class="custom-control-label"
                                                for="customSwitch1"
                                            ></label>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </li>
                    </ul-->
                </div>
            </div>
        </div>
        <div class="items">
            <collectible-component
                :div_id="div_id"
                :collectible_asset="collectible_asset"
                :show_collectible="show_collectible"
                :current_user="current_user"
                :base_url="base_url"
                :collectibles="
                    isSortedRecent
                        ? new_array_collectibles
                        : isAll
                        ? collectibles
                        : collectible
                "
                :page="'marketplace'"
                :isLoading="loading_collectibles"
            ></collectible-component>
            <div id="preloader" class="row d-none">
                <div
                    v-for="index in 24"
                    :key="index"
                    class="col-md-3 col-lg-3 custom-column-xl main-dashboard"
                >
                    <div class="outside-nft border-on-profile">
                        <div class="inside-nft">
                            <div class="inner-outside-nft">
                                <div class="inner-nft">
                                    <div class="item-main">
                                        <div class="item-head">
                                            <div class="preloader-img"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="item-img"></div>

                <div class="display-flex -mt-15">
                    <div class="preloader-content"></div>
                </div>
                <div class="text-center currency-label">
                    <div class="preloader-content"></div>
                </div>
                <div class="preloader-half">
                    <div class="preloader-content"></div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Collectible from "./CollectibleComponent.vue";
import { getAllSales } from "./../data";
import { checkConnection, toAddress } from "./../etherFunc";
export default {
    components: {
        Collectible
    },
    props: ["collectible_asset", "show_collectible", "base_url", "asset_url"],
    data() {
        return {
            collectibles: [],
            collectible: [],
            category: "all",
            sortBy: "updated_at",
            order: "desc",
            div_id: "nft-container",
            notifications: [],
            current_user: "",
            isAll: true,
            new_array_collectibles: [],
            isSortedRecent: false,
            loading_collectibles: true
        };
    },
    methods: {
        checkConnection: async function() {
            const _this = this;
            _this.current_user = await checkConnection();
        },
        getCollectible() {
            const _this = this;
            //this.loading_collectibles = true;
            var itemInterval = setInterval(function() {
                _this.collectibles = window.sales;
                if (!_this.loading_collectibles) {
                    clearInterval(itemInterval);
                }
                if (window.loaded["sales"]) {
                    _this.loading_collectibles = false;
                }
            }, 1);
            getAllSales(_this.current_user)
                .then(data => {
                    //_this.collectibles = data;
                    //this.loading_collectibles = false;
                })
                .catch(error => {
                    console.log(error);
                });
        },
        getFilterdArts() {
            if (
                this.new_array_collectibles === undefined ||
                this.new_array_collectibles.length == 0
            ) {
                //
            } else {
                if (this.isSortedRecent) {
                    //
                } else {
                    this.collectibles = [...this.new_array_collectibles];
                }
            }
            this.isSortedRecent = false;
            this.collectible = this.collectibles.filter(function(hero) {
                return hero.category == "Arts";
            });
            this.isAll = false;
        },
        getFilterdMemes() {
            if (
                this.new_array_collectibles === undefined ||
                this.new_array_collectibles.length == 0
            ) {
                //
            } else {
                this.isSortedRecent = false;
                this.collectibles = [...this.new_array_collectibles];
            }
            this.isSortedRecent = false;
            this.collectible = this.collectibles.filter(function(hero) {
                return hero.category == "Memes";
            });
            this.isAll = false;
        },
        filterNftAll() {
            if (
                this.new_array_collectibles === undefined ||
                this.new_array_collectibles.length == 0
            ) {
                //
            } else {
                this.isSortedRecent = false;
                this.collectibles = [...this.new_array_collectibles];
            }
            this.isAll = true;
        },
        sortedItems: function(sort_status) {
            if (sort_status == "recent") {
                if (
                    this.new_array_collectibles === undefined ||
                    this.new_array_collectibles.length == 0
                ) {
                    //
                } else {
                    this.isSortedRecent = false;
                    this.collectibles = [...this.new_array_collectibles];
                }
                if (!this.isAll) {
                    var collectible_array = this.collectible;
                } else {
                    var collectible_array = this.collectibles;
                }
                this.isSortedRecent = true;
                this.new_array_collectibles = collectible_array.sort(function(
                    a,
                    b
                ) {
                    return new Date(b.created_at) - new Date(a.created_at);
                });
            } else if (sort_status == "cheap") {
                if (
                    this.new_array_collectibles === undefined ||
                    this.new_array_collectibles.length == 0
                ) {
                    //
                } else {
                    this.isSortedRecent = false;
                    this.collectibles = [...this.new_array_collectibles];
                }
                if (!this.isAll) {
                    var collectible_array = this.collectible;
                } else {
                    var collectible_array = this.collectibles;
                }
                this.isSortedRecent = true;
                this.new_array_collectibles = collectible_array.sort(function(
                    a,
                    b
                ) {
                    return new Date(a.price) - new Date(b.price);
                });
            } else if (sort_status == "hprice") {
                if (
                    this.new_array_collectibles === undefined ||
                    this.new_array_collectibles.length == 0
                ) {
                    //
                } else {
                    this.isSortedRecent = false;
                    this.collectibles = [...this.new_array_collectibles];
                }
                if (!this.isAll) {
                    var collectible_array = this.collectible;
                } else {
                    var collectible_array = this.collectibles;
                }
                this.isSortedRecent = true;
                this.new_array_collectibles = collectible_array.sort(function(
                    a,
                    b
                ) {
                    return new Date(b.price) - new Date(a.price);
                });
            }
        },
        filterNft(clickedCategory) {
            this.category = clickedCategory;
            // this.fetchFilterNft();
        },
        sortNft(column, columnOrder) {
            this.sortBy = column;
            this.order = columnOrder;
            this.fetchFilterNft();
        },
        fetchFilterNft() {
            axios
                .get(
                    "/nft/filter/" +
                        this.category +
                        "/" +
                        this.sortBy +
                        "/" +
                        this.order
                )
                .then(res => {
                    this.collectibles = res.data.collectibles;
                })
                .catch(error => {
                    console.log(error);
                });
        }
    },
    async mounted() {
        await this.checkConnection();
        this.getCollectible();
    }
};
</script>
