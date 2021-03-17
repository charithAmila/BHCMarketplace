/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
import { ethers } from 'ethers';
import Vue from 'vue';
window.ethers = ethers;

window.Vue = require('vue').default;

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('example-component', require('./components/ExampleComponent.vue').default);
Vue.component('header-component', require('./components/HeaderComponent.vue').default);
Vue.component('main-page-component', require('./components/MainPageComponent.vue').default);
Vue.component('notification-component', require('./components/NotificationComponent.vue').default);
Vue.component('wallet-component', require('./components/WalletComponent.vue').default);
Vue.component('toast-component', require('./components/ToastComponent.vue').default);
Vue.component('social-link-component', require('./components/SocialLinkComponent.vue').default);
Vue.component('custom-social-link-component', require('./components/CustomSocialLinkComponent.vue').default);
Vue.component('footer-component', require('./components/FooterComponent.vue').default);
Vue.component('right-background-component', require('./components/RightBackgroundComponent.vue').default);
Vue.component('faq-heading-component', require('./components/FaqHeadingComponent.vue').default);
Vue.component('faq-body-component', require('./components/FaqBodyComponent.vue').default);
Vue.component('bhc-heading-component', require('./components/BhcHeadingComponent.vue').default);
Vue.component('bhc-heading-component', require('./components/BhcHeadingComponent.vue').default);
Vue.component('bhc-body-component', require('./components/BhcBodyComponent.vue').default);
Vue.component('bhc-how-component', require('./components/BhcHowComponent.vue').default);
Vue.component('landing-sidebar-component', require('./components/LandingSidebarComponent.vue').default);
Vue.component('landing-body-component', require('./components/LandingBodyComponent.vue').default);
Vue.component('wallet-sidebar-component', require('./components/WalletSidebarComponent.vue').default);
Vue.component('wallet-body-component', require('./components/WalletBodyComponent.vue').default);
Vue.component('index-create-header-component', require('./components/IndexCreateHeaderComponent.vue').default);
Vue.component('index-create-body-component', require('./components/IndexCreateBodyComponent.vue').default);
Vue.component('index-create-right-component', require('./components/IndexCreateRightComponent.vue').default);


Vue.component('marketplace-component', require('./components/MarketplaceComponent.vue').default);
Vue.component('top-user-component', require('./components/TopUserComponent.vue').default);

Vue.component('collectible-component', require('./components/CollectibleComponent.vue').default);
Vue.component('create-collectible-component', require('./components/CreateCollectibleComponent.vue').default);


Vue.component('generate-collection-component', require('./components/GenerateCollectionComponent.vue').default);
Vue.component('category-component', require('./components/CategoryComponent.vue').default);
Vue.component('legend-component', require('./components/LegendComponent.vue').default);
Vue.component('collection-collectible-component', require('./components/CollectionCollectibleComponent.vue').default);
Vue.component('collection-info-component', require('./components/CollectionInfoComponent.vue').default);


Vue.component('profile-component', require('./components/ProfileComponent.vue').default);
Vue.component('profile-info-component', require('./components/ProfileInfoComponent.vue').default);
//Vue.component('edit-cover-btn-component', require('./components/EditCoverBtnComponent.vue').default);
Vue.component('profile-page', require('./components/pages/UserProfilePage.vue').default);


// Show collectible page
Vue.component('show-collectible-component', require('./components/ShowCollectibleComponent.vue').default);
Vue.component('collectible-details-component', require('./components/show_collectible/CollectibleDetailsComponent.vue').default);
Vue.component('show-fullscreen-component', require('./components/show_collectible/ShowFullscreenComponent.vue').default);


// MODALS
Vue.component('bid-modal-component', require('./components/modals/BidModalComponent.vue').default);
Vue.component('checkout-modal-component', require('./components/modals/CheckoutModalComponent.vue').default);
Vue.component('report-modal-component', require('./components/modals/ReportModalComponent.vue').default);
Vue.component('following-modal-component', require('./components/modals/FollowingModalComponent.vue').default);
Vue.component('follower-modal-component', require('./components/modals/FollowerModalComponent.vue').default);
Vue.component('bid-list-modal-component', require('./components/modals/BidListModalComponent.vue').default);
Vue.component('update-cover-modal-component', require('./components/modals/UpdateCoverModalComponent.vue').default);
Vue.component('preferences-modal-component', require('./components/modals/PreferencesModalComponent.vue').default);
Vue.component('create-collection-modal-component', require('./components/modals/CreateCollectionModalComponent.vue').default);
Vue.component('create-steps-modal-component', require('./components/modals/CreateStepsModalComponent.vue').default);


/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#main-app',
});
