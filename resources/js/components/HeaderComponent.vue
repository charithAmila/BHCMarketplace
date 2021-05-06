<template>
    <div>
        <nav class="navbar navbar-expand-lg navbar-light custom-navbar">
            <div class="left-nav default-nav onclick-menu">
                <a class="navbar-brand" :href="marketplace_route">
                    <img
                        :src="asset_url + 'images/logo.png'"
                        class="d-inline-block align-top"
                        alt=""
                        loading="lazy"
                    />
                </a>
                <div class="has-search d-none d-md-block">
                    <form autocomplete="off" :action="search_url">
                        <span class="fa fa-search form-control-feedback"></span>
                        <input
                            type="text"
                            class="form-control search-box"
                            name="nft"
                            placeholder="Search by collector, owner, NFT"
                            v-model="searchSlug"
                        />
                        <input
                            type="submit"
                            style="position: absolute; left: -9999px"
                        />
                    </form>
                </div>
            </div>

            <div class="search-nav d-sm-block d-md-none onclick-search">
                <a class="back-btn" href="javascript:void(0)">
                    <i class="fa fa-arrow-left arrow-back"></i>
                </a>
                <div class="has-search-mobile">
                    <form autocomplete="off" :action="search_url">
                        <span class="fa fa-search form-control-feedback"></span>
                        <input
                            type="text"
                            class="form-control search-box"
                            name="nft"
                            placeholder="Search by collector, owner, NFT"
                            v-model="searchSlug"
                        />
                    </form>
                </div>
            </div>

            <div class="right-nav d-none d-md-block default-nav">
                <a
                    class="nav-item right-border"
                    :class="request_segment == 'nft' ? 'active' : ''"
                    :href="marketplace_route"
                    ><span>Marketplace</span></a
                >
                <a
                    v-if="auth_check"
                    class="nav-item right-border"
                    :class="request_segment == 'profile' ? 'active' : ''"
                    :href="profile_route + '/' + user_link"
                    >My NFT's</a
                >
                <a
                    class="nav-item right-border"
                    :class="request_segment == 'faq' ? 'active' : ''"
                    :href="faq_route"
                    >FAQ</a
                >
                <a
                    class="nav-item right-border"
                    :class="request_segment == 'bhc' ? 'active' : ''"
                    :href="about_route"
                    >BHC</a
                >
                <a :href="create_collectible_route" class="btn nav-create"
                    >Create</a
                >

                <a
                    v-if="auth_check"
                    class="nav-item notif-btn"
                    @click="toggleNotification"
                >
                    <span v-if="this.notifications.length > 0" class="count">{{
                        this.notifications.length
                    }}</span>

                    <img
                        :src="asset_url + 'images/logo2.png'"
                        width="35"
                        class="d-inline-block align-top"
                        alt=""
                        loading="lazy"
                    />
                </a>
                <a
                    v-if="auth_check"
                    class="nav-item profile-btn"
                    :href="profile_route + '/' + user_link"
                    @mouseover="identifyState(false)"
                >
                    <img
                        v-if="current_user.display_photo == 'default.png'"
                        class="navbar-img-profile d-inline-block align-top"
                        :src="userPhoto"
                        width="30"
                        alt=""
                        loading="lazy"
                    />

                    <img
                        v-if="current_user.display_photo != 'default.png'"
                        class="navbar-img-profile d-inline-block align-top"
                        :src="userPhoto"
                        width="30"
                        alt=""
                        loading="lazy"
                    />
                </a>

                <a
                    v-if="!auth_check"
                    :href="connect_route"
                    class="btn nav-connect"
                    >{{ account }}</a
                >
            </div>

            <div class="right-nav d-sm-block d-md-none d-lg-none default-nav">
                <a class="nav-item search-mobile" href="javascript:void(0)"
                    ><i class="fa fa-search"></i
                ></a>
                <a
                    class="nav-item notif-btn notif-mobile"
                    href="javascript:void(0)"
                >
                    <span v-if="this.notifications.length > 0 && !this.viewed" class="count" @click="setViewed()">{{
                        this.notifications.length
                    }}</span>
                    <img
                        :src="asset_url + 'images/logo2.png'"
                        width="30"
                        class="d-inline-block align-top"
                        alt=""
                        loading="lazy"
                        @click="setViewed()"
                    />
                </a>
                <a class="nav-item hamburger-menu" href="javascript:void(0)"
                    ><i class="fa fa-bars"></i
                ></a>

                <a
                    v-if="auth_check"
                    class="nav-item profile-btn"
                    :href="profile_route + '/' + user_link"
                >
                    <img
                        v-if="current_user.display_photo == 'default.png'"
                        class="navbar-img-profile d-inline-block align-top"
                        :src="userPhoto"
                        width="30"
                        alt=""
                        loading="lazy"
                    />

                    <img
                        v-if="current_user.display_photo != 'default.png'"
                        class="navbar-img-profile d-inline-block align-top"
                        :src="userPhoto"
                        width="30"
                        alt=""
                        loading="lazy"
                    />
                </a>
            </div>

            <div v-if="auth_check" class="d-sm-block d-md-none onclick-menu">
                <a class="nav-item menu-close" href="javascript:void(0)">
                    <i class="fa fa-close"></i>
                </a>
            </div>

            <notification-component
                v-if="auth_check"
                :asset_url="asset_url"
            ></notification-component>

            <div
                v-if="auth_check"
                class="profile-menu d-none"
                @mouseleave="identifyState(true)"
            >
                <div class="name-section">
                    <h6>
                        {{
                            current_user.name != null
                                ? current_user.name
                                : current_user.wallet
                        }}
                    </h6>
                    <a
                        v-if="current_user.name == null"
                        class="preferencesBtn"
                        href="javascript:void(0)"
                        @click="getUser()"
                        >Set display name</a
                    >
                </div>
                <div class="menu-options">
                    <a :href="profile_route + '/' + user_link">My account</a>
                    <a
                        id="edit-profile-btn"
                        href="javascript:void(0)"
                        @click="getUser()"
                        >Edit profile</a
                    >

                    <!--a href="javascript:void(0)">Manage funds</a>
                    < <a href="javascript:void(0)">

		        <div class="row">
		          <div class="col-sm-10 col-md-10">GIF/Video enabled</div>
		          <div class="col-sm-2 col-md-2"></div>
		        </div>

		      </a> >
                    <a :href="disconnect_route">Disconnect</a-->
                </div>
            </div>
        </nav>
        <div class="mobile-menu onclick-menu">
            <div class="menu-links">
                <a :href="marketplace_route">Marketplace</a>
                <a :href="profile_route + '/' + user_link">My NFT's</a>
                <a :href="faq_route">FAQ</a>
                <a :href="about_route">BHC</a>
            </div>
            <div class="menu-footer">
                <div class="footer-social">
                    <a
                        href="javascript:void(0)"
                        data-sharer="twitter"
                        data-title="Billion Happiness"
                        :data-url="asset_url"
                    >
                        <img :src="asset_url + 'images/twitter.png'" />
                    </a>
                    <a
                        href="javascript:void(0)"
                        data-sharer="telegram"
                        data-title="Billion Happiness"
                        :data-url="asset_url"
                    >
                        <img :src="asset_url + 'images/telegram.png'" />
                    </a>
                    <a href="javascript:void(0)">
                        <img :src="asset_url + 'images/message.png'" />
                    </a>
                </div>
                <div class="footer-btn">
                    <a :href="create_collectible_route" class="btnPrimary"
                        >Create</a
                    >
                    <a
                        v-if="!auth_check"
                        :href="connect_route"
                        class="btnSecondary"
                        >{{ account }}</a
                    >
                </div>
            </div>
        </div>
        <preferences-modal-component
            :asset_url="asset_url"
            :csrf_token="csrf_token"
            :user_data="current_user"
            :userPhoto="userPhoto"
        ></preferences-modal-component>

        <wrong-network-modal-component
            v-if="wrongNet"
        ></wrong-network-modal-component>

        <toast-component :asset_url="asset_url"></toast-component>
    </div>
