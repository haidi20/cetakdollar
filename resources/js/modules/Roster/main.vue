<template>
  <div>
    <DatatableClient
      :data="getData"
      :columns="columns"
      :options="options"
      nameStore="roster"
      nameLoading="table"
      bordered
    >
      <template v-slot:thead>
        <b-th
          v-for="(item, index) in getDateRange"
          v-bind:key="`thead-${index}`"
          style="width: 30px"
        >{{ setLabelDate(item) }}</b-th>
      </template>
      <template v-slot:theadSecond>
        <b-th
          v-for="(item, index) in getDateRange"
          v-bind:key="`thead-${index}`"
          style="text-align-last: center"
        >{{ setLabelNameDate(item) }}</b-th>
      </template>
      <template v-slot:tbody="{ filteredData, currentPage }">
        <b-tr v-for="(item, index) in filteredData" :key="index">
          <b-td nowrap>
            <b-button variant="info" size="sm" @click="onEdit(item)">Ubah</b-button>
          </b-td>
          <b-td nowrap>{{ item.employee_name }}</b-td>
          <b-td nowrap>{{ item.position_name }}</b-td>
          <b-td
            class="cursor-pointer text-center"
            v-for="(date, subIndex) in getDateRange"
            :key="`date-${subIndex}`"
            @click="onRowClick(
                item,
                date,
                getNoTable(index, currentPage, options.perPage),
                item[date]?.value
            )"
            :style="setStyling(
                getNoTable(index, currentPage, options.perPage),
                date,
                item[date]?.color
            )"
          >{{ item[date]?.value }}</b-td>
        </b-tr>
      </template>
    </DatatableClient>
    <br />
    <Form />
    <FormChangeStatus />
  </div>
</template>

<script>
import _ from "lodash";
import moment from "moment";
import DatePicker from "vue2-datepicker";
import DatatableClient from "../../components/DatatableClient";
import Form from "./form";
import FormChangeStatus from "./formChangeStatus";

export default {
  data() {
    return {
      options: {
        perPage: 10,
        // perPageValues: [5, 10, 25, 50, 100],
        filterByColumn: true,
        texts: {
          filter: "",
          count: " ",
        },
      },
      columns: [
        {
          label: "",
          field: "actions",
          width: "10px",
          rowspan: 2,
          class: "",
        },
        {
          label: "Nama Karyawan",
          field: "employee_name",
          width: "100px",
          rowspan: 2,
          class: "",
        },
        {
          label: "Departemen",
          field: "position_name",
          width: "100px",
          rowspan: 2,
          class: "",
        },
      ],
    };
  },
  components: {
    Form,
    FormChangeStatus,
    DatePicker,
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
      return this.$store.state.roster.data.main;
    },
    getDateRange() {
      return this.$store.state.roster.date_range;
    },
    getIsLoadingFilter() {
      return this.$store.state.roster.loading.table;
    },
    getOptionPositions() {
      return this.$store.state.master.data.positions;
    },
    params() {
      return this.$store.state.roster.params;
    },
  },
  methods: {
    onEdit(data) {
      this.$store.commit("roster/INSERT_FORM", { data });
      this.$bvModal.show("roster_form");
    },
    onRowClick(item, date_selected, row, data_roster_status) {
      const form = { ...item[date_selected] };
      this.$store.commit("roster/INSERT_SELECTED_FORM", { form });
      this.$bvModal.show("roster_change_status_form");
    },
    getNoTable(index, currentPage, perPage) {
      return index + 1 + (currentPage - 1) * perPage;
    },
    setLabelDate(date) {
      return moment(date).format("DD");
    },
    setLabelNameDate(date) {
      return moment(date).format("dddd");
    },
    setStyling(index, date, color) {
      // console.info(index, date);
      let style = {};

      if (color != null) {
        style = {
          backgroundColor: color,
        };
      } else {
        // style = this.getBackgroundColor(index, date);
        style = {
          backgroundColor: "white",
        };
      }

      return style;
    },
    setDateReadAble(date) {
      return moment(date).format("dddd, DD MMMM YYYY");
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
