<template>
  <div>
    <b-modal
      id="roster_form"
      ref="roster_form"
      :title="title_form"
      size="md"
      class="modal-custom"
      hide-footer
    >
      <b-row>
        <b-col cols>
          <b-form-group label="Bulan" label-for="month">
            <DatePicker
              id="month"
              v-model="form.month"
              format="YYYY-MM"
              type="month"
              placeholder="pilih bulan"
              style="width: 100%"
              :disabled-date="(date, currentValue) => disabledDate(date, currentValue)"
            />
          </b-form-group>
        </b-col>
        <b-col cols>
          <b-form-group label="Tanggal Cuti" label-for="date">
            <DatePicker
              id="date"
              v-model="form.date_vacation"
              format="YYYY-MM-DD"
              type="date"
              placeholder="Pilih Tanggal"
              style="width: 100%"
              range
              :disabled-date="(date, currentValue) => disabledDate(date, currentValue)"
            />
          </b-form-group>
        </b-col>
      </b-row>
      <b-row>
        <b-col cols="6">
          <b-form-group
            :label="`Hari Off ${form.work_schedule == '5,2' ? 'Pertama': ''}`"
            label-for="day_off_one"
          >
            <VueSelect
              id="day_off_one"
              class="cursor-pointer"
              v-model="form.day_off_one"
              placeholder="Pilih Hari"
              :options="getOptionDays"
              :reduce="(data) => data.id"
              label="name"
              searchable
              style="min-width: 180px"
            />
          </b-form-group>
        </b-col>
        <b-col cols="6" v-if="form.work_schedule == '5,2'">
          <b-form-group label="Hari Off Kedua" label-for="day_off_two">
            <VueSelect
              id="day_off_two"
              class="cursor-pointer"
              v-model="form.day_off_two"
              placeholder="Pilih Hari"
              :options="getOptionDays"
              :reduce="(data) => data.id"
              label="name"
              searchable
              style="min-width: 180px"
            />
          </b-form-group>
        </b-col>
      </b-row>
      <br />
      <b-row>
        <b-col>
          <b-button variant="info" @click="onCloseModal()">Tutup</b-button>
          <b-button
            :disabled="is_loading"
            variant="success"
            size="sm"
            class="float-end"
            @click="onSend()"
          >Kirim</b-button>
          <span v-if="is_loading" class="float-end">Loading...</span>
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
      title_form: null,
    };
  },
  mounted() {
    //
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
    getTitleForm() {
      return this.$store.state.roster.get_title_form;
    },
    getOptionDays() {
      return this.$store.state.roster.options.list_days;
    },
    getOptionPositions() {
      return this.$store.state.master.data.positions;
    },
    form() {
      return this.$store.state.roster.form;
    },
  },
  watch: {
    getTitleForm(value, oldValue) {
      this.title_form = value;
    },
  },
  methods: {
    onCloseModal() {
      this.$bvModal.hide("roster_form");
    },
    async onSend() {
      //   console.info(this.form);

      //   return false;

      const getDateVacation =
        this.form.date_vacation[0] != null
          ? [
              moment(this.form.date_vacation[0]).format("Y-MM-DD"),
              moment(this.form.date_vacation[1]).format("Y-MM-DD"),
            ]
          : null;

      const requests = {
        ...this.form,
        date_vacation: getDateVacation,
        month: moment(this.form.month).format("Y-MM"),
        user_id: this.getUserId,
      };

      //   console.info(requests);

      //   return false;

      this.is_loading = true;

      await axios
        .post(`${this.getBaseUrl}/api/v1/roster/store`, requests)
        .then((responses) => {
          console.info(responses);
          this.is_loading = false;

          //   return false;
          const data = responses.data;

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

            this.$store.dispatch("roster/fetchData");
            this.$store.dispatch("roster/fetchTotal", {
              positions: this.getOptionPositions,
            });
            this.$bvModal.hide("roster_form");
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
      return date < moment({ date: 1 });
    },
  },
};
</script>

<style lang="scss" scoped>
</style>