</template>

<script>
import { getUserDetails, tempUserData } from "./../data";
import { toAddress, checkConnection } from "./../etherFunc";
import WrongNetworkModalComponent from "./modals/WrongNetworkModalComponent.vue";
export default {
    components: { WrongNetworkModalComponent },
    props: [
        "marketplace_route",
        "asset_url",
        "request_segment",
        "profile_route",
        //"current_user",
        //"user_link",
        "faq_route",
        "about_route",
        "create_collectible_route",
        "connect_route",
        "disconnect_route",
        "str_random",
        "csrf_token",
        "base_url",
        "search_url",
    ],
    data() {
        return {
            userPhoto: "",
            searchSlug: "",
            account: "Connect",
            selectedAddress: "",
            auth_check: false,
            current_user: tempUserData(""),
            user_link: "",
            mouse_leave: false,
            notifications: [],
            wrongNet: false,viewed :true
        };
    },
   
   
    

    methods: {
        async getNotifications() {
            let data = {};
            data.user_id = await checkConnection();
            axios
                .post("/notifications", data)
                .then(res => {
                    this.notifications = res.data;
                    this.viewed = this.notifications[0].viewed;
                })
                .catch(error => {
                    //removed//console.log(error);
                });
        },
        async setViewed() {
            this.viewed = true;
            let data = {};
            data.user_id = await checkConnection();
             axios
                .post("/setviewed", data)
                .then(res => {
                })
                .catch(error => {
                    //removed//console.log(error);
                });
        },
        checkConnection: async function() {
            const _this = this;
            //var interval = setInterval(async function() {
            var acc = await checkConnection();

            if (acc != toAddress("")) {
                _this.auth_check = true;
                _this.current_user.wallet = acc;
                _this.selectedAddress = acc;
                _this.account = acc.substring(0, 10) + "...";
                _this.user_link = toAddress(acc);
                _this.current_user = await getUserDetails(acc);
                _this.userPhoto = _this.current_user.display_photo;
                //clearInterval(interval);
            }
            _this.wrongNet = window.wrongNetwork;
            //}, 300);
        },
        bidEvent() {
            Pusher.logToConsole = false;
            var pusher = new Pusher("28dafea922cb7250b6e0", {
                cluster: "ap1"
            });

            var channel = pusher.subscribe(
                "bid-channel_" + this.current_user.id
            );
            channel.bind("place-bid", function(data) {
                var html =
                    '<div class="notif-item">\
								<a href="' +
                    data.transactionData.asset_url +
                    "profile/" +
                    data.transactionData.profile_link +
                    '"><img src="' +
                    data.transactionData.display_photo +
                    '" class="notif-img"></a>\
								<label><a href="' +
                    data.transactionData.asset_url +
                    "profile/" +
                    data.transactionData.profile_link +
                    '" class="user-profile">' +
                    data.transactionData.profile_name +
                    "</a> " +
                    data.transactionData.action +
                    '<span class="notif-item">' +
                    data.transactionData.nft +
                    "</span><small>" +
                    data.transactionData.transaction_time +
                    " ago</small></label>\
							</div>";

                $(".notif-list").prepend(html);

                $(".notif").addClass("d-none");
                $(".notif-list").removeClass("d-none");
                $(".notif-tab").removeClass("d-none");
                $(".notif-tab").addClass(".notif-fade");
                $(".notif-content").text(
                    data.transactionData.profile_name +
                        " " +
                        data.transactionData.action +
                        " " +
                        data.transactionData.nft
                );
                setTimeout(function() {
                    $(".notif-tab").removeClass(".notif-fade");
                    $(".notif-tab").addClass("d-none");
                }, 6000);
            });
        },
        toggleNotification: function() {
            var container = $(".notification");
            if (!container.hasClass("fade-in-top")) {
                $(".notification").toggleClass("d-md-block");
                container.addClass("fade-in-top").removeClass("fade-out-top");
            } else {
                container.addClass("fade-out-top").removeClass("fade-in-top");
                setTimeout(function() {
                    $(".notification").toggleClass("d-md-block");
                }, 400);
            }
        },
        identifyState: function(leave) {
            const _this = this;
            if (leave) {
                $(".profile-btn").removeClass("hovered");
            }
            var container = $(".profile-menu");
            if (!$(".profile-menu").hasClass("fade-in-top")) {
                container.toggleClass("d-md-block");
                container.addClass("fade-in-top").removeClass("fade-out-top");
                $(".profile-btn").addClass("hovered");
            } else {
                if (!$(".profile-btn").hasClass("hovered")) {
                    container
                        .removeClass("fade-in-top")
                        .addClass("fade-out-top");
                    $(".profile-btn").removeClass("hovered");
                    setTimeout(function() {
                        container.toggleClass("d-md-block");
                    }, 200);
                }
            }
        },
        getUser() {
            $("#preferencesModal").addClass("d-block");
            $(".preferences-content")
                .removeClass("fade-out-bottom")
                .addClass("fade-in-bottom");
        }
    },
    async mounted() {
        await this.checkConnection();
        //this.bidEvent();
        $(".menu-close").click(function() {
            $(".onclick-menu").hide();
            $(".faq-container").fadeIn();
            $(".about-bhc").fadeIn();
            $(".user-profile-page").fadeIn();
            $(".main-content").fadeIn();
            $(".default-nav").fadeIn();
        });
        try {
            await this.getNotifications();
        } catch (err) {
            //removed//console.log("error");
        }
    }
};
</script>
