import axios from "axios";
import moment from "moment";

import { checkNull, listStatus, datetimeDuration } from '../utils';

/**
Status values
@typedef {
'active' | 'finish'
| 'pending'
| 'overtime' | 'overtime_finish'
| 'correction' | 'correction_finish'
| 'assessment' | 'assessment_finish'
} Status
*/

/**
 * Default form object.
 * @typedef {Object} DefaultForm
 * @property {?number} id - The ID of the form.
 * @property {?string} job_code - The job code.
 * @property {?number} project_id - The ID of the project.
 * @property {?string} category - The category of the job.
 * @property {?number} job_id - The ID of the job.
 * @property {?string} job_note - The note related to the job.
 * @property {?string} status - The status of the job.
 * @property {?string} status_last - The last status of the job.
 * @property {?string} status_finish - The finish status of the job.
 * @property {?string} image - The image related to the job.
 * @property {Date} date - The date of the form.
 * @property {string} hour - The hour in "HH:mm" format.
 * @property {?string} status_note - The note related to the status.
 * @property {?string} form_kind - The kind of form.
 * @property {?string} form_title - The title of the form.
 * @property {?string} hour_start - The start hour of the job.
 * @property {?string} datetime_start - The start date and time of the job.
 * @property {?string} datetime_end - The end date and time of the job.
 * @property {?string} datetime_end_readable - The readable format of the end date and time.
 * @property {?string} datetime_estimation_end - The estimated end date and time of the job.
 * @property {?string} datetime_estimation_end_readable - The readable format of the estimated end date and time.
 * @property {?number} estimation - The estimation value.
 * @property {?string} time_type - The type of time (minutes, hours, days).
 * @property {?string} note - The note related to the job.
 * @property {string} label_image - The label for the image field.
 */

/**
 * Default form object.
 * @type {DefaultForm}
 */
const defaultForm = {
    id: null,
    job_code: null,
    project_id: null,
    category: null,
    job_id: null,
    job_note: null,
    status: null,
    status_last: null,
    status_finish: null,
    image: null,
    image_bit: null,
    // start form action
    date: new Date(),
    hour: moment().format("HH:mm"),
    // hour: null,
    status_note: null,
    // end form action
    // form_kind: 'create',
    form_kind: null, // kebutuhan logika kirim data dari modal karyawan
    form_title: "Job Order v1.2",
    hour_start: moment().format("HH:mm"),
    date_start: null,
    datetime_start: null,
    hour_end: moment().format("HH:mm"),
    date_end: null,
    datetime_end: null,
    datetime_end_readable: null,
    datetime_estimation_end: null,
    datetime_estimation_end_readable: null,
    estimation: null,
    time_type: "hours",
    note: null,
    label_image: "Masukkan Gambar",
    is_disabled_btn_send: true,
    job_order_id: null, // kebutuhan penyesuaian gaji
    duration: null,
    duration_readable: null,
}

