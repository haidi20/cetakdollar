import axios from "axios";
import moment from "moment";

const defaultForm = {
    id: null,
}

const example = {
    namespaced: true,
    state: {
        base_url: null,
        data: [],
        params: {
            month: new Date(),
        },
        form: { ...defaultForm },
        options: {
            //
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
            state.data = payload.vacations;
        },
        INSERT_FORM(state, payload) {
            state.form = { ...state.form, ...payload.form };
        },
        UPDATE_LOADING_TABLE(state, payload) {
            state.loading.table = payload.value;
        },
        CLEAR_FORM(state, payload) {
            state.form = { ...defaultForm };
        },
    },
    actions: {
        fetchData: async (context, payload) => {
            context.commit("INSERT_DATA", {
                vacations: [],
            });
            context.commit("UPDATE_LOADING_TABLE", { value: true });

            const params = {
                ...context.state.params,
                month: moment(context.state.params.month).format("Y-MM"),
            }

            await axios
                .get(
                    `${context.state.base_url}/api/v1/vacation/fetch-data`, {
                    params: { ...params },
                })
                .then((responses) => {
                    console.info(responses);
                    const data = responses.data;

                    context.commit("INSERT_DATA", {
                        vacations: data.vacations,
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
        //
    },
}

export default example;
