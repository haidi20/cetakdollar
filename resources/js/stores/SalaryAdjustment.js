import axios from "axios";
import moment from "moment";

import { numbersOnly, formatCurrency } from "../utils";

const defaultForm = {
    id: null,
    name: null,
    type_time: "forever",
    type_amount: "nominal",
    amount: null,
    amount_readable: null,
    month_start: new Date(),
    month_end: new Date(),
    type_adjustment: "addition",
    note: null,
    is_month_end: false,
    is_thr: false,
    form_type: "create",
}

const SalaryAdjustment = {
    namespaced: true,
    state: {
        base_url: null,
        data: [],
        params: {
            month: [
                new Date(),
                new Date(),
            ],
        },
        form: { ...defaultForm },
        options: {
            type_times: [
                {
                    id: "forever",
                    name: "selamanya",
                },
                {
                    id: "base_time",
                    name: "berdasarkan bulan",
                }
            ],
            // jenis jumlah dalam bentuk persen atau angka
            type_amounts: [
                {
                    id: "nominal",
                    name: "jumlah uang",
                },
                {
                    id: "percent",
                    name: "persen dari gaji karyawan",
                },
            ],
            type_adjustments: [
                {
                    id: "deduction",
                    name: "pengurangan",
                },
                {
                    id: "addition",
                    name: "penambahan",
                },
            ],
        },
        loading: {
            table: false,
        },
    },
    mutations: {
        INSERT_BASE_URL(state, payload) {
            state.base_url = payload.base_url;
        },
        INSERT_DATA(state, payload) {
            state.data = payload.salaryAdjustments;
        },
        INSERT_FORM(state, payload) {
            state.form = {
                ...state.form,
                ...payload.form,
                is_thr: payload.form.is_thr == 1 ? true : false,
                form_type: payload?.form_type,
                month_start: payload.form.month_start != null ? new Date(payload.form.month_start) : new Date(),
                month_end: payload.form.month_end != null ? new Date(payload.form.month_end) : new Date(),
            };
        },
        INSERT_FORM_FORM_TYPE(state, payload) {
            state.form.form_type = payload.from_type;
        },
        INSERT_FORM_AMOUNT(state, payload) {
            if (payload.amount != null) {
                // console.info(typeof payload.amount);
                const numericValue = numbersOnly(payload.amount.toString());
                const readAble = formatCurrency(payload.amount, ".");

                // console.info(readAble.replace(/[^\d.:]/g, ''));

                state.form.amount = numericValue;
                state.form.amount_readable = readAble;

                // console.info(state);
            }
        },


        UPDATE_FORM_IS_DATE_END(state, payload) {
            state.form.is_month_end = payload.value;
        },
        UPDATE_LOADING_TABLE(state, payload) {
            state.loading.table = payload.value;
        },
        CLEAR_FORM(state, payload) {
            // console.info(defaultForm);
            state.form = { ...defaultForm };
        },
    },
    actions: {
        fetchData: async (context, payload) => {
            context.commit("INSERT_DATA", {
                salaryAdjustments: [],
            });
            context.commit("UPDATE_LOADING_TABLE", { value: true });

            const params = {
                ...context.state.params,
                month: moment(context.state.params.month).format("Y-MM"),
            }

            await axios
                .get(
                    `${context.state.base_url}/api/v1/salary-adjustment/fetch-data`, {
                    params: { ...params },
                })
                .then((responses) => {
                    console.info(responses);
                    const data = responses.data;

                    context.commit("INSERT_DATA", {
                        salaryAdjustments: data.salaryAdjustments,
                    });
                    context.commit("UPDATE_LOADING_TABLE", { value: false });
                })
                .catch((err) => {
                    context.commit("UPDATE_LOADING_TABLE", { value: false });
                    console.info(err);
                });
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

export default SalaryAdjustment;
