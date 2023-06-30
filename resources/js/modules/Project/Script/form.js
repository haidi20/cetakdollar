import axios from "axios";
import moment from "moment";
import VueSelect from "vue-select";

export default {
    data() {
        return {
            is_loading: false,
        };
    },
    components: {
        VueSelect,
    },
    computed: {
        getBaseUrl() {
            return this.$store.state.base_url;
        },
        getUserId() {
            return this.$store.state.user?.id;
        },
        getOptionBarges() {
            return this.$store.state.master.data.barges;
        },
        getOptionCompanies() {
            return this.$store.state.master.data.companies;
        },
        getOptionLocations() {
            return this.$store.state.master.data.locations;
        },
        getOptionTypes() {
            return this.$store.state.project.options.types;
        },
        getOptionJobs() {
            return this.$store.state.master.data.jobs;
        },
        getOptionForemans() {
            return this.$store.state.employeeHasParent.data.foremans;
        },
        form() {
            return this.$store.state.project.form;
        },
        price: {
            get() {
                return this.$store.state.project.form.price_readable;
            },
            set(value) {
                this.$store.commit("project/INSERT_FORM_PRICE", {
                    price: value,
                });
            },
        },
        down_payment: {
            get() {
                return this.$store.state.project.form.down_payment_readable;
            },
            set(value) {
                this.$store.commit("project/INSERT_FORM_DOWN_PAYMENT", {
                    down_payment: value,
                });
            },
        },
        date_end: {
            get() {
                return this.$store.state.project.form.date_end;
            },
            set(value) {
                this.$store.commit("project/INSERT_FORM_DATE_END", {
                    date_end: value,
                });
            },
        },
    },
    watch: {
        price: {
            deep: true,
            handler(newValue, oldValue) {
                // console.info(newValue);
                this.$store.commit("project/INSERT_FORM_REMAINING_PAYMENT");
            }
        },
        down_payment(value, oldValue) {
            // console.info(value);
            this.$store.commit("project/INSERT_FORM_REMAINING_PAYMENT");
        },
        date_end(value, oldValue) {
            this.$store.commit("project/INSERT_FORM_DAY_DURATION");
        },
    },
    methods: {
        onCloseModal() {
            this.$store.commit("project/CLEAR_FORM");
            this.$bvModal.hide("project_form");
        },
        onChangeTab(type) {
            // console.info(type);
        },
        onNumberOnly(evt) {
            // console.info("change");
            this.$store.dispatch("onNumberOnly", { evt: evt });
        },
        getReadOnly() {
            const readOnly = this.$store.getters["project/getReadOnly"];
            //   console.info(readOnly);

            return readOnly;
        },
        disabledDate(date, currentValue) {
            return date <= moment();
        },
    },
};
