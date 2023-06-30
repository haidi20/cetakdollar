import VueSelect from "vue-select";
import axios from "axios";
import moment from "moment";

import { imageToBase64 } from "../../../utils";

import EmployeeTableConfirmation from '../../EmployeeHasParent/View/tableConfirmation';

export default {
    data() {
        return {
            sourceImage: null,
            label_image: null,
            is_image: false,
            is_loading: false,
            title_form: "Konfirmasi Data",
        };
    },
    mounted() {
        // this.$bvModal.show("data_employee");
    },
    components: {
        VueSelect,
        EmployeeTableConfirmation,
    },
    computed: {
        getBaseUrl() {
            return this.$store.state.base_url;
        },
        getUserId() {
            return this.$store.state.user?.id;
        },
        getTitleForm() {
            return this.$store.state.jobOrder.form_title;
        },
        projectName() {
            return this.$store.state.project.data.find(item => item.id == this.form.project_id)?.name;
        },
        categoryName() {
            return this.$store.state.jobOrder.options.categories.find(item => item.id == this.form.category)?.name;
        },
        jobName() {
            const job = this.$store.state.master.data.jobs.find(item => item.id == this.form.job_id);
            let jobName = job?.name;

            if (job == null) {
                jobName = this.$store.state.jobOrder.form.job_another_name;
            }

            return jobName;
        },
        jobLevelName() {
            return this.$store.state.jobOrder.options.job_levels.find(item => item.id == this.form.job_level)?.name;
        },
        getOptionTimeTypes() {
            return this.$store.state.jobOrder.options.time_types;
        },
        getEmployeeSelecteds() {
            return this.$store.state.employeeHasParent.data.selecteds;
        },
        getLabelImage() {
            return this.$store.state.jobOrder.form.label_image;
        },
        getIsDisabledBtnSend() {
            return this.$store.state.jobOrder.form.is_disabled_btn_send;
        },
        form() {
            return this.$store.state.jobOrder.form;
        },
    },
    mounted() {
        const file = this.form.image;

        if (file) {
            const reader = new FileReader();
            reader.onload = () => {
                this.sourceImage = reader.result;
            };
            reader.readAsDataURL(file);
        }
    },
    methods: {
        onCloseModal() {
            this.$bvModal.hide("job_order_confirmation");
        },
        async onSend() {
            const Swal = this.$swal;

            let request = {
                ...this.form,
                employee_selecteds: [...this.getEmployeeSelecteds],
                user_id: this.getUserId,
            };

            if (this.form.image != null) {
                request.image = await imageToBase64(request.image);
            }

            // console.info(request);
            // return false;
            this.is_loading = true;

            await axios
                .post(`${this.getBaseUrl}/api/v1/job-order/store`, request)
                .then((responses) => {
                    // console.info(responses);
                    this.is_loading = false;
                    // return false;
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

                        this.$store.commit("jobOrder/INSERT_FORM_KIND", {
                            form_title: "Job Order",
                            form_kind: null,
                        });
                        this.$store.commit("jobOrder/UPDATE_IS_ACTIVE_FORM", {
                            value: false,
                        });
                        this.$store.dispatch("jobOrder/fetchData");
                        this.$store.commit("jobOrder/CLEAR_FORM");
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
            const readOnly = this.$store.getters["jobOrder/getReadOnly"];
            //   console.info(readOnly);

            return readOnly;
        },
    },
};
