<template>
  <div>
    <b-modal
      id="project_form"
      ref="project_form"
      :title="getFormTitle"
      size="lg"
      class="modal-custom"
      hide-footer
    >
      <Form />
      <hr />
      <b-row>
        <b-col cols>
          <b-tabs content-class="mt-3">
            <b-tab title="Kepala Pemborong" @click="onChangeTab('contractor')">
              <ContractorHasParent />
            </b-tab>
            <b-tab title="OS" @click="onChangeTab('os')">
              <OrdinarySeamanHasParent />
            </b-tab>
            <b-tab title="Job Order" @click="onChangeTab('job_order')">
              <JobOrderTableHasParent />
            </b-tab>
          </b-tabs>
        </b-col>
      </b-row>
      <br />
      <b-row>
        <b-col>
          <b-button style="float: right" variant="info" @click="onCloseModal()">Tutup</b-button>
          <b-button
            variant="success"
            @click="onSend()"
            v-if="!getReadOnly()"
            :disabled="is_loading"
          >Simpan</b-button>
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

import Form from "../View/form";
import ContractorHasParent from "../../ContractorHasParent/contractorHasParent";
import OrdinarySeamanHasParent from "../../OrdinarySeamanHasParent/ordinarySeamanHasParent";
import JobOrderTableHasParent from "../../JobOrder/View/tableHasParent";
import { checkNull } from "../../../utils";

export default {
  data() {
    return {
      is_loading: false,
    };
  },
  components: {
    Form,
    VueSelect,
    JobOrderTableHasParent,
    ContractorHasParent,
    OrdinarySeamanHasParent,
  },
  computed: {
    getBaseUrl() {
      return this.$store.state.base_url;
    },
    getUserId() {
      return this.$store.state.user?.id;
    },
    getFormTitle() {
      return this.$store.state.project.form.form_title;
    },
    form() {
      return this.$store.state.project.form;
    },
  },
  watch: {
    //
  },
  methods: {
    onCloseModal() {
      this.$store.commit("project/CLEAR_FORM");
      this.$bvModal.hide("project_form");
    },
    onChangeTab(type) {
      //   console.info(type);
    },
    async onSend() {
      const Swal = this.$swal;

      // mengambil data hexa saja
      const request = {
        ...this.form,
        user_id: this.getUserId,
      };

      const getValidation = this.getValidation();

      if (getValidation) {
        return false;
      }

      //   console.info(request);
      //   return false;
      this.is_loading = true;

      await axios
        .post(`${this.getBaseUrl}/api/v1/project/store`, request)
        .then((responses) => {
          //   console.info(responses);
          this.is_loading = false;
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

            this.$bvModal.hide("project_form");
            this.$store.dispatch("project/fetchData");
            this.$store.commit("project/CLEAR_FORM");
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
    getValidation() {
      let result = false;

      //   console.info(this.form.price, this.form.remaining_payment);

      if (
        (this.form.remaining_payment < 0) &
        (checkNull(this.form.price) != null &&
          checkNull(this.form.remaining_payment) != null)
      ) {
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
          title: "Maaf, DP tidak boleh lebih dari biaya proyek",
        });

        result = true;
      }

      return result;
    },
    getReadOnly() {
      const readOnly = this.$store.getters["project/getReadOnly"];
      //   console.info(readOnly);

      return readOnly;
    },
  },
};
</script>

<style lang="scss" scoped>
</style>
