import axios from "axios";
import moment from "moment";

import DatatableClient from "../../../components/DatatableClient";
import ButtonAction from "../../../components/ButtonAction";

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
                    label: "Nama",
                    field: "name",
                    width: "150px",
                    class: "",
                },
                {
                    label: "Lokasi",
                    field: "location_name",
                    width: "200px",
                    class: "",
                },
                {
                    label: "Tanggal Selesai",
                    field: "date_end",
                    width: "200px",
                    class: "",
                },
                {
                    label: "Jangka Waktu Pekerjaan",
                    field: "duration",
                    width: "200px",
                    class: "",
                },
                {
                    label: "Job Order",
                    field: "job_order_total",
                    width: "200px",
                    class: "",
                },
            ],
            options: {
                perPage: 10,
                // perPageValues: [5, 10, 25, 50, 100],
            },
        };
    },
    components: {
        ButtonAction,
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
            return this.$store.state.project.data;
        },
        getIsLoadingData() {
            return this.$store.state.project.loading.table;
        },
        getParentType() {
            return this.$store.state.project.parent.type;
        },
        getForm() {
            return this.$store.state.project.form;
        },
        params() {
            return this.$store.state.project.params;
        },
    },
    methods: {
        onAction() {
            //
        },
        onCreate(item) {
            this.$store.commit("project/INSERT_FORM_FORM_TYPE", {
                form_type: "create",
                form_title: "Tambah Proyek",
            });
            this.$store.commit("project/CLEAR_FORM");
            this.$bvModal.show("project_form");
        },
        onDetail(item) {
            //   console.info(item);
            this.$store.dispatch("project/onAction", {
                form: item,
                form_type: "detail",
                form_title: "Informasi Lengkap Proyek",
            });

            this.$store.commit("jobOrder/INSERT_PARAM", { project_id: item.id });
            this.$store.dispatch("jobOrder/fetchData", { user_id: this.getUserId });

            this.$bvModal.show("project_form");
        },
        onEdit(item) {
            // console.info(item);
            const form = { ...item };
            this.$store.dispatch("project/onAction", {
                form: form,
                form_type: "edit",
                form_title: "Ubah Proyek",
            });

            this.$store.commit("jobOrder/INSERT_PARAM", { project_id: item.id });
            this.$store.dispatch("jobOrder/fetchData", { user_id: this.getUserId });

            // console.info(this.getForm);

            this.$bvModal.show("project_form");
        },
        onShowJobOrder() {
            this.$bvModal.show("job_order_modal");
        },
        onFilter() {
            this.$store.dispatch("project/fetchData");
        },
        async onExport() {
            const Swal = this.$swal;
            this.is_loading_export = true;

            await axios
                .get(`${this.getBaseUrl}/project/export`, {
                    params: {
                        ...this.params,
                        user_id: this.getUserId,
                        month: moment(this.params.month).format("Y-MM"),
                    },
                })
                .then((responses) => {
                    // console.info(responses);
                    this.is_loading_export = false;
                    const data = responses.data;

                    //   return false;

                    if (data.success) {
                        window.open(data.linkDownload, "_blank");
                    }
                })
                .catch((err) => {
                    this.is_loading_export = false;
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
        },
        async onDelete(data) {
            const Swal = this.$swal;
            await Swal.fire({
                title: "Perhatian!!!",
                html: `Anda yakin ingin hapus Proyek <h2><b>${data.name}</b> ?</h2>`,
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Ya hapus",
                cancelButtonText: "Batal",
            }).then(async (result) => {
                if (result.isConfirmed) {
                    await axios
                        .post(`${this.getBaseUrl}/api/v1/project/delete`, {
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

                                this.$store.dispatch("project/fetchData");
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
    },
};
