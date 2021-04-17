<template>
    <div>
        <collection-info-component
            v-if="loaded"
            :asset_url="asset_url"
            :collection="collectionData"
        ></collection-info-component>
        <fold v-else></fold>

        <div class="collection-collectibles">
            <collection-collectible-component
                :div_id="'collection-page'"
                :collectible_asset="collectible_asset"
                :show_collectible="show_collectible"
                :current_user="current_user"
                :base_url="base_url"
                :collectibles="collectibles"
                :page="'collection'"
                :filter="'on-sale'"
                :collection="collectionData"
            ></collection-collectible-component>
        </div>
    </div>
</template>

<script>
import { getCollection, checkConnection, toAddress } from "./../../etherFunc";
import { collectiblesOfCollection } from "./../../data";
export default {
    props: [
        "collectible_asset",
        "show_collectible",
        "base_url",
        "collection",
        "asset_url"
    ],
    data() {
        return {
            current_user: null,
            collectibles: [],
            collectionData: null,
            loaded: false
        };
    },
    methods: {
        checkConnection() {
            const _this = this;
            var interval = setInterval(function() {
                var acc = checkConnection();
                if (acc != toAddress("")) {
                    _this.current_user = acc;
                    clearInterval(interval);
                }
            }, 300);
        }
    },
    async mounted() {
        this.checkConnection();
        this.collectibles = await collectiblesOfCollection(this.collection);
        this.collectionData = this.collectibles[0].collection;
        this.loaded = true;
    }
};
</script>
