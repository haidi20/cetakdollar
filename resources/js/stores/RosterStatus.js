import axios from "axios";
import moment from "moment";

const defaultForm = {
    name: null,
    initial: null,
    note: null,
    color: "#FFFFFF",
}

const RosterStatus = {
    namespaced: true,
    state: {
        base_url: null,
        data: [],
        params: {
            date_filter: new Date(),
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
            state.data = payload.data;
        },
        INSERT_FORM(state, payload) {
            state.form = { ...payload.form };
        },
        CLEAR_FORM(state, payload) {
            // console.info(defaultForm);
            state.form = { ...defaultForm };
        },
    },
    actions: {
        fetchData: async (context, payload) => {
            await axios
                .get(
                    `${context.state.base_url}/api/v1/roster-status/fetch-data`, {
                    params: {
                        //
                    },
                }
                )
                .then((responses) => {
                    // console.info(responses);
                    const data = responses.data;

                    context.commit("INSERT_DATA", {
                        data: data.data,
                    });
                })
                .catch((err) => {
                    console.info(err);
                });
        },
    }
}

export default RosterStatus;
