import axios from "axios";
import moment from "moment";
import DatatableClient from "../../../components/DatatableClient";

export default {
    data() {
        return {
            is_loading_export: false,
            columns: [
                {
                    label: "Nama",
                    field: "name",
                    width: "100px",
                    class: "",
                },
                {
                    label: "Waktu",
                    field: "type_time_readable",
                    width: "100px",
                    class: "",
                },
                {
                    label: "Nilai",
                    field: "amount_readable",
                    width: "100px",
                    class: "",
                },
                {
                    label: "Jenis",
                    field: "type_adjustment_name",
                    width: "100px",
                    class: "",
                },
                {
                    label: "Keterangan",
                    field: "note",
                    width: "100px",
                    class: "",
                },
                {
                    label: "",
                    class: "",
                    width: "20px",
                },
            ],
            options: {
                perPage: 5,
                // perPageValues: [5, 10, 25, 50, 100],
            },
        };
    },
    components: {
        DatatableClient,
    },
    computed: {
        getBaseUrl() {
            return this.$store.state.base_url;
        },
        getUserId() {
            return this.$store.state.user?.id;
        },
        getData() {
            return this.$store.state.salaryAdjustment.data;
        },
        params() {
            return this.$store.state.salaryAdjustment.params;
        },
    },
    methods: {
        onExport() {
            //
        },
        // edit or detail
        onAction(type, form) {
            //   this.$store.commit("salaryAdjustment/CLEAR_FORM");

            this.$store.commit("salaryAdjustment/INSERT_FORM", {
                form,
                form_type: type,
            });
            this.$store.commit("employeeHasParent/INSERT_FORM", {
                form: {
                    position_id: form.position_id,
                    job_order_id: form.job_order_id,
                    project_id: form.project_id,
                    employee_base: form.employee_base,
                },
                form_type: type,
            });


            if (form.employee_base == 'choose_employee') {
                this.$store.commit("employeeHasParent/INSERT_DATA_ALL_SELECTED", {
                    selecteds: [...form.salary_adjustment_details],
                });
            } else if (form.employee_base == 'project') {
                this.$store.commit("project/UPDATE_DATA_IS_SELECTED_TRUE", { id: form.project_id });
                this.$store.commit("project/INSERT_FORM_ID", { id: form.project_id });
                this.$store.commit("project/INSERT_PARAM_MONTH", { month: form.month_filter_has_parent });
                this.$store.dispatch("project/fetchDataBaseJobOrderFinish");
            } else if (form.employee_base == 'job_order') {
                this.$store.commit("jobOrder/UPDATE_DATA_IS_SELECTED_TRUE", { id: form.job_order_id });
                this.$store.commit("jobOrder/INSERT_FORM_ID", { id: form.job_order_id });
                this.$store.commit("jobOrder/INSERT_PARAM_MONTH", { month: form.month_filter_has_parent });
                this.$store.dispatch("jobOrder/fetchDataFinish");
            }

            this.$bvModal.show("salary_adjustment_form");
        },
        onFilter() {
            //   console.info(this.params);
            this.$store.dispatch("salaryAdjustment/fetchData");
        },
        async onDelete(data) {
            // console.info(data);

            const Swal = this.$swal;
            await Swal.fire({
                title: "Perhatian!!!",
                html: `Anda yakin ingin hapus data <h2><b>${data.name}</b> ?</h2>`,
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Ya hapus",
                cancelButtonText: "Batal",
            }).then(async (result) => {
                if (result.isConfirmed) {
                    await axios
                        .post(`${this.getBaseUrl}/api/v1/salary-adjustment/delete`, {
                            id: data.id,
                            user_id: this.getUserId,
                        })
                        .then((responses) => {
                            //   console.info(responses);
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

                                this.$store.dispatch("salaryAdjustment/fetchData");
                            }
                        })
                        .catch((err) => {
                            console.info(err);

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
                }
            });
        },
        getColumns() {
            const columns = this.columns.filter((item) => item.label != "");
            return columns;
        },
    },
};
