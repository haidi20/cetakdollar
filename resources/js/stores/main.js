import Vue from 'vue';
import Vuex from 'vuex';
import axios from "axios";
import moment from "moment";

import Os from "./Os";
import Master from "./Master";
import Roster from "./Roster";
import Project from "./Project";
import Vacation from "./Vacation";
import JobOrder from "./JobOrder";
import EmployeeHasParent from "./EmployeeHasParent";
import Contractor from "./Contractor";
import Attendance from "./Attendance";
import OsHasParent from "./OsHasParent";
import RosterStatus from "./RosterStatus";
import SalaryAdvance from './SalaryAdvance';
import SalaryAdjustment from './SalaryAdjustment';
import SalaryAdvanceReport from './SalaryAdvanceReport';
import ContractorHasParent from "./ContractorHasParent";

Vue.use(Vuex);

const store = new Vuex.Store({
    modules: {
        os: Os,
        roster: Roster,
        master: Master,
        project: Project,
        vacation: Vacation,
        jobOrder: JobOrder,
        contractor: Contractor,
        attendance: Attendance,
        osHasParent: OsHasParent,
        rosterStatus: RosterStatus,
        salaryAdvance: SalaryAdvance,
        salaryAdjustment: SalaryAdjustment,
        employeeHasParent: EmployeeHasParent,
        salaryAdvanceReport: SalaryAdvanceReport,
        contractorHasParent: ContractorHasParent,
    },
    state: {
        user: {},
        permissions: [],
        base_url: null,
        name_menu: null,
        permission: {
            is_edit: true,
            is_delete: true,
        },
    },
    mutations: {
        INSERT_BASE_URL(state, payload) {
            state.base_url = payload.base_url;
        },
        INSERT_USER(state, payload) {
            state.user = payload.user;
        },
        INSERT_NAME_MENU(state, payload) {
            state.name_menu = payload.name_menu;
        },
        UPDATE_PERMISSION_IS_EDIT(state, payload) {
            state.permission.is_edit = payload.value;
        },
        UPDATE_PERMISSION_IS_DELETE(state, payload) {
            state.permission.is_delete = payload.value;
        },
        INSERT_PERMISSION(state, payload) {
            state.permissions = payload.permissions;
        },
    },
    actions: {
        fetchPermission: async (context, payload) => {
            context.commit("INSERT_PERMISSION", {
                permissions: [],
            });

            // console.info(context.state.user);

            await axios
                .get(
                    `${context.state.base_url}/api/v1/user/fetch-permission`, {
                    params: { user_id: context.state.user?.id },
                }
                )
                .then((responses) => {
                    // console.info(responses);
                    const data = responses.data;

                    context.commit("INSERT_PERMISSION", {
                        permissions: data.permissions,
                    });
                })
                .catch((err) => {
                    console.info(err);
                });
        },
        onNumberOnly: (context, payload) => {
            let evt = payload.evt;

            evt = evt ? evt : window.event;
            var charCode = evt.which ? evt.which : evt.keyCode;
            if (
                charCode > 31 &&
                (charCode < 48 || charCode > 57) &&
                charCode !== 46
            ) {
                evt.preventDefault();
            } else {
                return true;
            }
        },
    },
    getters: {
        getCan: (state) => (permissionName) => {
            let result = false;

            // console.info(state.form.form_type);
            const getPermission = state.permissions.some(item => item.name == permissionName);

            if (getPermission) {
                result = true;
            }

            return result;
        },
    }
})

export default store;
