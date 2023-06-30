import axios from "axios";
import moment from "moment";
import Form from "../View/form";
import FilterData from "../View/filter";

export default {
    data() {
        return {
            title: "",
        };
    },
    components: {
        FilterData,
        Form,
    },
    computed: {
        getBaseUrl() {
            return this.$store.state.base_url;
        },
        getUserId() {
            return this.$store.state.user?.id;
        },
        getData() {
            return this.$store.state.salaryAdvance.data;
        },
        getLoadingTable() {
            return this.$store.state.salaryAdvance.loading.table;
        },
        form() {
            return this.$store.state.salaryAdvance.form;
        },
    },
    watch: {
        getBaseUrl(value, oldValue) {
            if (value != null) {
                this.$store.dispatch("salaryAdvance/fetchData", {
                    user_id: this.getUserId,
                });
            }
        },
    },
    methods: {
        onOpenAction(data) {
            // console.info(data);
            this.$store.commit("salaryAdvance/INSERT_FORM", {
                form: data,
            });

            // pilihan action tidak muncul jika  sudah di setujui oleh HRD
            if (!this.getConditionAction()) {
                return false;
            }
            this.$refs.myBottomSheet.open();
        },
        onCreate() {
            //   console.info("create");
            this.$refs.myBottomSheet.close();
            this.$store.commit("salaryAdvance/INSERT_FORM_TITLE", {
                form_title: "Tambah Kasbon",
            });
            this.$store.commit("salaryAdvance/CLEAR_FORM");
            this.$bvModal.show("salary_advance_form");
        },
        onFilter() {
            this.$bvModal.show("salary_advance_filter");
        },
        onEdit(type, title) {
            //   console.info(type, title);

            if (this.getConditionAction()) {
                this.$refs.myBottomSheet.close();
                this.$store.commit("salaryAdvance/INSERT_FORM_TITLE", {
                    form_title: "Ubah Kasbon",
                });
                this.$bvModal.show("salary_advance_form");
            }
        },
        async onDelete() {
            if (!this.getConditionAction()) {
                return false;
            }
            this.$refs.myBottomSheet.close();
            //   console.info(this.form);
            const Swal = this.$swal;
            await Swal.fire({
                title: "Perhatian!!!",
                html: `Anda yakin ingin hapus data kasbon dari karyawan ${this.form.employee_name} ?</h2>`,
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
                            id: this.form.id,
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

                                this.$store.dispatch("salaryAdvance/fetchData", {
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
        getConditionAction() {
            let result = true;
            //   console.info(Number(this.form.created_by), Number(this.getUserId));
            //   return (
            //     Number(this.form.created_by) == Number(this.getUserId) &&
            //     this.form.approval_status != "accept"
            //   );

            if (Number(this.form.created_by) != Number(this.getUserId)) {
                result = false;
            }

            if (this.form.approval_status_first == "accept") {
                result = false;
            }

            return result;
        },
    },
};
