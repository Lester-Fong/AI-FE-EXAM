import { createApp } from "vue";
import { createWebHistory, createRouter } from "vue-router";
import Vuex from "vuex";
import VueSweetalert2 from "vue-sweetalert2";
import VueDatePicker from "@vuepic/vue-datepicker";
import App from "./App.vue";

import { onDatatable } from "./helper.js";
import user_query from "./queries.js";
import routes from "./router";
require("./bootstrap");
import "sweetalert2/dist/sweetalert2.min.css";
import "@vuepic/vue-datepicker/dist/main.css";

// creating the app and reding the App.vue file
const app = createApp(App);

// creating the router and reading the routes file
const router = createRouter({
    history: createWebHistory(),
    routes,
});

const writerNotAllowedRoutes = ["CompanyPage", "UserPage"];

router.beforeEach((to, from, next) => {
    // ADMIN
    if (to.matched.some((m) => m.meta.requiresAuth)) {
        if (sessionStorage.getItem("login_type") == null || sessionStorage.getItem("user_api_token") == null) {
            sessionStorage.clear();
            window.location.href = "/login";
        }
    }
    if (to.matched.some((m) => writerNotAllowedRoutes.includes(m.name)) && !store.state.userType) {
        next({ name: "DashboardPage" });
    }
    next();
});

const store = new Vuex.Store({
    state: {
        userRecord: {},
        userType: null,
    },

    mutations: {
        updateUserRecord(state, newRecord) {
            state.userRecord = newRecord;
            state.userType = state.userRecord.user_type;
        },
    },
});

// Gloabal properties
app.config.globalProperties.$user_query = user_query;
app.config.globalProperties.$onDatatable = onDatatable;

// using imported plugins
app.component("VueDatePicker", VueDatePicker);
app.use(VueSweetalert2);
app.use(store);
app.use(router);

// Vue3's way of mounting the app to the div with id app which is in the Welcome.blade.php file
app.mount("#app");
