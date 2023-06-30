window.Vue = require("vue").default;
import store from "./stores/main";

import Vue from "vue";

import moment from "moment";
import VueEvents from "vue-events";
import BootstrapVue from "bootstrap-vue";
import VueSweetalert2 from "vue-sweetalert2";
import VueBottomSheet from "@webzlodimir/vue-bottom-sheet";
import { ServerTable, ClientTable, Event } from "vue-tables-2";
import clickOutside from './vue-directive-clickOutside';

import 'vue2-datepicker/index.css';
import "vue-select/dist/vue-select.css";
import "sweetalert2/dist/sweetalert2.min.css";
import "bootstrap-vue/dist/bootstrap-vue.css";

moment.locale("id");

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component("roster", require("./modules/Roster/roster.vue").default);
Vue.component("vacation", require("./modules/Vacation/vacation.vue").default);
Vue.component("project", require("./modules/Project/View/project.vue").default);
Vue.component("dashboard", require("./modules/Dashboard/dashboard.vue").default);
Vue.component("job-order", require("./modules/JobOrder/View/jobOrder.vue").default);
Vue.component("attendance", require("./modules/Attendance/attendance.vue").default);
Vue.component("overtimeReport", require("./modules/OvertimeReport/overtimeReport.vue").default);
Vue.component("job-order-report", require("./modules/JobOrderReport/jobOrderReport.vue").default);
Vue.component("salary-advance", require("./modules/SalaryAdvance/View/salaryAdvance.vue").default);
Vue.component("salary-adjustment", require("./modules/SalaryAdjustment/View/salaryAdjustment.vue").default);
Vue.component("salary-advance-report", require("./modules/SalaryAdvanceReport/View/salaryAdvanceReport.vue").default);

Vue.directive('click-outside', clickOutside);
Vue.config.productionTip = false;

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.use(VueBottomSheet);
Vue.use(VueEvents);
Vue.use(BootstrapVue);
Vue.use(VueSweetalert2);
Vue.use(ServerTable);
Vue.use(ClientTable);

new Vue({
    store,
    el: "#root",
});
