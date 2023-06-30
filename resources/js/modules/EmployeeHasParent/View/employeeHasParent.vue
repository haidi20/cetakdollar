<template>
  <div>
    <b-modal
      id="data_employee"
      ref="data_employee"
      :title="getTitleForm"
      :size="getIsMobile ?'md' : 'xl'"
      class="modal-custom"
      hide-footer
    >
      <template v-if="getIsMobile">
        <b-tabs content-class="mt-3">
          <b-tab title="Utama">
            <FormMobile />
            <br />
            <TableMobile />
          </b-tab>
          <!-- <b-tab title="Data">
            <span>Data Karyawan</span>
          </b-tab>-->
        </b-tabs>
      </template>
      <template v-else>
        <FormDesktop />
        <br />
        <TableDesktop v-if="getForm.employee_base == 'choose_employee'" />
        <JobOrderTableHasParent v-else-if="getForm.employee_base == 'job_order'" />
        <ProjectTableHasParent v-else-if="getForm.employee_base == 'project'" />
      </template>
      <br />
      <b-row>
        <b-col>
          <b-button variant="info" @click="onCloseModal()">Tutup</b-button>
          <b-button
            v-if="getConditionBtnSave()"
            :disabled="getConditionDisabledBtnSave()"
            style="float: right"
            variant="success"
            @click="onSave()"
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

import FormMobile from "./formMobile";
import TableMobile from "./tableMobile";
import FormDesktop from "./formDesktop";
import TableDesktop from "./tableDesktop";
import JobOrderTableHasParent from "../../JobOrder/View/tableHasParent";
import ProjectTableHasParent from "../../Project/View/tableHasParent";

export default {
  components: {
    TableMobile,
    FormMobile,
    TableDesktop,
    FormDesktop,
    JobOrderTableHasParent,
    ProjectTableHasParent,
  },
  data() {
    return {
      is_loading: false,
      getTitleForm: "Data Karyawan",
    };
  },
  computed: {
    getBaseUrl() {
      return this.$store.state.base_url;
    },
    getUserId() {
      return this.$store.state.user?.id;
    },
    getJobOrderId() {
      return this.$store.state.jobOrder.form.id;
    },
    getJobOrderFormKind() {
      return this.$store.state.jobOrder.form.form_kind;
    },
    getIsDisabledBtnSave() {
      return this.$store.state.employeeHasParent.form.is_disabled_btn_save;
    },
    getParentName() {
      return this.$store.state.employeeHasParent.form.parent_name;
    },
    getData() {
      return this.$store.state.employeeHasParent.data.selecteds;
    },
    getDataActives() {
      return this.$store.state.employeeHasParent.data.actives;
    },
    getIsMobile() {
      return this.$store.state.employeeHasParent.is_mobile;
    },
    getForm() {
      return this.$store.state.employeeHasParent.form;
    },
  },
  methods: {
    onCloseModal() {
      this.$bvModal.hide("data_employee");
    },
    async onSave() {
      //   console.info(this.getJobOrderFormKind);

      //   console.info(this.getParentName);

      if (this.getParentName == "job_order") {
        // console.info(this.getJobOrderFormKind);
        const getValidationEmployee = await this.getValidationEmployee();

        if (getValidationEmployee) {
          return false;
        } else {
          this.$bvModal.hide("data_employee");
        }

        if (this.getJobOrderFormKind == null) {
          this.onSend();
        }
      } else {
        this.$bvModal.hide("data_employee");
      }
    },
    async onSend() {
      const Swal = this.$swal;

      let request = {
        job_order_id: this.getJobOrderId,
        date: moment(this.getForm.date).format("Y-MM-DD"),
        hour: this.getForm.hour,
        data_employees: [...this.getData],
        user_id: this.getUserId,
      };

      console.info(request);
      //   return false;
      this.is_loading = true;

      await axios
        .post(
          `${this.getBaseUrl}/api/v1/job-order/store-action-has-employee`,
          request
        )
        .then((responses) => {
          console.info(responses);
          this.is_loading = false;
          // return false;
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

            this.$store.dispatch("jobOrder/fetchData");
            this.$bvModal.hide("data_employee");
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
    async getValidationEmployee() {
      this.is_loading = true;

      return new Promise((resolve, reject) => {
        // setTimeout(() => {
        //   resolve();
        // }, duration);

        let request = {
          job_order_id: this.getJobOrderId,
          data_selecteds: [...this.getData],
        };

        // console.info(request);

        axios
          .post(`${this.getBaseUrl}/api/v1/job-order/find-employee-status`, {
            ...request,
          })
          .then((responses) => {
            // console.info(responses);
            const data = responses.data;

            // jika hasilnya 'true', maka di temukan
            //beberapa karyawan ada di job order lain
            if (!data.result) {
              resolve(false);
              this.$store.commit("jobOrder/UPDATE_IS_DISABLED_BTN_SEND", {
                value: false,
              });
            } else {
              this.$store.commit("employeeHasParent/INSERT_DATA_ACTIVE", {
                actives: data.actives,
              });
              this.$store.commit("jobOrder/UPDATE_IS_DISABLED_BTN_SEND", {
                value: true,
              });

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
                icon: "warning",
                title: "Maaf, beberapa karyawan aktif di job order lain.",
              });

              resolve(true);
            }
          })
          .catch((err) => {
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

            reject(new Error("Kesalahan sistem"));
          });

        this.is_loading = false;
      });
    },
    getConditionBtnSave() {
      let result = true;

      //   console.info(this.getForm.employee_base);

      //   if (this.getForm.employee_base != "job_order") {
      //     result = true;
      //   }

      if (this.getJobOrderFormKind == "read") {
        result = false;
      }

      return result;
    },
    getConditionDisabledBtnSave() {
      let result =
        this.getParentName == "job_order" ? this.getIsDisabledBtnSave : false;
      //   let result = false;

      //   console.info(this.getJobOrderFormKind);
      if (this.getJobOrderFormKind != null) {
        result = false;
      }

      if (this.is_loading) {
        result = true;
      }

      return result;
    },
  },
};
</script>

<style lang="scss" scoped>
</style>
