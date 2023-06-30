<template>
  <div>
    <b-row>
      <b-col cols>
        <b-form-group label="Tanggal" label-for="date" class="place_filter_table">
          <DatePicker
            id="date"
            v-model="params.date"
            format="YYYY-MM-DD"
            type="date"
            placeholder="pilih tanggal"
            range
          />
        </b-form-group>
        <b-form-group label="Status" label-for="status" class="place_filter_table">
          <VueSelect
            id="status"
            class="cursor-pointer"
            v-model="params.status"
            placeholder="pilih status"
            :options="getOptionStatuses"
            :reduce="(data) => data.id"
            label="name"
            searchable
            style="min-width: 200px"
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
          @click="onExport()"
          :disabled="is_loading_export"
        >
          <i class="fas fa-file-excel"></i>
          Export
        </b-button>
        <span v-if="is_loading_export">Loading...</span>
      </b-col>
    </b-row>
  </div>
</template>

<script>
import axios from "axios";
import moment from "moment";
import VueSelect from "vue-select";

export default {
  data() {
    return {
      is_loading_export: false,
    };
  },
  components: {
    VueSelect,
  },
  computed: {
    getBaseUrl() {
      return this.$store.state.base_url;
    },
    getUserId() {
      return this.$store.state.user?.id;
    },
    getIsLoadingData() {
      return this.$store.state.salaryAdvanceReport.loading.table;
    },
    getOptionStatuses() {
      return this.$store.state.salaryAdvanceReport.options.statuses;
    },
    params() {
      return this.$store.state.salaryAdvanceReport.params;
    },
  },
  methods: {
    onFilter() {
      this.$store.dispatch("salaryAdvanceReport/fetchData", {
        user_id: this.getUserId,
      });
    },
    async onExport() {
      const Swal = this.$swal;
      this.is_loading_export = true;

      await axios
        .get(`${this.getBaseUrl}/report/salary-advance/export`, {
          params: {
            ...this.params,
            user_id: this.getUserId,
            date_start: moment(this.params.date[0]).format("Y-MM-DD"),
            date_end: moment(this.params.date[1]).format("Y-MM-DD"),
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
  },
};
</script>
<style lang="scss" scoped>
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
