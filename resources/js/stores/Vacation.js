import axios from "axios";
import moment from "moment";

const defaultForm = {
    employee_id: null,
    date_start: null,
    date_end: null,
    note: null,
    created_by: null,
}

const Vacation = {
    namespaced: true,
    state: {
        base_url: null,
        data: [],
        params: {
            month: new Date(),
            search: null,
        },
        form: { ...defaultForm },
        form_title: "Tambah Cuti",
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
        INSERT_FORM_TITLE(state, payload) {
            state.form_title = payload.form_title;
        },
        INSERT_FORM(state, payload) {
            state.form = {
                ...payload.form,
                date_start: new Date(payload.form.date_start),
                date_end: new Date(payload.form.date_end),
            };
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
                }
                )
                .then((responses) => {
                    // console.info(responses);
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
    }
}

export default Vacation;



// {
//     id: 1,
//         employee_name: "muhammad adi",
//             position_name: "Welder",
//                 duration_readable: "7 Hari",
//                     date_start: "01 Mei 2023",
//                         date_end: "07 Mei 2023",
//                             created_by_name: "Sumardi",
//             }
