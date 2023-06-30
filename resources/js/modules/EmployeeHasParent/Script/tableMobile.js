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
        getDataClone() {
            return this.$store.state.employeeHasParent.data.clone_selecteds;
        },
        getDataSearch() {
            return this.$store.state.employeeHasParent.data.filter_selecteds;
        },
        getIsMobile() {
            return this.$store.state.employeeHasParent.data.selecteds;
        },
        getForm() {
            return this.$store.state.employeeHasParent.form;
        },
        getFilteredData() {
            let data = this.$store.state.employeeHasParent.data.filter_selecteds;
            if (this.search) {
                data = data.filter((item) => {
                    let found = false;
                    for (const key in item) {
                        if (item.hasOwnProperty(key)) {
                            if (
                                typeof item[key] === 'string' &&
                                item[key]
                                    .toLowerCase()
                                    .includes(this.search.toLowerCase())
                            ) {
                                found = true;
                                break;
                            }
                        }
                    }
                    return found;
                });
            }
            return data;
        },
        params() {
            return this.$store.state.employeeHasParent.params;
        },
        search() {
            return this.$store.state.employeeHasParent.params.search;
        },
    },
    watch: {
        getData(value) {
            // this.getFilteredData();
        },
    },
    methods: {
        onSearch() {
            // console.info(this.search);
            if (this.getData.length > 0) {
                let searchTerm = this.search.toLowerCase();
                let filteredData = this.getData.filter(item => {
                    for (let key in item) {
                        if (item[key] && item[key].toString().toLowerCase().includes(searchTerm)) {
                            return true;
                        }
                    }
                    return false;
                });

                // Use the filteredData as needed
                // console.log('Filtered data:', filteredData);
            }
        },
        onDelete() {
            this.$store.commit("employeeHasParent/DELETE_DATA_SELECTED");
            this.$bvModal.hide("action_list_employee");
        },
        onDeleteAll() {
            this.$store.commit("employeeHasParent/CLEAR_DATA_SELECTED");
        },
        onOpenAction(item, index) {
            // console.info(index);

            if (this.getUserId == item.created_by && this.getJobOrderFormKind != 'read') {
                this.$store.commit("employeeHasParent/INSERT_FORM", { form: { ...item, data_index: index } });
                this.$bvModal.show("action_list_employee");
            }
        },
        onAction(form_type, form_title) {
            this.$store.commit("employeeHasParent/INSERT_FORM_FORM_TYPE", { form_type });
            this.$store.commit("employeeHasParent/UPDATE_DATA_SELECTED_STATUS", { form_type });

            // console.info(form_type);
            let isDisabledBtnSave = this.getComparetionData();

            // console.info(isDisabledBtnSave);
            this.$store.commit("employeeHasParent/UPDATE_IS_DISABLED_BTN_SAVE", { value: isDisabledBtnSave });

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
                this.getForm.form_type != 'create'
                && this.getForm.status_data != 'new'
                //&& this.getForm.status_clone == 'active' // ketika coba aktif kembali tidak muncul
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

            // hapus hanya ketika buat data, kalo edit hanya bisa pending
            if (
                this.getForm.status_data == 'new'
            ) {
                result = true;
            }

            // console.info(this.getForm.form_type);

            return result;
        },
        getConditionOvertime() {
            let result = false;

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
        getComparetionData() {
            let result = true;

            this.getData.forEach((item, index) => {
                // console.info(item.status, this.getDataClone[index].status);
                if (item.status !== this.getDataClone[index].status) {
                    result = false;

                }
            });

            return result;
        },
        getConditionAddInformation(item) {
            let result = false;

            // console.info(item);

            if (
                item?.job_order_id
                // && item?.status_data == 'new'
                // && this.getJobOrderFormKind != null
                && item?.is_add_information
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
