import DatatableClient from "../../../components/DatatableClient";

export default {
    data() {
        return {
            columns: [
                {
                    label: "Nama Karyawan",
                    field: "employee_name",
                    width: "250px",
                    class: "",
                },
                {
                    label: "Nama Departemen",
                    field: "position_name",
                    width: "150px",
                    class: "",
                },
                {
                    label: "",
                    class: "",
                    width: "0px",
                },
            ],
            options: {
                perPage: 5,
                // perPageValues: [5, 10, 25, 50, 100],
            },
        };
    },
    components: {
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
            return this.$store.state.employeeHasParent.data.selecteds;
        },
        getIsMobile() {
            return this.$store.state.employeeHasParent.data.selecteds;
        },
    },
    watch: {
        getBaseUrl(value) {
            //
        },
    },
    methods: {
        onDelete(index) {
            this.$store.commit("employeeHasParent/DELETE_DATA_SELECTED", { index });
        },
        onDeleteAll() {
            this.$store.commit("employeeHasParent/CLEAR_DATA_SELECTED");
        },
        getColumns() {
            const columns = this.columns.filter((item) => item.label != "");
            return columns;
        },
    },
};
