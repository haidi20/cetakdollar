import axios from "axios";
import moment from "moment";

const defaultForm = {

}

const Attendance = {
    namespaced: true,
    state: {
        base_url: null,
        data: {
            main: [],
            detail: [],
        },
        params: {
            main: {
                position_id: 'all',
                month: new Date(),
            },
            detail: {
                employee_id: null,
                month: new Date(),
            },
        },
        form: { ...defaultForm },
        options: {
            //
        },
        loading: {
            main: false,
            detail: false,
        },
        date_range: [],
    },
    mutations: {
        INSERT_BASE_URL(state, payload) {
            state.base_url = payload.base_url;
        },
        INSERT_DATA_MAIN(state, payload) {
            state.data.main = payload.data;
        },
        INSERT_DATA_DETAIL(state, payload) {
            state.data.detail = payload.data;
        },
        INSERT_DATE_RANGE(state, payload) {
            state.date_range = payload.date_range;
        },
        UPDATE_LOADING_MAIN(state, payload) {
            state.loading.main = payload.value;
        },
        UPDATE_LOADING_DETAIL(state, payload) {
            state.loading.detail = payload.value;
        },
    },
    actions: {
        fetchData: async (context, payload) => {
            context.commit("UPDATE_LOADING_MAIN", { value: true });

            const params = {
                ...context.state.params.main,
                month: moment(context.state.params.main.month).format("Y-MM"),
            }

            await axios
                .get(
                    `${context.state.base_url}/api/v1/attendance/fetch-data-main`, {
                    params: { ...params },
                }
                )
                .then((responses) => {
                    // console.info(responses);
                    const data = responses.data;

                    context.commit("INSERT_DATA_MAIN", {
                        data: data.data,
                    });
                    context.commit("INSERT_DATE_RANGE", {
                        date_range: data.dateRange,
                    });
                    context.commit("UPDATE_LOADING_MAIN", { value: false });
                })
                .catch((err) => {
                    context.commit("UPDATE_LOADING_MAIN", { value: false });
                    console.info(err);
                });
        },
        fetchDetail: async (context, payload) => {
            context.commit("UPDATE_LOADING_DETAIL", { value: true });

            const params = {
                ...context.state.params.detail,
                month: moment(context.state.params.detail.month).format("Y-MM"),
            }

            await axios
                .get(
                    `${context.state.base_url}/api/v1/attendance/fetch-data-detail`, {
                    params: { ...params },
                }
                )
                .then((responses) => {
                    // console.info(responses);
                    const data = responses.data;

                    context.commit("INSERT_DATA_DETAIL", {
                        data: data.data,
                    });
                    context.commit("UPDATE_LOADING_DETAIL", { value: false });
                })
                .catch((err) => {
                    context.commit("UPDATE_LOADING_DETAIL", { value: false });
                    console.info(err);
                });
        },
    }
}

export default Attendance;
