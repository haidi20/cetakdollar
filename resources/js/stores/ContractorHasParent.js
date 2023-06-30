import axios from "axios";
import moment from "moment";

// STORE INI DIBUTUHKAN JIKA ADA KEBUTUHAN DATA KEPALA KONTRAKTOR LEBIH KOMPLEKS

const defaultForm = {
    id: null,
    name: null,
    address: null,
    no_hp: null,
    is_show_form: false,
    type_form: "create",
    contractors: [
        {
            id: null,
        },
    ],
}

const ContractorHasParent = {
    namespaced: true,
    state: {
        base_url: null,
        data: [
            {
                id: 1,
                name: "Pak Jamal",
                address: null,
                no_hp: null,
            }
        ],
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
            state.data = payload.contractors;
        },
        INSERT_FORM(state, payload) {
            // console.info(defaultForm);
            state.form = { ...payload.form };
        },
        INSERT_TYPE_FORM(state, payload) {
            state.form.type_form = payload.type_form;
        },
        UPDATE_IS_SHOW_FORM(state, payload) {
            // console.info(defaultForm);
            state.form.is_show_form = payload.value;
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
            context.commit("INSERT_DATA", {
                contractors: [],
            });
            context.commit("UPDATE_LOADING_TABLE", { value: true });

            const params = {
                ...context.state.params,
                month: moment(context.state.params.month).format("Y-MM"),
            }

            await axios
                .get(
                    `${context.state.base_url}/api/v1/contractor/fetch-data`, {
                    params: { ...params },
                }
                )
                .then((responses) => {
                    console.info(responses);
                    const data = responses.data;

                    context.commit("INSERT_DATA", {
                        contractors: data.contractors,
                    });
                    context.commit("UPDATE_LOADING_TABLE", { value: false });
                })
                .catch((err) => {
                    context.commit("UPDATE_LOADING_TABLE", { value: false });
                    console.info(err);
                });
        },

    }
}

export default ContractorHasParent;
