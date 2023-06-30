import axios from "axios";
import moment from "moment";

const defaultForm = {
    id: null,
}

const example = {
    namespaced: true,
    state: {
        base_url: null,
        data: {
            jobs: [],
            barges: [],
            positions: [],
            companies: [],
            locations: [],
        },
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
        INSERT_DATA_JOB(state, payload) {
            state.data.jobs = payload.jobs;
        },
        INSERT_DATA_POSITION(state, payload) {
            state.data.positions = payload.positions;
        },
        INSERT_DATA_BARGE(state, payload) {
            state.data.barges = payload.barges;
        },
        INSERT_DATA_COMPANY(state, payload) {
            state.data.companies = payload.companies;
        },
        INSERT_DATA_LOCATION(state, payload) {
            state.data.locations = payload.locations;
        },
        INSERT_FORM(state, payload) {
            state.form = { ...state.form, ...payload.form };
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
        fetchPosition: async (context, payload) => {
            await axios
                .get(
                    `${context.state.base_url}/api/v1/position/fetch-data`, {
                    params: {},
                }
                )
                .then((responses) => {
                    // console.info(responses);
                    let data = responses.data;

                    data.positions = [
                        { id: "all", name: "Semua" },
                        ...data.positions,
                    ];

                    context.commit("INSERT_DATA_POSITION", {
                        positions: data.positions,
                    });
                })
                .catch((err) => {
                    console.info(err);
                });
        },
        fetchBarge: async (context, payload) => {
            await axios
                .get(
                    `${context.state.base_url}/api/v1/barge/fetch-data`, {
                    params: {},
                }
                )
                .then((responses) => {
                    // console.info(responses);
                    let data = responses.data;

                    context.commit("INSERT_DATA_BARGE", {
                        barges: data.barges,
                    });
                })
                .catch((err) => {
                    console.info(err);
                });
        },
        fetchCompany: async (context, payload) => {
            await axios
                .get(
                    `${context.state.base_url}/api/v1/company/fetch-data`, {
                    params: {},
                }
                )
                .then((responses) => {
                    // console.info(responses);
                    let data = responses.data;

                    context.commit("INSERT_DATA_COMPANY", {
                        companies: data.companies,
                    });
                })
                .catch((err) => {
                    console.info(err);
                });
        },
        fetchLocation: async (context, payload) => {
            await axios
                .get(
                    `${context.state.base_url}/api/v1/location/fetch-data`, {
                    params: {},
                }
                )
                .then((responses) => {
                    // console.info(responses);
                    let data = responses.data;

                    context.commit("INSERT_DATA_LOCATION", {
                        locations: data.locations,
                    });
                })
                .catch((err) => {
                    console.info(err);
                });
        },
        fetchJob: async (context, payload) => {
            await axios
                .get(
                    `${context.state.base_url}/api/v1/job/fetch-data`, {
                    params: {},
                }
                )
                .then((responses) => {
                    // console.info(responses);
                    let data = responses.data;

                    context.commit("INSERT_DATA_JOB", {
                        jobs: data.jobs,
                    });
                })
                .catch((err) => {
                    console.info(err);
                });
        },

    }
}

export default example;
