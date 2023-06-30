<template>
  <div>
    <b-row>
      <b-col cols>
        <b-form-group label="Bulan" label-for="month" class="place_filter_table">
          <DatePicker
            id="month"
            v-model="params.month"
            format="YYYY-MM"
            type="month"
            placeholder="pilih bulan"
          />
        </b-form-group>
        <b-form-group label="Karyawan" label-for="employee_id" class="place_filter_table">
          <VueSelect
            id="employee_id"
            class="cursor-pointer"
            v-model="params.employee_id"
            placeholder="Pilih Karyawan"
            :options="getOptionEmployees"
            :reduce="(data) => data.id"
            label="name_and_position"
            searchable
            style="min-width: 250px"
          />
        </b-form-group>
        <b-button
          class="place_filter_table"
          variant="success"
          size="sm"
          @click="onFilter()"
          :disabled="getIsLoadingData"
        >Kirim</b-button>
        <b-button
          class="place_filter_table ml-4"
          variant="success"
          size="sm"
          @click="onPrint()"
          :disabled="getIsLoadingData"
        >print</b-button>
      </b-col>
    </b-row>
    <b-row class="mt-4">
      <b-col>
        <b-table-simple hover medium caption-top responsive :bordered="true">
          <b-thead>
            <b-tr>
              <b-th>Tanggal</b-th>
              <b-th>Hari</b-th>
              <b-th>Jam Masuk</b-th>
              <b-th>Jam Pulang</b-th>
              <b-th>Durasi Kerja</b-th>
              <b-th>Jam Istirahat</b-th>
              <b-th>Jam Selesai Istirahat</b-th>
              <b-th>Durasi Istirahat</b-th>
              <b-th>Jam Mulai Lembur</b-th>
              <b-th>Jam Selesai Lembur</b-th>
              <b-th>Durasi Lembur</b-th>
              <!-- <b-th>Jam Mulai Lembur Job Order</b-th>
              <b-th>Jam Selesai Lembur Job Order</b-th>
              <b-th>Durasi Lembur Job Order</b-th>-->
            </b-tr>
          </b-thead>
          <b-tbody>
            <template v-if="getIsLoadingData">
              <b-tr>
                <b-td colspan="9">Loading...</b-td>
              </b-tr>
            </template>
            <template v-else>
              <b-tr v-for="(data, index) in getData" :key="index">
                <b-td>{{data?.date}}</b-td>
                <b-td>{{data?.day}}</b-td>
                <b-td>{{data?.hour_start}}</b-td>
                <b-td>{{data?.hour_end}}</b-td>
                <b-td>{{data?.duration_work}}</b-td>
                <b-td>{{data?.hour_rest_start}}</b-td>
                <b-td>{{data?.hour_rest_end}}</b-td>
                <b-td>{{data?.duration_rest}}</b-td>
                <!-- <b-td>{{data?.hour_overtime_start}}</b-td>
                <b-td>{{data?.hour_overtime_end}}</b-td>
                <b-td>{{data?.duration_overtime}}</b-td>-->
                <b-td>{{data?.hour_overtime_job_order_start}}</b-td>
                <b-td>{{data?.hour_overtime_job_order_end}}</b-td>
                <b-td>{{data?.duration_overtime_job_order}}</b-td>
              </b-tr>
            </template>
          </b-tbody>
        </b-table-simple>
      </b-col>
    </b-row>
  </div>
</template>

<script>
import _ from "lodash";
import axios from "axios";
import moment from "moment";
import VueSelect from "vue-select";
import DatePicker from "vue2-datepicker";
import DatatableClient from "../../components/DatatableClient";

export default {
  data() {
    return {
      options: {
        perPage: 20,
        // perPageValues: [5, 10, 25, 50, 100],
        filterByColumn: true,
        texts: {
          filter: "",
          count: " ",
        },
      },
      columns: [
        {
          label: "Nama Karyawan",
          field: "employee_name",
          width: "100px",
          rowspan: 2,
          class: "",
        },
      ],
    };
  },
  components: {
    DatePicker,
    DatatableClient,
    VueSelect,
  },
  computed: {
    getBaseUrl() {
      return this.$store.state.base_url;
    },
    getUserId() {
      return this.$store.state.user?.id;
    },
    getData() {
      return this.$store.state.attendance.data.detail;
    },
    getDateRange() {
      return this.$store.state.attendance.date_range;
    },
    getIsLoadingData() {
      return this.$store.state.attendance.loading.detail;
    },
    getOptionEmployees() {
      return this.$store.state.employeeHasParent.data.options;
    },
    params() {
      return this.$store.state.attendance.params.detail;
    },
  },
  methods: {
    onFilter() {
      this.$store.dispatch("attendance/fetchData");
      this.$store.dispatch("attendance/fetchDetail");
    },
    onPrint() {
      //   console.info(`${this.getBaseUrl}/attendance/print`);
      const month = moment(this.params.month).format("Y-MM");
      const params = `month=${month}&employee_id=${this.params.employee_id}`;
      const linkPrint = `${this.getBaseUrl}/attendance/print?${params}`;
      window.open(`${linkPrint}`, "_blank");
    },
    setLabelDate(date) {
      return moment(date).format("DD");
    },
    setLabelNameDate(date) {
      return moment(date).format("dddd");
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
.item-hour {
  height: 18px;
}
.hour-reguler {
  color: green;
}
.hour-rest {
  color: blue;
}
</style>
