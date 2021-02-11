require("./bootstrap");

window.Vue = require("vue").default;
import JQuery from "jquery";

window.$ = jQuery;

require("./custom");
require("./dropdown");

Vue.component(
    "example-component",
    require("./components/ExampleComponent.vue").default
);
Vue.component(
    "wallet-component",
    require("./components/WalletComponent.vue").default
);

Vue.component(
    "navigation-component",
    require("./components/NavigationComponent.vue").default
);
Vue.component(
    "checkout-modal",
    require("./components/modals/CheckoutModal.vue").default
);
Vue.component("bid-modal", require("./components/modals/BidModal.vue").default);
Vue.component(
    "report-modal",
    require("./components/modals/ReportModal.vue").default
);

const app = new Vue({
    el: "#app"
});