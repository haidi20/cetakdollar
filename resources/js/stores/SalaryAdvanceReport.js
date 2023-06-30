import axios from "axios";
import moment from "moment";

import { numbersOnly, formatCurrency, formatNumberId } from "../utils";

const defaultForm = {
    id: null,
    type: "month",
    duration: null,
    loan_amount: null,
    loan_amount_readable: null,
    monthly_deduction: null,
    monthly_deduction_readable: null,
    payment_method: null,
    payment_status: null,

    approval_status: null,
    approval_agreement_level: null,
    approval_agreement_note: null,

    form_type: null,
}

const example = {
    namespaced: true,
    state: {
        base_url: null,
        data: {
            main: []
        },
        params: {
            date: [
                new Date(moment().startOf("month")),
                new Date(),
            ],
            status: "all",
        },
        form: { ...defaultForm },
        options: {
            types: [
                {
                    id: 'nominal',
                    name: 'Berdasarkan Jumlah Uang',
                },
                {
                    id: 'month',
                    name: 'Berdasarkan Jumlah Bulan',
                },
            ],
            payment_methods: [
                {
                    id: 'cash',
                    name: 'Uang Cash',
                },
                {
                    id: 'transfer',
                    name: 'Transfer',
                },
            ],
            statuses: [
                {
                    id: "all",
                    name: "Semua",
                },
                {
                    id: "review",
                    name: "Menunggu Persetujuan",
                },
                {
                    id: "settled",
                    name: "Lunas",
                },
                {
                    id: "unpaid",
                    name: "Belum Lunas",
                },
                {
                    id: "accept",
                    name: "Diterima",
                },
                {
                    id: "reject",
                    name: "Ditolak",
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
        INSERT_DATA_MAIN(state, payload) {
            state.data.main = payload.salary_advances;
        },
        INSERT_FORM(state, payload) {
            // console.info(payload);
            state.form = { ...state.form, ...payload.form };
        },
        INSERT_FORM_MONTHLY_DEDUCTION(state, payload) {
            if (payload.monthly_deduction != null) {
                const numericValue = numbersOnly(payload.monthly_deduction.toString());
                const readAble = formatCurrency(payload.monthly_deduction, ".");

                state.form.monthly_deduction = numericValue;
                state.form.monthly_deduction_readable = readAble;
            }
        },

        CLEAR_FORM(state, payload) {
            // console.info(defaultForm);
            state.form = { ...defaultForm };
        },
        UPDATE_LOADING_TABLE(state, payload) {
            state.loading.table = payload.value;
        },
    },
    actions: {
        fetchData: async (context, payload) => {
            context.commit("INSERT_DATA_MAIN", {
                salary_advances: [],
            });
            context.commit("UPDATE_LOADING_TABLE", { value: true });

            const params = {
                ...context.state.params,
                date_start: moment(context.state.params.date[0]).format("YYYY-MM-DD"),
                date_end: moment(context.state.params.date[1]).format("YYYY-MM-DD"),
                user_id: payload.user_id,
            }

            await axios
                .get(
                    `${context.state.base_url}/api/v1/report/salary-advance/fetch-data`, {
                    params: { ...params },
                })
                .then((responses) => {
                    console.info(responses);
                    const data = responses.data;

                    context.commit("INSERT_DATA_MAIN", {
                        salary_advances: data.salaryAdvances,
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
        getDeductionFormula: (state) => {
            let result = "";

            if (state.form.duration > 0 && state.form.type == 'month') {
                result = state.form.loan_amount / state.form.duration;
                result = "Rp. " + formatNumberId(Number(result.toFixed(2)), ".");
            }
            else if (state.form.type == 'nominal' && state.form.monthly_deduction_amount > 0) {
                // console.info(state.form.monthly_deduction_amount);
                result = state.form.loan_amount / state.form.monthly_deduction_amount;
                console.info(result);
                result = Math.ceil(result) + " Bulan";
            } else {
                // result = null;
            }

            return result;
        },
        getReadOnly: (state) => {
            let result = false;

            // console.info(state.form.approval_status);

            if (
                state.form.form_type == "detail"
                // || state.form.approval_status == "accept_onbehalf"
                || state.form.approval_agreement_level == 2
            ) {
                result = true;
            }

            return result;
        },
    },
}

export default example;