const JobOrder = {
    namespaced: true,
    state: {
        base_url: null,
        data: [],
        params: {
            month: new Date(),
            date: [
                new Date(moment().startOf("month")),
                new Date(),
            ],
            status: "all",
            created_by: "creator",
            project_id: null,
            search: null,
        },
        form: { ...defaultForm },
        is_active_form: false,
        options: {
            time_types: [
                {
                    id: "minutes",
                    name: "Menit",
                },
                {
                    id: "hours",
                    name: "Jam",
                },
                {
                    id: "days",
                    name: "Hari",
                },
            ],
            job_levels: [
                {
                    id: "easy",
                    name: "Mudah / Ringan",
                },
                {
                    id: "middle",
                    name: "Sedang / Menengah",
                },
                {
                    id: "hard",
                    name: "Sulit / Berat",
                },
            ],
            categories: [
                {
                    id: 'reguler',
                    name: "Reguler",
                },
                {
                    id: 'daily',
                    name: "Harian",
                },
                {
                    id: 'fixed_price',
                    name: "Borongan",
                },
            ],
            statuses: [
                {
                    id: "all",
                    name: "semua",
                },
                {
                    id: "active",
                    name: "aktif",
                },
                {
                    id: "pending",
                    name: "tunda",
                },
                {
                    id: "overtime",
                    name: "lembur",
                },
                {
                    id: "assessment",
                    name: "penilaian",
                },
                {
                    id: "finish",
                    name: "selesai",
                },
                // ini hanya untuk pengawas
                // {
                //     id: "done_assessment_qc",
                //     name: "sudah dinilai oleh QC",
                // },
            ],
            create_byes: [
                {
                    id: "creator",
                    name: "anda",
                },
                {
                    id: "another_foreman",
                    name: "pengawas lain",
                },
            ],
        },
        loading: {
            data: false,
        },
        user_id: null,
    },
    mutations: {
        INSERT_BASE_URL(state, payload) {
            state.base_url = payload.base_url;
        },
        INSERT_DATA(state, payload) {
            state.data = payload.data;
        },
        INSERT_DATA_SELECTED(state, payload) {
            let dataClone = [...state.data];
            dataClone = dataClone.map(item => ({ ...item, is_selected: false }));
            dataClone[payload.index] = {
                ...dataClone[payload.index],
                is_selected: true,
            }
            // console.info(dataClone, payload);

            state.data = [...dataClone];
        },
        INSERT_FORM(state, payload) {
            // console.info(payload);
            state.form = {
                ...state.form,
                ...payload.form,
            };

            if (checkNull(payload.form.datetime_start) != null) {
                state.form = {
                    ...state.form,
                    date_start: new Date(payload.form.datetime_start),
                    hour_start: moment(payload.form.datetime_start).format("HH:mm"),
                }
            }
            if (checkNull(payload.form.datetime_end) != null) {
                state.form = {
                    ...state.form,
                    date_end: new Date(payload.form.datetime_end),
                    hour_end: moment(payload.form.datetime_end).format("HH:mm"),
                    duration: datetimeDuration(payload.form.datetime_start, payload.form.datetime_end),
                    duration_readable: datetimeDuration(payload.form.datetime_start, payload.form.datetime_end, true),
                }
            }

            if (payload?.form_kind) {
                state.form.form_kind = payload?.form_kind;
            }

            // console.info(state.form);

            // if (payload.form_kind == 'edit') {
            //     state.form.note = payload.note;
            // }
        },
        INSERT_FORM_ID(state, payload) {
            state.form.id = payload.id;
        },
        INSERT_FORM_JOB_ID(state, payload) {
            state.form.job_id = payload.job_id;
        },
        INSERT_FORM_JOB_ORDER_ID(state, payload) {
            state.form.job_order_id = payload.job_order_id;
        },
        INSERT_FORM_JOB_CODE(state, payload) {
            state.form.job_code = payload.job_code;
        },
        INSERT_FORM_IMAGE(state, payload) {
            state.form.image = payload.file;
            state.form.image_bit = payload.bit;
        },
        INSERT_FORM_DATE_START(state, payload) {
            state.form.date_start = new Date(payload.date_start);

            const date = moment(payload.date_start).format('YYYY-MM-DD');
            state.form.datetime_start = moment(`${date} ${state.form.hour_start}`)
                .format('YYYY-MM-DD HH:mm:ss');
        },
        INSERT_FORM_HOUR_START(state, payload) {
            state.form.hour_start = payload.hour_start;

            const date = moment(state.form.date_start).format('YYYY-MM-DD');
            state.form.datetime_start = moment(`${date} ${payload.hour_start}`)
                .format('YYYY-MM-DD HH:mm:ss');
        },
        INSERT_FORM_DATE_END(state, payload) {
            state.form.date_end = new Date(payload.date_end);

            const date = moment(payload.date_end).format('YYYY-MM-DD');
            state.form.datetime_end = moment(`${date} ${state.form.hour_end}`)
                .format('YYYY-MM-DD HH:mm:ss');
        },
        INSERT_FORM_HOUR_END(state, payload) {
            state.form.hour_end = payload.hour_end;

            const date = moment(state.form.date_end).format('YYYY-MM-DD');
            state.form.datetime_end = moment(`${date} ${payload.hour_end}`)
                .format('YYYY-MM-DD HH:mm:ss');
        },
        INSERT_FORM_DURATION(state, payload) {
            state.form = {
                ...state.form,
                duration: datetimeDuration(state.form.datetime_start, state.form.datetime_end),
                duration_readable: datetimeDuration(state.form.datetime_start, state.form.datetime_end, true),
            }

            console.info(state.form.datetime_start);
        },
        INSERT_FORM_ESTIMATION(state, payload) {
            state.form.estimation = payload.estimation;
        },
        INSERT_FORM_TIME_TYPE(state, payload) {
            state.form.time_type = payload.time_type;
        },
        INSERT_FORM_DATETIME_ESTIMATION_END(state, payload) {
            // console.info(state.form);
            // console.info(checkNull(state.form.estimation));
            if (
                checkNull(state.form.hour_start) != null
                && checkNull(state.form.estimation) != null
            ) {
                const hourStart = state.form.hour_start;
                const momentHourStart = moment().set({
                    hour: parseInt(hourStart.split(":")[0]),
                    minute: parseInt(hourStart.split(":")[1])
                });
                const getDateEstimation = momentHourStart
                    .add(
                        state.form.estimation,
                        state.form.time_type
                    );

                let addFormat = "";
                if (state.form.time_type != "days") {
                    addFormat = " HH:mm";
                }

                state.form.datetime_estimation_end = getDateEstimation.format("YYYY-MM-DD HH:mm");
                state.form.datetime_estimation_end_readable = getDateEstimation
                    // .locale("id")
                    .format(`dddd, D MMMM YYYY${addFormat}`);
            } else {
                state.form.datetime_estimation_end = null;
                state.form.datetime_estimation_end_readable = null;
            }
        },
        INSERT_FORM_KIND(state, payload) {
            state.form.form_title = payload.form_title;
            state.form.form_kind = payload.form_kind;

            if (payload.form_kind == "edit") {
                state.form.label_image = "Ganti Gambar";
            } else {
                state.form.label_image = "Masukkan Gambar";
            }
        },
        INSERT_FORM_STATUS(state, payload) {
            let getStatus = payload.status;

            if (listStatus[getStatus]) {
                state.form.status = listStatus[getStatus].status;
                state.form.status_last = listStatus[getStatus].status_last;
                state.form.status_finish = payload.status;
                // state.form.status_note = null;
            } else {
                state.form.status = getStatus;
                // state.form.status_note = null;
                state.form.status_last = null;
                state.form.status_finish = null;
            }
        },
        INSERT_PARAM(state, payload) {
            state.params = {
                ...state.params,
                ...payload,
            }
        },
        INSERT_PARAM_PROJECT_ID(state, payload) {
            state.params.project_id = payload.project_id;
        },
        INSERT_PARAM_CREATED_BY(state, payload) {
            state.params.created_by = payload.created_by;
        },
        INSERT_PARAM_MONTH(state, payload) {
            state.params.month = new Date(payload.month);
        },
        INSERT_USER_ID(state, payload) {
            state.user_id = payload.user_id;
        },
        UPDATE_IS_ACTIVE_FORM(state, payload) {
            state.is_active_form = payload.value;
        },
        UPDATE_IS_DISABLED_BTN_SEND(state, payload) {
            state.form.is_disabled_btn_send = payload.value;
        },
        UPDATE_DATA_IS_SELECTED_FALSE(state, payload) {
            let dataClone = [...state.data];
            dataClone = dataClone.map(item => ({ ...item, is_selected: false }));

            state.data = [...dataClone];
        },
        UPDATE_DATA_IS_SELECTED_TRUE(state, payload) {
            let dataClone = [...state.data];
            dataClone = dataClone.map(item => ({ ...item, is_selected: payload.id == item.id ? true : false }));

            state.data = [...dataClone];
        },
        UPDATE_LOADING_DATA(state, payload) {
            state.loading.data = payload.value;
        },
        CLEAR_FORM(state, payload) {
            state.form = {
                ...defaultForm,
                // is_active_form: true,
            };
        },
        CLEAR_FORM_ACTION(state, payload) {
            state.form = {
                ...state.form,
                // date_end: null,
                hour: moment().format("HH:mm"),
                status_note: null,
            };
        },
    },
    actions: {
        fetchData: async (context, payload) => {

            if (payload?.user_id) {
                context.commit("INSERT_USER_ID", { user_id: payload.user_id });
            }

            if (payload?.project_id) {
                context.commit("INSERT_PARAM_PROJECT_ID", { project_id: payload.project_id });
            }

            context.commit("UPDATE_LOADING_DATA", { value: true });

            const params = {
                ...context.state.params,
                month: moment(context.state.params.month).format("Y-MM"),
                user_id: context.state.user_id,
                project_id: context.state.params.project_id,
            }

            await axios
                .get(
                    `${context.state.base_url}/api/v1/job-order/fetch-data`, {
                    params: { ...params },
                })
                .then((responses) => {
                    // console.info(responses);
                    const data = responses.data;

                    context.commit("INSERT_DATA", {
                        data: data.jobOrders,
                    });
                    context.commit("UPDATE_LOADING_DATA", { value: false });
                })
                .catch((err) => {
                    context.commit("UPDATE_LOADING_DATA", { value: false });
                    console.info(err);
                });
        },
        fetchDataFinish: async (context, payload) => {

            context.commit("UPDATE_LOADING_DATA", { value: true });

            const params = {
                // ...context.state.params,
                month: moment(context.state.params.month).format("Y-MM"),
                // user_id: context.state.user_id,
            }

            await axios
                .get(
                    `${context.state.base_url}/api/v1/job-order/fetch-data-finish`, {
                    params: { ...params },
                })
                .then((responses) => {
                    // console.info(responses);
                    const data = responses.data;
                    const jobOrders = data.jobOrders.map(item => {
                        if (
                            checkNull(context.state.form.id) != null
                            && item.id == context.state.form.id
                        ) {
                            return {
                                ...item,
                                is_selected: true,
                            }
                        } else {
                            return {
                                ...item,
                                is_selected: false,
                            }
                        }
                    });

                    context.commit("INSERT_DATA", {
                        data: jobOrders,
                    });
                    context.commit("UPDATE_LOADING_DATA", { value: false });
                })
                .catch((err) => {
                    context.commit("UPDATE_LOADING_DATA", { value: false });
                    console.info(err);
                });
        },
        fetchDataReport: async (context, payload) => {
            context.commit("UPDATE_LOADING_DATA", { value: true });

            const params = {
                ...context.state.params,
                date_start: moment(context.state.params.date[0]).format("Y-MM-DD"),
                date_end: moment(context.state.params.date[1]).format("Y-MM-DD"),
            }

            await axios
                .get(
                    `${context.state.base_url}/api/v1/report/job-order/fetch-data`, {
                    params: { ...params },
                })
                .then((responses) => {
                    // console.info(responses);
                    const data = responses.data;

                    context.commit("INSERT_DATA", {
                        data: data.jobOrders,
                    });
                    context.commit("UPDATE_LOADING_DATA", { value: false });
                })
                .catch((err) => {
                    context.commit("UPDATE_LOADING_DATA", { value: false });
                    console.info(err);
                });
        },
        fetchDataOvertimeReport: async (context, payload) => {
            context.commit("UPDATE_LOADING_DATA", { value: true });

            const params = {
                ...context.state.params,
                date_start: moment(context.state.params.date[0]).format("Y-MM-DD"),
                date_end: moment(context.state.params.date[1]).format("Y-MM-DD"),
                user_id: context.state.user_id,
            }

            await axios
                .get(
                    `${context.state.base_url}/api/v1/report/overtime/fetch-data`, {
                    params: { ...params },
                })
                .then((responses) => {
                    // console.info(responses);
                    const data = responses.data;

                    context.commit("INSERT_DATA", {
                        data: data.overtimes,
                    });
                    context.commit("UPDATE_LOADING_DATA", { value: false });
                })
                .catch((err) => {
                    context.commit("UPDATE_LOADING_DATA", { value: false });
                    console.info(err);
                });
        },
    },
    getters: {
        getReadOnly: (state) => {
            let result = false;

            // console.info(state.form.form_type);

            if (state.form.form_kind == "read") {
                result = true;
            }

            return result;
        },
    },
}

export default JobOrder;
