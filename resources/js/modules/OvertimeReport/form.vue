<template>
  <div>
    <b-modal
      id="overtime_report_form"
      ref="overtime_report_form"
      :title="getFormTitle"
      size="md"
      class="modal-custom"
      hide-footer
    >
      <b-row>
        <b-col cols>
          <b-form-group label="Tanggal Awal Lembur" label-for="date_start">
            <DatePicker
              id="date_start"
              v-model="date_start"
              format="YYYY-MM-DD"
              type="date"
              placeholder="pilih tanggal"
              style="width: 100%"
              @change="onChangeDuration()"
            />
          </b-form-group>
        </b-col>
        <b-col cols>
          <b-form-group label="Jam Mulai" label-for="hour_start">
            <input
              type="time"
              class="form-control"
              v-model="hour_start"
              id="hour_start"
              name="hour_start"
              @change="onChangeDuration()"
            />
          </b-form-group>
        </b-col>
      </b-row>
      <b-row>
        <b-col cols>
          <b-form-group label="Tanggal Selesai Lembur" label-for="date_end">
            <DatePicker
              id="date_end"
              v-model="date_end"
              format="YYYY-MM-DD"
              type="date"
              placeholder="pilih tanggal"
              style="width: 100%"
              @change="onChangeDuration()"
            />
          </b-form-group>
        </b-col>
        <b-col cols>
          <b-form-group label="Jam Selesai" label-for="hour_end">
            <input
              type="time"
              class="form-control"
              v-model="hour_end"
              id="hour_end"
              name="hour_end"
              @change="onChangeDuration()"
            />
          </b-form-group>
        </b-col>
      </b-row>
      <b-row>
        <b-col cols="6">
          <b-form-group label="Durasi" label-for="duration_readable">
            <input
              type="text"
              class="form-control"
              v-model="getDurationReadable"
              id="duration_readable"
              name="duration_readable"
              disabled
            />
          </b-form-group>
        </b-col>
      </b-row>
      <br />
      <b-row class="float-end">
        <b-col>
          <b-button variant="info" @click="onCloseModal()">Tutup</b-button>
          <b-button variant="success" @click="onSend()" class="ml-8" :disabled="is_loading">Simpan</b-button>
          <span v-if="is_loading">Loading...</span>
        </b-col>
      </b-row>
    </b-modal>
  </div>
</template>

<script>
import axios from "axios";
import moment from "moment";
import VueSelect from "vue-select";

export default {
  data() {
    return {
      getFormTitle: "Revisi Tanggal Lembur",
      is_loading: false,
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
    getDurationReadable() {
      return this.$store.state.jobOrder.form.duration_readable;
    },
    form() {
      return this.$store.state.jobOrder.form;
    },
    hour_start: {
      get() {
        return this.$store.state.jobOrder.form.hour_start;
      },
      set(value) {
        this.$store.commit("jobOrder/INSERT_FORM_HOUR_START", {
          hour_start: value,
        });
      },
    },
    date_start: {
      get() {
        return this.$store.state.jobOrder.form.date_start;
      },
      set(value) {
        this.$store.commit("jobOrder/INSERT_FORM_DATE_START", {
          date_start: value,
        });
      },
    },
    hour_end: {
      get() {
        return this.$store.state.jobOrder.form.hour_end;
      },
      set(value) {
        this.$store.commit("jobOrder/INSERT_FORM_HOUR_END", {
          hour_end: value,
        });
      },
    },
    date_end: {
      get() {
        return this.$store.state.jobOrder.form.date_end;
      },
      set(value) {
        this.$store.commit("jobOrder/INSERT_FORM_DATE_END", {
          date_end: value,
        });
      },
    },
  },
  methods: {
    onCloseModal() {
      this.$bvModal.hide("overtime_report_form");
    },
    onChangeDuration() {
      this.$store.commit("jobOrder/INSERT_FORM_DURATION");
    },
    async onSend() {
      const Swal = this.$swal;

      // mengambil data hexa saja
      const request = {
        // ...this.form,
        id: this.form.id,
        datetime_start: this.form.datetime_start,
        datetime_end: this.form.datetime_end,
        user_id: this.getUserId,
      };

      //   console.info(request);
      //   return false;
      this.is_loading = true;
      await axios
        .post(
          `${this.getBaseUrl}/api/v1/job-status-has-parent/store-overtime-revision`,
          request
        )
        .then((responses) => {
          // console.info(responses);
          const data = responses.data;
          this.is_loading = false;

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

          if (data.success == true) {
            Toast.fire({
              icon: "success",
              title: data.message,
            });

            this.$bvModal.hide("overtime_report_form");
            this.$store.dispatch("jobOrder/fetchDataOvertimeReport");
          }
        })
        .catch((err) => {
          console.info(err);
          this.is_loading = false;

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
    disabledDate(date, currentValue) {
      return date <= moment();
    },
  },
};
</script>

<style lang="scss" scoped>
</style>
