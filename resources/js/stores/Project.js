import axios from "axios";
import moment from "moment";

import { numbersOnly, formatCurrency, checkNull, dateDuration } from "../utils";

const defaultForm = {
    id: null,
    date_end: null,
    day_duration: null,
    // biaya
    price: null,
    price_readable: null,
    // DP
    down_payment: null,
    down_payment_readable: null,
    // sisa pembayaran
    remaining_payment: null,
    remaining_payment_readable: null,
    company_id: null,
    location_id: null,
    foreman_id: null,
    type: null,
    form_type: "create", // create, edit, detail
    form_title: "Tambah Proyek",
}

const Project = {
    namespaced: true,
    state: {
        base_url: null,
        data: [],
        params: {
            month: new Date(),
        },
        form: { ...defaultForm },
        // kebutuhan di form penyesuaian gaji bagian karyawan
        parent: {
            type: null,
        }, // create, edit, read
        options: {
            types: [
                {
                    id: "daily",
                    name: "Harian",
                },
                {
                    id: "contract",
                    name: "Borongan",
                },
            ],
            work_types: [
                {
                    id: "production",
                    name: "Produksi (pembuatan dari awal)",
                },
                {
                    id: "maintenance",
                    name: "Maintenance (Perbaikan)",
                },
            ],
        },
        loading: {
            table: false,
            position: false,
        },
    },
    mutations: {
        INSERT_BASE_URL(state, payload) {
            state.base_url = payload.base_url;
        },
        INSERT_DATA(state, payload) {
            state.data = [...payload.projects];
        },
        INSERT_DATA_SELECTED(state, payload) {
            let dataClone = [...state.data];
            dataClone = dataClone.map((item, index) => ({
                ...item,
                is_selected: index == payload.index ? true : false,
            }));
            // dataClone[payload.index] = {
            //     ...dataClone[payload.index],
            //     is_selected: true,
            // }
            // console.info(dataClone, payload);

            state.data = [...dataClone];
        },
        INSERT_PARENT(state, payload) {
            if (payload?.type) {
                state.parent.type = payload?.type;
            }
        },
        INSERT_FORM(state, payload) {
            let getCloneForm = state.form;

            // console.info(payload.form.date_end);

            getCloneForm = {
                ...getCloneForm,
                ...payload.form,
                contractors: [...payload.contractors],
                ordinary_seamans: [...payload.ordinary_seamans],
                date_end: checkNull(payload.form.date_end) != null ? new Date(payload.form.date_end) : null,
            };

            state.form = { ...getCloneForm };

            // console.info(payload.form.contractors, state.form.contractors);
        },
        INSERT_FORM_ID(state, payload) {
            state.form.id = payload.id;
        },
        INSERT_FORM_NEW_CONTRACTOR(state, payload) {
            // console.info(state.form);
            if (state.form.contractors) {
                state.form.contractors = [
                    ...state.form.contractors,
                    {
                        id: null,
                    },
                ]
            } else {
                state.form = {
                    ...state.form,
                    contractors: [{
                        id: null,
                    }]
                }
            }
        },
        INSERT_FORM_NEW_OS(state, payload) {
            if (state.form.ordinary_seamans) {
                state.form.ordinary_seamans = [
                    ...state.form.ordinary_seamans,
                    {
                        id: null,
                    },
                ]
            } else {
                state.form = {
                    ...state.form,
                    ordinary_seamans: [{
                        id: null,
                    }]
                }
            }
        },
        INSERT_FORM_PRICE(state, payload) {
            if (checkNull(payload.price) != null) {
                // console.info(typeof payload.amount);
                const numericValue = numbersOnly(payload.price.toString());
                let readAble = formatCurrency(payload.price, ".");

                // console.info(readAble.replace(/[^\d.:]/g, ''));

                state.form.price = numericValue;
                state.form.price_readable = readAble;

                // console.info(state);
            } else {
                state.form.price = null;
                state.form.price_readable = null;
            }
        },
        INSERT_FORM_DOWN_PAYMENT(state, payload) {
            if (checkNull(payload.down_payment) != null) {
                // console.info(typeof payload.amount);
                const numericValue = numbersOnly(payload.down_payment.toString());
                let readAble = formatCurrency(payload.down_payment, ".");
                // readAble = readAble.replace(/[^\d.:]/g, '');

                state.form.down_payment = numericValue;
                state.form.down_payment_readable = readAble;

                // console.info(state);
            } else {
                state.form.down_payment = null;
                state.form.down_payment_readable = null;
            }
        },
        INSERT_FORM_REMAINING_PAYMENT(state, payload) {
            // console.info(state.form.price, state.form.down_payment);
            // return false;
            if (checkNull(state.form.down_payment) != null && checkNull(state.form.price) != null) {
                const remaining_payment = state.form.price - state.form.down_payment;
                // console.info(remaining_payment);
                const numericValue = numbersOnly(remaining_payment.toString());
                let readAble = formatCurrency(remaining_payment, ".");
                if (Number(state.form.price) < Number(state.form.down_payment)) {
                    readAble = `- ${readAble}`;
                }

                // console.info(readAble.replace(/[^\d.:]/g, ''));

                state.form.remaining_payment = numericValue;
                state.form.remaining_payment_readable = readAble;

                // console.info(state);
            } else {
                state.form.remaining_payment = null;
                state.form.remaining_payment_readable = null;
            }
        },
        INSERT_FORM_DATE_END(state, payload) {
            // console.info(payload.date_end);
            state.form.date_end = checkNull(payload.date_end) != null ? payload.date_end : null;
        },
        INSERT_FORM_FORM_TYPE(state, payload) {
            state.form.form_type = payload.form_type;
            state.form.form_title = payload.form_title;
        },
        INSERT_FORM_DAY_DURATION(state, payload) {
            // console.info(state.form.date_end);
            if (
                checkNull(state.form.date_end) != null
                && moment(state.form.date_end).format("YYYY-mm-dd") != moment(new Date()).format("YYYY-mm-dd")
            ) {
                const dateNow = moment().format("YYYY-MM-DD");
                const dayDuration = dateDuration(dateNow, state.form.date_end);

                // console.info(day_duration);
                state.form.day_duration = `${dayDuration}`;
            } else {
                state.form.day_duration = null;
            }
        },
        INSERT_PARAM_MONTH(state, payload) {
            state.params.month = new Date(payload.month);
        },
        INSERT_OPTION_POSITION(state, payload) {
            state.options.positions = payload.positions;
        },
        UPDATE_LOADING_TABLE(state, payload) {
            state.loading.table = payload.value;
        },
        UPDATE_LOADING_POSITION(state, payload) {
            state.loading.position = payload.value;
        },
        UPDATE_DATA_IS_SELECTED_FALSE(state, payload) {
            let dataClone = [...state.data];
            dataClone = dataClone.map(item => ({ ...item, is_selected: false }));

            state.data = [...dataClone];
        },
        UPDATE_DATA_IS_SELECTED_TRUE(state, payload) {
            let dataClone = [...state.data];
            // console.info(dataClone.length);
            dataClone = dataClone.map(item => {
                // console.info(item.id, payload.id);
                if (item.id == payload.id) {
                    return {
                        ...item,
                        is_selected: true,
                    }
                } else {
                    return {
                        ...item,
                    }
                }
            });

            state.data = [...dataClone];
        },
        DELETE_FORM_CONTRACTOR(state, payload) {
            // const index = state.form.contractors.findIndex(obj => obj.id === payload.id);

            // Hapus objek dari array jika index ditemukan
            // if (index !== -1) {
            //     state.form.contractors.splice(index, 1);
            // }

            state.form.contractors.splice(payload.index, 1);
            // const indexToRemove = state.form.contractors.findIndex(item => item.id == payload.id);

            // // Remove the element from the array
            // if (indexToRemove !== -1) {
            //     state.form.contractors.splice(indexToRemove, 1);
            // }

            // console.info(state.data);

        },
        DELETE_FORM_OS(state, payload) {
            state.form.ordinary_seamans.splice(payload.index, 1);
        },
        CLEAR_FORM(state, payload) {
            // console.info(defaultForm);
            state.form = { ...defaultForm };
        },
    },
    actions: {
        fetchData: async (context, payload) => {
            context.commit("UPDATE_LOADING_TABLE", { value: true });

            const params = {
                ...context.state.params,
                month: moment(context.state.params.month).format("Y-MM"),
            }

            await axios
                .get(
                    `${context.state.base_url}/api/v1/project/fetch-data`, {
                    params: { ...params },
                })
                .then((responses) => {
                    // console.info(responses);
                    const data = responses.data;

                    context.commit("INSERT_DATA", {
                        projects: data.projects,
                    });
                    context.commit("UPDATE_LOADING_TABLE", { value: false });
                })
                .catch((err) => {
                    context.commit("UPDATE_LOADING_TABLE", { value: false });
                    console.info(err);
                });
        },
        fetchDataBaseDateEnd: async (context, payload) => {
            context.commit("UPDATE_LOADING_TABLE", { value: true });

            const params = {
                ...context.state.params,
                month: moment(context.state.params.month).format("Y-MM"),
                user_id: payload.user_id,
            }

            await axios
                .get(
                    `${context.state.base_url}/api/v1/project/fetch-data-base-date-end`, {
                    params: { ...params },
                })
                .then((responses) => {
                    // console.info(responses);
                    const data = responses.data;

                    context.commit("INSERT_DATA", {
                        projects: data.projects,
                    });
                    context.commit("UPDATE_LOADING_TABLE", { value: false });
                })
                .catch((err) => {
                    context.commit("UPDATE_LOADING_TABLE", { value: false });
                    console.info(err);
                });
        },
        fetchDataBaseJobOrderFinish: async (context, payload) => {
            context.commit("UPDATE_LOADING_TABLE", { value: true });

            const params = {
                ...context.state.params,
                month: moment(context.state.params.month).format("Y-MM"),
            }

            await axios
                .get(
                    `${context.state.base_url}/api/v1/project/fetch-data-base-joborder-finish`, {
                    params: { ...params },
                })
                .then((responses) => {
                    // console.info(responses);
                    const data = responses.data;
                    let projects = data.projects;

                    if (context.state.form.form_type != 'edit') {
                        projects = projects.map(item => {
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
                    }

                    context.commit("INSERT_DATA", {
                        projects: projects,
                    });
                    context.commit("UPDATE_LOADING_TABLE", { value: false });
                })
                .catch((err) => {
                    context.commit("UPDATE_LOADING_TABLE", { value: false });
                    console.info(err);
                });
        },
        /**
         * Perform an action asynchronously.
         *
         * @param {Object} context - The context object.
         * @param {Object} payload - The payload object.
         * @param {Object} payload.form - The form item.
         * @param {string} payload.form_type - The type of form.
         * @param {string} payload.form_title - The title of the form.
         * @returns {Promise} A promise that resolves after the action is performed.
         */
        onAction: async (context, payload) => {
            const getCloneForm = { ...payload.form };
            const getCloneFormContractors = [...payload.form.contractors];
            const getCloneFormOrdinarySeamans = [...payload.form.ordinary_seamans];

            // console.info(context.state.data);

            context.commit("INSERT_FORM", {
                form: { ...getCloneForm },
                contractors: [...getCloneFormContractors],
                ordinary_seamans: [...getCloneFormOrdinarySeamans],
            });
            context.commit("INSERT_FORM_FORM_TYPE", {
                form_type: payload.form_type,
                form_title: payload.form_title,
            });
            context.commit("INSERT_FORM_PRICE", {
                price: payload.form.price,
            });
            context.commit("INSERT_FORM_DOWN_PAYMENT", {
                down_payment: payload.form.down_payment,
            });
            context.commit("INSERT_FORM_REMAINING_PAYMENT");

            // if (payload.form.contractors.length == 0) {
            //     context.commit("INSERT_FORM_NEW_CONTRACTOR");
            // }

            // if (payload.form.ordinary_seamans.length == 0) {
            //     context.commit("INSERT_FORM_NEW_OS");
            // }
        },
    },
    getters: {
        getReadOnly: (state) => {
            let result = false;

            // console.info(state.form.form_type);

            if (state.form.form_type == "detail") {
                result = true;
            }

            return result;
        },
    },
}

export default Project;
