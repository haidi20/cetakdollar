<template>
  <div>
    <DatatableClientSide
      :data="getData"
      :columns="columns"
      :options="options"
      nameStore="employeeHasParent"
      nameLoading="table"
      :filter="false"
      bordered
    >
      <template v-slot:tbody="{ filteredData }">
        <b-tr v-for="(item, index) in filteredData" :key="index">
          <template v-for="(column, index) in getColumns()">
            <b-td :key="`col-${index}`">{{ item[column.field] }}</b-td>
          </template>
        </b-tr>
      </template>
    </DatatableClientSide>
  </div>
</template>

<script>
import _ from "lodash";
import axios from "axios";
import moment from "moment";
import DatePicker from "vue2-datepicker";

import DatatableClientSide from "../../../components/DatatableClient";

export default {
  data() {
    return {
      is_loading_export: false,
      options: {
        perPage: 5,
        // perPageValues: [5, 10, 25, 50, 100],
        filterByColumn: true,
        texts: {
          filter: "",
          count: " ",
        },
      },
      columns: [
        {
          label: "Nama",
          field: "employee_name",
          width: "100px",
          class: "",
        },
        {
          label: "Jabatan",
          field: "position_name",
          width: "100px",
          class: "",
        },
      ],
    };
  },
  components: {
    DatePicker,
    DatatableClientSide,
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
    getIsLoadingData() {
      return this.$store.state.employeeHasParent.loading.table;
    },
  },
  methods: {
    getColumns() {
      const columns = this.columns.filter((item) => item.label != "");
      return columns;
    },
    getCan(permissionName) {
      const getPermission = this.$store.getters["getCan"](permissionName);

      return getPermission;
    },
  },
};
</script>

<style lang="css">
.VueTables__search-field {
  display: none;
}

.place_filter_table {
  align-items: self-end;
  margin-bottom: 0;
  display: inline-block;
}

.table-wrapper {
  overflow-x: auto;
}
</style>
