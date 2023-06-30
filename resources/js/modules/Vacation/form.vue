<template>
  <div>
    <b-modal
      id="vacation_form"
      ref="vacation_form"
      :title="getFormTitle"
      size="md"
      class="modal-custom"
      hide-footer
    >
      <b-row>
        <b-col cols>
          <b-form-group label="Karyawan" label-for="employee_id" class>
            <VueSelect
              id="employee_id"
              class="cursor-pointer"
              v-model="form.employee_id"
              placeholder="Pilih Karyawan"
              :options="getOptionEmployees"
              :reduce="(data) => data.id"
              label="name_and_position"
              searchable
              style="min-width: 180px"
            />
          </b-form-group>
        </b-col>
      </b-row>
      <b-row>
        <b-col cols>
          <b-form-group label="Tanggal Awal Cuti" label-for="date_start">
            <DatePicker
              id="date_start"
              v-model="form.date_start"
              format="YYYY-MM-DD"
              type="date"
              placeholder="pilih tanggal"
              style="width: 100%"
              :disabled-date="(date, currentValue) => disabledDate(date, currentValue)"
            />
          </b-form-group>
        </b-col>
      </b-row>
      <b-row>
        <b-col cols>
          <b-form-group label="Tanggal Selesai Cuti" label-for="date_end">
            <DatePicker
              id="date_end"
              v-model="form.date_end"
              format="YYYY-MM-DD"
              type="date"
              placeholder="pilih tanggal"
              style="width: 100%"
              :disabled-date="(date, currentValue) => disabledDate(date, currentValue)"
            />
          </b-form-group>
        </b-col>
      </b-row>
      <b-row>
        <b-col cols>
          <b-form-group label="Keterangan" label-for="note" class>
            <b-form-input v-model="form.note" id="note" name="note" autocomplete="off"></b-form-input>
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
    getOptionEmployees() {
      return this.$store.state.employeeHasParent.data.options;
    },
    getFormTitle() {
      return this.$store.state.vacation.form_title;
    },
    form() {
      return this.$store.state.vacation.form;
    },
  },
  methods: {
    onCloseModal() {
      this.$bvModal.hide("vacation_form");
    },
    async onSend() {
      const Swal = this.$swal;

      // mengambil data hexa saja
      const request = {
        ...this.form,
        date_start: moment(this.form.date_start).format("Y-MM-DD"),
        date_end: moment(this.form.date_end).format("Y-MM-DD"),
        user_id: this.getUserId,
      };

      //   console.info(request);

      //   return false;
      this.is_loading = true;
      await axios
        .post(`${this.getBaseUrl}/api/v1/vacation/store`, request)
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

            this.$bvModal.hide("vacation_form");
            this.$store.dispatch("vacation/fetchData");
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
