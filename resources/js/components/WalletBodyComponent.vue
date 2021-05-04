<template>
    <div>
        <a class="d-none d-md-block" :href="marketplace_route">
            <img class="sidebarLogo" :src="asset_url + 'images/logo.png'" />
        </a>
        <div class="my-auto">
            <div class="imgSmall imgPos1">
                <img
                    class="sidebarImg"
                    :src="asset_url + 'images/1_icon1.png'"
                />
            </div>
            <div class="imgLarge imgPos2">
                <img
                    class="sidebarImg"
                    :src="asset_url + 'images/1_icon2.png'"
                />
            </div>
            <div class="imgNormal imgPos3">
                <img
                    class="sidebarImg"
                    :src="asset_url + 'images/1_icon3.png'"
                />
            </div>
            <div class="imgNormal imgPos4">
                <img
                    class="sidebarImg"
                    :src="asset_url + 'images/1_icon4.png'"
                />
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ["marketplace_route", "asset_url"]
};
</script>
