import DatatableClient from "../../../components/DatatableClient";

export default {
    data() {
        return {
            columns: [
                {
                    label: "Nama Karyawan",
                    field: "employee_name",
                    width: "250px",
                    class: "",
                },
                {
                    label: "Nama Departemen",
                    field: "position_name",
                    width: "150px",
                    class: "",
                },
                {
                    label: "",
                    class: "",
                    width: "0px",
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
        getJobOrderStatus() {
            return this.$store.state.jobOrder.form.status;
        },
        getJobOrderFormKind() {
            return this.$store.state.jobOrder.form.form_kind;
        },
        getData() {
            return this.$store.state.employeeHasParent.data.selecteds;
        },
        getIsMobile() {
            return this.$store.state.employeeHasParent.data.selecteds;
        },
        getForm() {
            return this.$store.state.employeeHasParent.form;
        },
        params() {
            return this.$store.state.employeeHasParent.params;
        },
        search() {
            return this.$store.state.employeeHasParent.params.search;
        },
    },
    watch: {
        //
    },
    methods: {
        onSearch() {
            console.info(this.search);
        },
        onDelete() {
            const index = this.getForm.data_index;

            // console.info(index);
            this.$store.commit("employeeHasParent/DELETE_DATA_SELECTED", { index });
            this.$bvModal.hide("action_list_employee");
        },
        onDeleteAll() {
            this.$store.commit("employeeHasParent/CLEAR_DATA_SELECTED");
        },
        onOpenAction(item, index) {
            // console.info(item);

            if (this.getUserId == item.created_by && this.getJobOrderFormKind != 'read') {
                this.$store.commit("employeeHasParent/INSERT_FORM", { form: { ...item, data_index: index } });
                this.$bvModal.show("action_list_employee");
            }
        },
        onAction(form_type, form_title) {
            this.$store.commit("employeeHasParent/INSERT_FORM_FORM_TYPE", { form_type });
            this.$store.commit("employeeHasParent/UPDATE_DATA_SELECTED_STATUS", { form_type });

            this.$bvModal.hide("action_list_employee");
        },
        onNonActiveOvertime() {
            // console.info(this.getForm.employee_id);
            this.$store.commit("employeeHasParent/UPDATE_DATA_SELECTED_STATUS", { form_type: "active" });
            this.$bvModal.hide("action_list_employee");
        },
        onActionOvertimeAgain() {
            this.$store.commit("employeeHasParent/UPDATE_DATA_SELECTED_STATUS", { form_type: "overtime" });
            this.$bvModal.hide("action_list_employee");
        },
        getConditionActionActive() {
            let result = false;
            const listNotFormKind = ['overtime'];

            // console.info(this.getJobOrderFormKind);
            // && this.getJobOrderFormKind != null
            // && listNotFormKind.some(item => item != this.getJobOrderFormKind)

            if (
                (
                    this.getForm.status == 'pending'
                    || this.getForm.status == 'finish'
                    || this.getForm.status == 'overtime'
                )
                && this.getForm.status_clone != 'overtime'
                && this.getJobOrderFormKind != 'overtime'
            ) {
                result = true;
            }

            return result;
        },
        getConditionActionPending() {
            let result = false;

            // console.info(this.getForm);

            if (
                this.getForm.status_clone == 'active'
                && this.getForm.form_type != 'create'
                && this.getForm.status == 'active'
                && this.getJobOrderFormKind != 'overtime'
            ) {
                result = true;
            }

            return result;
        },
        getConditionActionFinish() {
            let result = false;

            if (
                this.getForm.status_clone == 'active'
                && this.getForm.form_type != 'create'
                && this.getForm.status == 'active'
                && this.getJobOrderFormKind != 'overtime'
            ) {
                result = true;
            }

            return result;
        },
        getConditionActionDelete() {
            let result = false;

            // console.info(this.getForm.form_type);

            // hapus hanya ketika buat data, kalo edit hanya bisa pending
            if (this.getForm.form_type == 'create') {
                result = true;
            }

            // console.info(this.getForm.form_type);

            return result;
        },
        getConditionOvertime() {
            let result = false;

            // console.info(this.getJobOrderFormKind);
            // console.info(
            //     this.getForm.status
            //     , this.getJobOrderFormKind
            // );

            // && this.getJobOrderStatus == 'overtime'
            // this.getJobOrderStatus == 'active'
            // if (this.getForm.status == 'overtime') {
            //     result = true;
            // }
            if (
                this.getForm.status == 'active'
                && this.getJobOrderFormKind == 'overtime_finish'
            ) {
                result = true;
            }

            return result;
        },
        getConditionActionOvertimeFinish() {
            let result = false;

            // console.info(this.getJobOrderStatus);
            // console.info(this.getForm.status, this.getForm.status_clone);

            // if (
            //     this.getForm.status == 'overtime'
            //     && (
            //         this.getJobOrderStatus == 'overtime'
            //         || this.getJobOrderFormKind == null
            //     )
            // ) {
            //     result = true;
            // }

            if (
                this.getForm.status_clone == 'overtime'
                && this.getForm.status == 'overtime'
            ) {
                result = true;
            }

            return result;
        },
        getConditionActionNonActiveOvertime() {
            let result = false;

            // console.info(this.getJobOrderFormKind);

            // if (
            //     this.getForm.status == 'overtime'
            //     && this.getJobOrderFormKind == 'overtime'
            // ) {
            //     result = true;
            // }

            if (
                this.getForm.status_clone == 'active'
                && this.getJobOrderFormKind == 'overtime'
                && this.getForm.status == 'overtime'
            ) {
                result = true;
            }

            return result;
        },
        getConditionActionOvertimeAgain() {
            let result = false;

            if (
                this.getForm.status_clone == 'overtime'
                && this.getForm.status == 'active'
            ) {
                result = true;
            }

            return result;
        },
        getColumns() {
            const columns = this.columns.filter((item) => item.label != "");
            return columns;
        },
    },
};
