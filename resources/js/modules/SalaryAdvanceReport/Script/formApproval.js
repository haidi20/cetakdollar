import axios from "axios";
import moment from "moment";
import VueSelect from "vue-select";

export default {
    data() {
        return {
            is_loading: false,
            getTitleForm: "Persetujuan Kasbon",
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
        getUserGroupName() {
            return this.$store.state.user?.group_name;
        },
        getOptionEmployees() {
            return this.$store.state.employee.data.options;
        },
        getOptionTypes() {
            return this.$store.state.salaryAdvanceReport.options.types;
        },
        getOptionPaymentMethods() {
            return this.$store.state.salaryAdvanceReport.options.payment_methods;
        },
        form() {
            return this.$store.state.salaryAdvanceReport.form;
        },
        monthly_deduction: {
            get() {
                return this.$store.state.salaryAdvanceReport.form
                    .monthly_deduction_readable;
            },
            set(value) {
                this.$store.commit(
                    "salaryAdvanceReport/INSERT_FORM_MONTHLY_DEDUCTION",
                    {
                        monthly_deduction: value,
                    }
                );
            },
        },
    },
    methods: {
        onCloseModal() {
            this.$bvModal.hide("salary_advance_approval_form");
        },
        async onSend() {
            const Swal = this.$swal;

            const request = {
                ...this.form,
                user_id: this.getUserId,
            };

            const getCheckDuration = this.getCheckDuration();

            if (!getCheckDuration) {
                return false;
            }

            // console.info(request);
            this.is_loading = true;
            await axios
                .post(
                    `${this.getBaseUrl}/api/v1/salary-advance/store-approval`,
                    request
                )
                .then((responses) => {
                    console.info(responses);

                    this.is_loading = false;

                    //   return false;
                    const data = responses.data;

                    const Toast = Swal.mixin({
                        toast: true,
                        position: "top-end",
                        showConfirmButton: false,
                        timer: 4000,
                        timerProgressBar: true,
                        didOpen: (toast) => {
                            toast.addEventListener("mouseenter", Swal.stopTimer);
                            toast.addEventListener("mouseleave", Swal.resumeTimer);
                        },
                    });

                    if (data.success == true) {
                        Toast.fire({
                            icon: "success",
                            title: data.message,
                        });

                        this.$bvModal.hide("salary_advance_approval_form");
                        this.$store.dispatch("salaryAdvanceReport/fetchData", {
                            user_id: this.getUserId,
                        });
                    }
                })
                .catch((err) => {
                    console.info(err);
                    this.is_loading = false;

                    const Toast = Swal.mixin({
                        toast: true,
                        position: "top-end",
                        showConfirmButton: false,
                        timer: 4000,
                        timerProgressBar: true,
                        didOpen: (toast) => {
                            toast.addEventListener("mouseenter", Swal.stopTimer);
                            toast.addEventListener("mouseleave", Swal.resumeTimer);
                        },
                    });

                    Toast.fire({
                        icon: "error",
                        title: err.response.data.message,
                    });
                });
        },
        getDeductionFormula() {
            const getResult =
                this.$store.getters["salaryAdvanceReport/getDeductionFormula"];

            this.$store.commit("salaryAdvanceReport/INSERT_FORM_MONTHLY_DEDUCTION", {
                monthly_deduction: getResult,
            });

            return getResult;
        },
        getReadOnly() {
            const readOnly = this.$store.getters["salaryAdvanceReport/getReadOnly"];
            //   console.info(readOnly);

            return readOnly;
        },
        getCheckDuration() {
            if (this.form.duration == null || this.form.duration == "") {
                const Toast = Swal.mixin({
                    toast: true,
                    position: "top-end",
                    showConfirmButton: false,
                    timer: 4000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.addEventListener("mouseenter", Swal.stopTimer);
                        toast.addEventListener("mouseleave", Swal.resumeTimer);
                    },
                });

                const roleHrd = this.form.approval_agreement_level == 2 ? "oleh HRD" : null;

                Toast.fire({
                    icon: "warning",
                    title: `Maaf, durasi harus di isi ${roleHrd} terlebih dahulu`,
                });

                return false;
            }

            return true;
        },
    },
};
