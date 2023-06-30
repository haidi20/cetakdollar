import axios from "axios";
import moment from "moment";

import { checkNull } from "../utils";

const defaultForm = {
    id: null,
    position_id: null,
    employee_id: null,
    employee_name: null,
    work_schedule: null,
    day_off_one: null,
    day_off_two: null,
    month: new Date(),
    date_vacation: [
        new Date(),
        new Date(),
    ],
    roster_status_id: null,
    roster_status_initial: null,
}

const Roster = {
    namespaced: true,
    state: {
        base_url: null,
        data: {
            main: [],
            total: [],
        },
        params: {
            month: new Date(),
        },
        form: { ...defaultForm },
        options: {
            list_days: [
                { name: "Senin", id: "Monday" },
                { name: "Selasa", id: "Tuesday" },
                { name: "Rabu", id: "Wednesday" },
                { name: "Kamis", id: "Thursday" },
                { name: "Jumat", id: "Friday" },
                { name: "Sabtu", id: "Saturday" },
                { name: "Minggu", id: "Sunday" },
            ],
            positions: [],
        },
        loading: {
            table: false,
        },
        date_range: [],
        get_title_form: [],
    },
    mutations: {
        INSERT_BASE_URL(state, payload) {
            state.base_url = payload.base_url;
        },
        INSERT_DATA(state, payload) {
            state.data.main = payload.data;
        },
        INSERT_DATE_RANGE(state, payload) {
            state.date_range = payload.date_range;
        },
        INSERT_TOTAL(state, payload) {
            state.data.total = {
                ...state.data.total,
                [payload.position_id]: payload.data,
            }
        },
        INSERT_FORM(state, payload) {
            // const getForm = state.data.main.find(item => item.id == payload.id);
            const getForm = payload.data;
            // console.info(getForm);

            const dateVacation = getForm.date_vacation[0] != null ? [
                new Date(getForm.date_vacation[0]),
                new Date(getForm.date_vacation[1]),
            ] : [
                null, null
            ];

            state.get_title_form = "Ubah Roster - " + getForm.employee_name;
            state.form = {
                ...state.form,
                position_id: getForm.position_id,
                employee_id: getForm.employee_id,
                employee_name: getForm.employee_name,
                work_schedule: getForm.work_schedule,
                day_off_one: getForm.day_off_one,
                day_off_two: getForm.day_off_two,
                month: getForm.month != null ? new Date(moment(getForm.month)) : new Date(),
                date_vacation: dateVacation,
            };
        },
        INSERT_SELECTED_FORM(state, payload) {
            state.form = { ...state.form, ...payload.form };
        },
        INSERT_OPTION_POSITION(state, payload) {
            state.options.positions = payload.positions;
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
            context.commit("UPDATE_LOADING_TABLE", { value: true });
            context.commit("INSERT_DATE_RANGE", { date_range: [] });

            const params = {
                // ...context.state.params,
                month: moment(context.state.params.month).format("Y-MM"),
            }

            await axios
                .get(
                    `${context.state.base_url}/api/v1/roster/fetch-data`, {
                    params: { ...params },
                }
                )
                .then((responses) => {
                    // console.info(responses);
                    const data = responses.data;

                    context.commit("INSERT_DATA", {
                        data: data.data,
                    });
                    context.commit("INSERT_DATE_RANGE", {
                        date_range: data.dateRange,
                    });
                    context.commit("UPDATE_LOADING_TABLE", { value: false });
                })
                .catch((err) => {
                    context.commit("UPDATE_LOADING_TABLE", { value: false });
                    console.info(err);
                });
        },
        fetchTotal: async (context, payload) => {
            const params = {
                // ...context.state.params,
                date_filter: moment(context.state.params.date_filter).format("Y-MM"),
            }

            // console.info(payload);

            // const positions = context.state.options.positions;
            const positions = payload.positions;

            // console.info(positions);

            const promises = positions
                .map(async (item, index) => {
                    context.commit("INSERT_TOTAL", { position_id: item.id, data: [] });

                    return new Promise((resolve, reject) => {
                        axios
                            .get(`${context.state.base_url}/api/v1/roster/fetch-total`, {
                                params: {
                                    ...params,
                                    position_id: item.id,
                                },
                            })
                            .then((responses) => {
                                // console.info(responses);

                                const data = responses.data.data;

                                context.commit("INSERT_TOTAL", { position_id: item.id, data: data });

                                resolve(item);
                            }).then(roster_status => {

                            });
                    });
                })

            await Promise.all(promises)
                .then((result) => {
                    // console.info(context.state.data.total);
                });

        },
    }
}

export default Roster;
