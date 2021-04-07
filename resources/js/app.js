require("./bootstrap");
import { ethers } from "ethers";

import Vue from "vue";

import Swal from "sweetalert2";
window.Swal = Swal;
const Toast = Swal.mixin({
    toast: true,
    position: "top-start",
    showConfirmButton: false,
    timer: 5000,
    timerProgressBar: true,
    didOpen: toast => {
        toast.addEventListener("mouseenter", Swal.stopTimer);
        toast.addEventListener("mouseleave", Swal.resumeTimer);
    }
});

window.Toast = Toast;

window.ethers = ethers;

window.Vue = require("vue").default;
Vue.component("wc-component", require("./components/wcComponent.vue"));
Vue.component(
    "example-component",
    require("./components/ExampleComponent.vue").default
);
Vue.component(
    "header-component",
    require("./components/HeaderComponent.vue").default
);
Vue.component(
    "main-page-component",
    require("./components/MainPageComponent.vue").default
);
Vue.component(
    "notification-component",
    require("./components/NotificationComponent.vue").default
);
Vue.component(
    "wallet-component",
    require("./components/WalletComponent.vue").default
);
Vue.component(
    "toast-component",
    require("./components/ToastComponent.vue").default
);
Vue.component(
    "social-link-component",
    require("./components/SocialLinkComponent.vue").default
);
Vue.component(
    "custom-social-link-component",
    require("./components/CustomSocialLinkComponent.vue").default
);
Vue.component(
    "footer-component",
    require("./components/FooterComponent.vue").default
);
Vue.component(
    "right-background-component",
    require("./components/RightBackgroundComponent.vue").default
);
Vue.component(
    "faq-heading-component",
    require("./components/FaqHeadingComponent.vue").default
);
Vue.component(
    "faq-body-component",
    require("./components/FaqBodyComponent.vue").default
);
Vue.component(
    "bhc-heading-component",
    require("./components/BhcHeadingComponent.vue").default
);
Vue.component(
    "bhc-heading-component",
    require("./components/BhcHeadingComponent.vue").default
);
Vue.component(
    "bhc-body-component",
    require("./components/BhcBodyComponent.vue").default
);
Vue.component(
    "bhc-how-component",
    require("./components/BhcHowComponent.vue").default
);
Vue.component(
    "landing-sidebar-component",
    require("./components/LandingSidebarComponent.vue").default
);
Vue.component(
    "landing-body-component",
    require("./components/LandingBodyComponent.vue").default
);
Vue.component(
    "wallet-sidebar-component",
    require("./components/WalletSidebarComponent.vue").default
);
Vue.component(
    "wallet-body-component",
    require("./components/WalletBodyComponent.vue").default
);
Vue.component(
    "index-create-header-component",
    require("./components/IndexCreateHeaderComponent.vue").default
);
Vue.component(
    "index-create-body-component",
    require("./components/IndexCreateBodyComponent.vue").default
);
Vue.component(
    "index-create-right-component",
    require("./components/IndexCreateRightComponent.vue").default
);

Vue.component(
    "marketplace-component",
    require("./components/MarketplaceComponent.vue").default
);
Vue.component(
    "top-user-component",
    require("./components/TopUserComponent.vue").default
);

Vue.component(
    "collectible-component",
    require("./components/CollectibleComponent.vue").default
);
Vue.component(
    "create-collectible-component",
    require("./components/CreateCollectibleComponent.vue").default
);

Vue.component(
    "generate-collection-component",
    require("./components/GenerateCollectionComponent.vue").default
);
Vue.component(
    "category-component",
    require("./components/CategoryComponent.vue").default
);
Vue.component(
    "legend-component",
    require("./components/LegendComponent.vue").default
);
Vue.component(
    "collection-collectible-component",
    require("./components/CollectionCollectibleComponent.vue").default
);
Vue.component(
    "collection-info-component",
    require("./components/CollectionInfoComponent.vue").default
);

Vue.component(
    "profile-component",
    require("./components/ProfileComponent.vue").default
);
Vue.component(
    "profile-info-component",
    require("./components/ProfileInfoComponent.vue").default
);
//Vue.component('edit-cover-btn-component', require('./components/EditCoverBtnComponent.vue').default);
Vue.component(
    "profile-page",
    require("./components/pages/UserProfilePage.vue").default
);

// Show collectible page
Vue.component(
    "show-collectible-component",
    require("./components/ShowCollectibleComponent.vue").default
);
Vue.component(
    "collectible-details-component",
    require("./components/show_collectible/CollectibleDetailsComponent.vue")
    .default
);
Vue.component(
    "show-fullscreen-component",
    require("./components/show_collectible/ShowFullscreenComponent.vue").default
);
Vue.component(
    "collectible-page",
    require("./components/pages/CollectiblePage.vue").default
);

// MODALS
Vue.component(
    "bid-modal-component",
    require("./components/modals/BidModalComponent.vue").default
);
Vue.component(
    "checkout-modal-component",
    require("./components/modals/CheckoutModalComponent.vue").default
);
Vue.component(
    "puton-modal-component",
    require("./components/modals/PutOnSaleModalComponent.vue").default
);
Vue.component(
    "report-modal-component",
    require("./components/modals/ReportModalComponent.vue").default
);
Vue.component(
    "following-modal-component",
    require("./components/modals/FollowingModalComponent.vue").default
);
Vue.component(
    "follower-modal-component",
    require("./components/modals/FollowerModalComponent.vue").default
);
Vue.component(
    "bid-list-modal-component",
    require("./components/modals/BidListModalComponent.vue").default
);
Vue.component(
    "update-cover-modal-component",
    require("./components/modals/UpdateCoverModalComponent.vue").default
);
Vue.component(
    "preferences-modal-component",
    require("./components/modals/PreferencesModalComponent.vue").default
);
Vue.component(
    "create-collection-modal-component",
    require("./components/modals/CreateCollectionModalComponent.vue").default
);
Vue.component(
    "create-steps-modal-component",
    require("./components/modals/CreateStepsModalComponent.vue").default
);

Vue.component(
    "collection-page",
    require("./components/pages/CollectionPage.vue").default
);

window.moment = require("moment");

const app = new Vue({
    el: "#main-app"
});