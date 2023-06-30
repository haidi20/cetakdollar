<template>
  <div>
    <DatatableClient
      :data="getData"
      :columns="columns"
      :options="options"
      nameStore="attendance"
      nameLoading="main"
      :filter="true"
      bordered
    >
      <template v-slot:filter>
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
          <b-form-group label="Jabatan" label-for="position_id" class="place_filter_table">
            <VueSelect
              id="position_id"
              class="cursor-pointer"
              v-model="params.position_id"
              placeholder="Pilih Jabatan"
              :options="getOptionPositions"
              :reduce="(data) => data.id"
              label="name"
              searchable
              style="min-width: 180px"
            />
          </b-form-group>
          <b-button
            class="place_filter_table"
            variant="success"
            size="sm"
            @click="onFilter()"
            :disabled="getIsLoadingData || is_loading_export"
          >Kirim</b-button>
          <span v-if="getIsLoadingData">Loading...</span>
          <b-button
            class="place_filter_table ml-4"
            variant="success"
            size="sm"
            @click="onExport()"
            :disabled="is_loading_export || getIsLoadingData"
          >
            <i class="fas fa-file-excel"></i>
            Export
          </b-button>
          <span v-if="is_loading_export">Loading...</span>
        </b-col>
      </template>
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
      <template v-slot:tbody="{ filteredData }">
        <b-tr v-for="(item, index) in filteredData" :key="index">
          <b-td nowrap>{{ item.employee_name }}</b-td>
          <b-td nowrap>{{ item.position_name }}</b-td>
          <b-td
            class="cursor-pointer text-center"
            style="font-size: 12px"
            v-for="(date, subIndex) in getDateRange"
            :key="`date-${subIndex}`"
          >
            <!-- is_exists -->
            <template v-if="item[date]?.is_exists">
              <div class="item-hour hour-reguler">{{ item[date]?.hour_start }}</div>
              <div class="item-hour hour-rest">{{ item[date]?.hour_rest_start }}</div>
              <div class="item-hour hour-rest">{{ item[date]?.hour_rest_end }}</div>
              <div class="item-hour hour-reguler">{{ item[date]?.hour_end }}</div>
            </template>
          </b-td>
        </b-tr>
      </template>
    </DatatableClient>
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
      is_loading_export: false,
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
          label: "Nama Karyawan",
          field: "employee_name",
          width: "100px",
          rowspan: 2,
          class: "",
        },
        {
          label: "Jabatan",
          field: "position_name",
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
      return this.$store.state.attendance.data.main;
    },
    getDateRange() {
      return this.$store.state.attendance.date_range;
    },
    getIsLoadingData() {
      return this.$store.state.attendance.loading.main;
    },
    getOptionPositions() {
      return this.$store.state.master.data.positions;
    },
    params() {
      return this.$store.state.attendance.params.main;
    },
  },
  methods: {
    onFilter() {
      this.$store.dispatch("attendance/fetchData");
    },
    async onExport() {
      const Swal = this.$swal;
      this.is_loading_export = true;

      await axios
        .get(`${this.getBaseUrl}/attendance/export`, {
          params: {
            user_id: this.getUserId,
            month: moment(this.params.month).format("Y-MM"),
            position_id: this.params.position_id,
          },
        })
        .then((responses) => {
          console.info(responses);
          this.is_loading_export = false;
          const data = responses.data;

          if (data.success) {
            window.open(data.linkDownload, "_blank");
          }
        })
        .catch((err) => {
          this.is_loading_export = false;
          console.info(err);
          const Toast = Swal.mixin({
            toast: true,
            position: "top-end",
            showConfirmButton: false,
            timer: 4000,
            timerProgressBar: true,
            didOpen: (toast) => {
              toast.addEventListener("mouseenter", Swal.stopTimer);
              toast.addEventListener("mouseleave", Swal.resumeTimer);
            },
          });
          Toast.fire({
            icon: "error",
            title: err.response.data.message,
          });
        });
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
