import axios from "axios";
import moment from "moment";

import FormApproval from "../View/formApproval";
import ButtonAction from "../../../components/ButtonAction";
import DatatableClient from "../../../components/DatatableClient";

export default {
    data() {
        return {
            is_loading_export: false,
            columns: [
                {
                    label: "",
                    class: "",
                    width: "10px",
                },
                {
                    label: "Nama Karyawan",
                    field: "employee_name",
                    width: "150px",
                    class: "",
                },
                {
                    label: "Jabatan",
                    field: "position_name",
                    width: "150px",
                    class: "",
                },
                {
                    label: "Mengajukan",
                    field: "creator_name",
                    width: "150px",
                    class: "",
                },
                {
                    label: "Nominal",
                    field: "loan_amount_readable",
                    width: "150px",
                    class: "",
                },
                {
                    label: "Alasan",
                    field: "reason",
                    width: "150px",
                    class: "",
                },
                {
                    label: "Sisa Pinjaman Sebelumnya",
                    field: "remaining_debt_readable",
                    width: "150px",
                    class: "",
                },
                {
                    label: "Selesai di Bulan",
                    field: "month_loan_complite_readable",
                    width: "150px",
                    class: "",
                },
                {
                    label: "Status",
                    field: "approval_label",
                    width: "150px",
                    class: "",
                },
                {
                    label: "Keterangan",
                    field: "approval_description",
                    width: "150px",
                    class: "",
                },
            ],
            options: {
                perPage: 5,
                // perPageValues: [5, 10, 25, 50, 100],
            },
        };
    },
    components: {
        ButtonAction,
        FormApproval,
        DatatableClient,
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
        getData() {
            return this.$store.state.salaryAdvanceReport.data.main;
        },
        getIsLoadingData() {
            return this.$store.state.salaryAdvanceReport.loading.table;
        },
        params() {
            return this.$store.state.salaryAdvanceReport.params;
        },
    },
    watch: {
        getBaseUrl(value, oldValue) {
            if (value != null) {
                this.$store.dispatch("salaryAdvanceReport/fetchData", {
                    user_id: this.getUserId,
                });
            }
        },
    },
    methods: {
        onApprove(item, status) {
            let note = null;

            // if (item.approval_status == status || item.approval_status == 'review') {
            //     note = item.note; || status != "reject"
            // }
            // if (item.approval_status == status || status == 'accept_onbehalf') {
            //     note = item.note;
            // }
            note = item.approval_agreement_note;

            // console.info(item.approval_status, status);
            this.$store.commit("salaryAdvanceReport/INSERT_FORM", {
                form: { ...item, approval_status: status, note: note },
            });
            this.$bvModal.show("salary_advance_approval_form");
        },
        async onDelete(data) {
            const Swal = this.$swal;
            await Swal.fire({
                title: "Perhatian!!!",
                html: `Anda yakin ingin hapus Kasbon <h2><b>${data.employee_name}</b> senilai ${data.loan_amount_readable} ?</h2>`,
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Ya hapus",
                cancelButtonText: "Batal",
            }).then(async (result) => {
                if (result.isConfirmed) {
                    await axios
                        .post(`${this.getBaseUrl}/api/v1/salary-advance/delete`, {
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

                                this.$store.dispatch("salaryAdvanceReport/fetchData", {
                                    user_id: this.getUserId,
                                });
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
        getCan(permissionName) {
            const getPermission = this.$store.getters["getCan"](permissionName);

            return getPermission;
        },
        getColumns() {
            const columns = this.columns.filter((item) => item.label != "");
            return columns;
        },
        getPermissionUsers(item) {
            let result = true;
            const approvalUsers = item.approval_user_id.map((value) => Number(value));
            // console.info(approvalUsers, Number(this.getUserId));

            if (!approvalUsers.includes(Number(this.getUserId))) {
                result = false;
            }

            return result;
        },
        getConditionApproval(item) {
            let result = false;

            // console.info(item);

            if (
                this.getCan("persetujuan laporan kasbon") &&
                this.getPermissionUsers(item) &&
                item.approval_status != "not yet"
            ) {
                result = true;
            }

            return result;
        },
        getConditionOnbehalf(item) {
            let result = false;

            // console.info(item.approval_agreement_level);

            if (
                item.approval_status == 'accept'
                && item.approval_agreement_level == 1) {
                result = true;
            }

            return result;
        },
        getConditionReject(item) {
            let result = false;

            if (item.approval_status != 'reject') {
                result = true;
            }
            // console.info(this.getUserGroupName);
            if (this.getUserGroupName.toLowerCase() == 'kasir') {
                result = false;
            }

            return result;
        },
    },
};
