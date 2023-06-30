import axios from "axios";
import moment from "moment";
import DatePicker from "vue2-datepicker";
import VueSelect from "vue-select";

import EmployeeHasParent from "../../EmployeeHasParent/view/employeeHasParent";

export default {
    data() {
        return {
            is_loading: false,
            getTitleForm: "Tambah Penyesuaian Gaji",
        };
    },
    components: {
        VueSelect,
        DatePicker,
        EmployeeHasParent,
    },
    mounted() {
        this.$store.commit("employeeHasParent/UPDATE_IS_MOBILE", {
            value: false,
        });
    },
    computed: {
        getBaseUrl() {
            return this.$store.state.base_url;
        },
        getUserId() {
            return this.$store.state.user?.id;
        },
        getOptionTypeTimes() {
            return this.$store.state.salaryAdjustment.options.type_times;
        },
        getOptionTypeAmount() {
            return this.$store.state.salaryAdjustment.options.type_amounts;
        },
        getOptionTypeAdjustments() {
            return this.$store.state.salaryAdjustment.options.type_adjustments;
        },
        getOptionHolidayAllowances() {
            return this.$store.state.salaryAdjustment.options.holiday_allowances;
        },
        getEmployeeHasParentForm() {
            return this.$store.state.employeeHasParent.form;
        },
        getEmployeeSelecteds() {
            return this.$store.state.employeeHasParent.data.selecteds;
        },
        getJobOrderMonthFilter() {
            const month = this.$store.state.jobOrder.params.month;
            return moment(month).format("Y-MM-01");
        },
        getProjectMonthFilter() {
            const month = this.$store.state.project.params.month;
            return moment(month).format("Y-MM-01");
        },
        // getJobOrderId() {
        //     return this.$store.state.jobOrder.data.find(item => item.is_selected)?.id;
        // },
        // getProjectId() {
        //     return this.$store.state.project.data.find(item => item.is_selected)?.id;
        // },
        form() {
            return this.$store.state.salaryAdjustment.form;
        },
        amount: {
            get() {
                return this.$store.state.salaryAdjustment.form.amount_readable;
            },
            set(value) {
                this.$store.commit("salaryAdjustment/INSERT_FORM_AMOUNT", {
                    amount: value,
                });
            },
        },
    },
    methods: {
        onCloseModal() {
            this.$bvModal.hide("salary_adjustment_form");
        },
        onChangeRangeMonth() {
            //
        },
        onActiveDateEnd() {
            // console.info(this.form.is_month_end);
            this.$store.commit("salaryAdjustment/UPDATE_FORM_IS_DATE_END", {
                value: !this.form.is_month_end,
            });
        },
        onShowEmployee() {
            this.$bvModal.show("data_employee");
        },
        onReplaceAmount($event) {
            let keyCode = $event.keyCode ? $event.keyCode : $event.which;
            //   console.info(keyCode);

            //   this.amount = this.amount.replace(/[^\d.:]/g, "");
            if ((keyCode < 48 || keyCode > 57) && keyCode !== 44) {
                // 46 is dot
                $event.preventDefault();
            }
        },
        async onSend() {
            const Swal = this.$swal;

            let getMonthFilter = null;

            if (this.getEmployeeHasParentForm.employee_base == 'job_order') {
                getMonthFilter = this.getJobOrderMonthFilter;
            } else if (this.getEmployeeHasParentForm.employee_base == 'project') {
                getMonthFilter = this.getProjectMonthFilter;
            }

            const request = {
                ...this.form,
                month_start: moment(this.form.month_start).format("Y-MM-DD"),
                month_end: moment(this.form.month_end).format("Y-MM-DD"),
                position_id: this.getEmployeeHasParentForm.position_id,
                job_order_id: this.getEmployeeHasParentForm.job_order_id,
                project_id: this.getEmployeeHasParentForm.project_id,
                employee_base: this.getEmployeeHasParentForm.employee_base,
                employee_selecteds: this.getEmployeeSelecteds,
                month_filter_has_parent: getMonthFilter,
                user_id: this.getUserId,
            };

            console.info(request);
            // return false;
            this.is_loading = true;

            await axios
                .post(`${this.getBaseUrl}/api/v1/salary-adjustment/store`, request)
                .then((responses) => {
                    console.info(responses);
                    this.is_loading = false;
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

                        this.$bvModal.hide("salary_adjustment_form");
                        this.$store.dispatch("salaryAdjustment/fetchData");
                        this.$store.commit("salaryAdjustment/CLEAR_FORM");
                        this.$store.commit("employeeHasParent/CLEAR_FORM");
                        this.$store.commit("employeeHasParent/CLEAR_DATA_SELECTED");
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
        getReadOnly() {
            const readOnly = this.$store.getters["salaryAdjustment/getReadOnly"];
            //   console.info(readOnly);

            return readOnly;
        },
    },
};
