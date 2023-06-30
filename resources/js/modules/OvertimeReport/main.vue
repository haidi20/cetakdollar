<template>
  <div>
    <DatatableClientSide
      :data="getData"
      :columns="columns"
      :options="options"
      nameStore="jobOrder"
      nameLoading="data"
      :filter="true"
      bordered
    >
      <template v-slot:filter>
        <b-col cols>
          <b-form-group label="Tanggal" label-for="date" class="place_filter_table">
            <DatePicker
              id="date"
              v-model="params.date"
              format="YYYY-MM-DD"
              type="date"
              range
              placeholder="pilih tanggal"
            />
          </b-form-group>
          <b-button
            class="place_filter_table"
            variant="success"
            size="sm"
            @click="onFilter()"
            :disabled="getIsLoadingData || is_loading_export"
          >Kirim</b-button>
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
      <template v-slot:tbody="{ filteredData }">
        <b-tr v-for="(item, index) in filteredData" :key="index">
          <b-td style="text-align: center">
            <ButtonAction class="cursor-pointer" type="click">
              <template v-slot:list_detail_button>
                <a href="#" @click="onEdit(item)">Ubah</a>
                <!-- <a href="#" @click="onRead(item)">Lihat</a> -->
              </template>
            </ButtonAction>
          </b-td>
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

import ButtonAction from "../../components/ButtonAction";
import DatatableClientSide from "../../components/DatatableClient";

export default {
  data() {
    return {
      is_loading_export: false,
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
          label: "",
          field: "",
          width: "10px",
          class: "",
        },
        {
          label: "Nama Karyawan",
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
        {
          label: "Pekerjaan",
          field: "job_name",
          width: "100px",
          class: "",
        },
        {
          label: "Waktu Mulai",
          field: "datetime_start_readable",
          width: "100px",
          class: "",
        },
        {
          label: "Waktu Selesai",
          field: "datetime_end_readable",
          width: "100px",
          class: "",
        },
        {
          label: "Durasi",
          field: "duration_readable",
          width: "100px",
          class: "",
        },
        {
          label: "Catatan",
          field: "note_start",
          width: "100px",
          class: "",
        },
      ],
    };
  },
  components: {
    DatePicker,
    ButtonAction,
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
      return this.$store.state.jobOrder.data;
    },
    getIsLoadingData() {
      return this.$store.state.jobOrder.loading.table;
    },
    params() {
      return this.$store.state.jobOrder.params;
    },
  },
  methods: {
    onFilter() {
      this.$store.dispatch("jobOrder/fetchDataOvertimeReport");
    },
    onEdit(form) {
      this.$store.commit("jobOrder/INSERT_FORM", { form });
      this.$bvModal.show("overtime_report_form");
    },
    onRead(data) {
      //
    },
    async onExport() {
      const Swal = this.$swal;
      this.is_loading_export = true;

      //   console.info(moment(this.params.month).format("Y-MM"));

      await axios
        .get(`${this.getBaseUrl}/report/overtime/export`, {
          params: {
            user_id: this.getUserId,
            date_start: moment(this.params.date[0]).format("Y-MM-DD"),
            date_end: moment(this.params.date[1]).format("Y-MM-DD"),
          },
        })
        .then((responses) => {
          //   console.info(responses);
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
