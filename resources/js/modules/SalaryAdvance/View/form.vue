<template>
  <div>
    <b-modal
      id="salary_advance_form"
      ref="salary_advance_form"
      :title="getTitleForm"
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
        <b-col cols="12" md="6">
          <b-form-group label="Jumlah kasbon" label-for="loan_amount" class>
            <b-form-input v-model="loan_amount" id="loan_amount" name="loan_amount"></b-form-input>
          </b-form-group>
        </b-col>
        <b-col cols="12" md="6">
          <b-form-group label="Alasan" label-for="reason" class>
            <b-form-input v-model="form.reason" id="reason" name="reason"></b-form-input>
          </b-form-group>
        </b-col>
      </b-row>
      <br />
      <b-row>
        <b-col>
          <b-button variant="info" @click="onCloseModal()">Tutup</b-button>
          <b-button
            style="float: right"
            variant="success"
            @click="onSend()"
            :disabled="is_loading"
          >Simpan</b-button>
          <span v-if="is_loading" style="float: right">Loading...</span>
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
      getTitleForm: "Buat Kasbon",
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
    form() {
      return this.$store.state.salaryAdvance.form;
    },
    loan_amount: {
      get() {
        return this.$store.state.salaryAdvance.form.loan_amount_readable;
      },
      set(value) {
        this.$store.commit("salaryAdvance/INSERT_FORM_LOAN_AMOUNT", {
          loan_amount: value,
        });
      },
    },
  },
  methods: {
    onCloseModal() {
      this.$bvModal.hide("salary_advance_form");
    },
    async onSend() {
      const Swal = this.$swal;

      const request = {
        ...this.form,
        user_id: this.getUserId,
      };

      // console.info(request);
      this.is_loading = true;
      await axios
        .post(`${this.getBaseUrl}/api/v1/salary-advance/store`, request)
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

            this.$bvModal.hide("salary_advance_form");
            this.$store.dispatch("salaryAdvance/fetchData", {
              user_id: this.getUserId,
            });
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
  },
};
</script>

<style lang="scss" scoped>
</style>
