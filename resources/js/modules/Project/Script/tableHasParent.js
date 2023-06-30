import axios from "axios";
import moment from "moment";

import DatatableClient from "../../../components/DatatableClient";
import ButtonAction from "../../../components/ButtonAction";

export default {
    data() {
        return {
            is_loading_export: false,
            columns: [
                {
                    label: "",
                    class: "",
                    width: "10px",
                },
                {
                    label: "Nama",
                    field: "name",
                    width: "150px",
                    class: "",
                },
                {
                    label: "Perusahaan",
                    field: "company_name",
                    width: "200px",
                    class: "",
                },
                {
                    label: "Tanggal Selesai",
                    field: "date_end",
                    width: "200px",
                    class: "",
                },
                {
                    label: "Jangka Waktu Pekerjaan",
                    field: "duration",
                    width: "200px",
                    class: "",
                },
            ],
            options: {
                perPage: 5,
                // perPageValues: [5, 10, 25, 50, 100],
            },
        };
    },
    components: {
        ButtonAction,
        DatatableClient,
    },
    computed: {
        getBaseUrl() {
            return this.$store.state.base_url;
        },
        getUserId() {
            return this.$store.state.user?.id;
        },
        getData() {
            return this.$store.state.project.data;
        },
        getIsLoadingData() {
            return this.$store.state.project.loading.table;
        },
        getParentType() {
            return this.$store.state.project.parent.type;
        },
        params() {
            return this.$store.state.project.params;
        },
    },
    methods: {
        onChoose(id, index) {
            // console.info(item);
            this.$store.commit("project/INSERT_DATA_SELECTED", { index });
            this.$store.commit("employeeHasParent/INSERT_FORM_PROJECT_ID", { project_id: id });
        },
        onFilter() {
            this.$store.dispatch("project/fetchDataBaseJobOrderFinish");
        },
        getCan(permissionName) {
            const getPermission = this.$store.getters["getCan"](permissionName);

            return getPermission;
        },
    },
};
