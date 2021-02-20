/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

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
Vue.component('wallet-component', require('./components/WalletComponent.vue').default);
Vue.component('marketplace-component', require('./components/MarketplaceComponent.vue').default);
Vue.component('collectible-component', require('./components/CollectibleComponent.vue').default);
Vue.component('profile-component', require('./components/ProfileComponent.vue').default);

// Show collectible page
Vue.component('show-collectible-component', require('./components/ShowCollectibleComponent.vue').default);
Vue.component('collectible-details-component', require('./components/show_collectible/CollectibleDetailsComponent.vue').default);


// MODALS
Vue.component('bid-modal-component', require('./components/modals/BidModalComponent.vue').default);
Vue.component('checkout-modal-component', require('./components/modals/CheckoutModalComponent.vue').default);
Vue.component('following-modal-component', require('./components/modals/FollowingModalComponent.vue').default);
Vue.component('follower-modal-component', require('./components/modals/FollowerModalComponent.vue').default);
Vue.component('bid-list-modal-component', require('./components/modals/BidListModalComponent.vue').default);


/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#main-app',
});
