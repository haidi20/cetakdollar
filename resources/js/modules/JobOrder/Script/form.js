import VueSelect from "vue-select";
import axios from "axios";
import moment from "moment";

import FormConfirmation from "../View/formConfirmation";
import EmployeeHasParent from "../../EmployeeHasParent/view/employeeHasParent";
import { checkNull } from "../../../utils";

export default {
    data() {
        return {
            label_image: null,
            is_image: false,
            is_loading: false,
            is_show_another_job: false,
        };
    },
    mounted() {
        // this.$bvModal.show("data_employee");
    },
    components: {
        VueSelect,
        FormConfirmation,
        EmployeeHasParent,
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
        getOptionProjects() {
            return this.$store.state.project.data;
        },
        getOptionCategories() {
            return this.$store.state.jobOrder.options.categories;
        },
        getOptionJobs() {
            let jobs = this.$store.state.master.data.jobs;

            jobs = [
                ...jobs,
                {
                    id: 'another',
                    name: 'Lainnya'
                }
            ];

            return jobs;
        },
        getOptionJobLevels() {
            return this.$store.state.jobOrder.options.job_levels;
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
        job_id: {
            get() {
                let jobId = this.$store.state.jobOrder.form.job_id;

                if (checkNull(jobId) == null) {
                    jobId = 'another';
                }

                return jobId;
            },
            set(value) {
                this.$store.commit("jobOrder/INSERT_FORM_JOB_ID", {
                    job_id: value,
                });
            },
        },
        hour_start: {
            get() {
                return this.$store.state.jobOrder.form.hour_start;
            },
            set(value) {
                this.$store.commit("jobOrder/INSERT_FORM_HOUR_START", {
                    hour_start: value,
                });
            },
        },
        estimation: {
            get() {
                return this.$store.state.jobOrder.form.estimation;
            },
            set(value) {
                this.$store.commit("jobOrder/INSERT_FORM_ESTIMATION", {
                    estimation: value,
                });
            },
        },
        time_type: {
            get() {
                return this.$store.state.jobOrder.form.time_type;
            },
            set(value) {
                this.$store.commit("jobOrder/INSERT_FORM_TIME_TYPE", {
                    time_type: value,
                });
            },
        },
    },
    watch: {
        job_id(value, oldMessage) {
            if (value != null) {
                const findJob = this.getOptionJobs.find(item => item.id == value);

                // console.info(findJob);

                this.$store.commit("jobOrder/INSERT_FORM_JOB_CODE", {
                    job_code: findJob.code,
                });
            }
        },
        hour_start(value, oldMessage) {
            this.$store.commit("jobOrder/INSERT_FORM_DATETIME_ESTIMATION_END");
        },
        estimation(value, oldMessage) {
            this.$store.commit("jobOrder/INSERT_FORM_DATETIME_ESTIMATION_END");
        },
        time_type(value, oldMessage) {
            this.$store.commit("jobOrder/INSERT_FORM_DATETIME_ESTIMATION_END");
        },
    },
    methods: {
        onCloseModal() {
            this.$store.commit("jobOrder/INSERT_FORM_KIND", {
                form_title: "Job Order",
                form_kind: null,
            });
            this.$store.commit("jobOrder/UPDATE_IS_ACTIVE_FORM", {
                value: false,
            });
        },
        onInsertImage(event) {
            const reader = new FileReader();

            if (event.target.files[0]) {
                reader.readAsDataURL(event.target.files[0]);
                reader.onload = (readerEvent) => {
                    this.$store.commit("jobOrder/INSERT_FORM_IMAGE", {
                        file: event.target.files[0],
                        bit: readerEvent.target.result,
                    });
                };

                // this.$store.dispatch("onEvent", { nameForm: "business_photo" });
            }
        },
        onShowEmployee() {
            this.$bvModal.show("data_employee");
        },
        onConfirmation() {
            this.$bvModal.show("job_order_confirmation");
        },
        getReadOnly() {
            const readOnly = this.$store.getters["jobOrder/getReadOnly"];
            //   console.info(readOnly);

            return readOnly;
        },
    },
};
