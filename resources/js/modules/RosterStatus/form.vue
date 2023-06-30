<template>
  <div>
    <b-modal
      id="roster_status_form"
      ref="roster_status_form"
      :title="getTitleForm"
      size="md"
      class="modal-custom"
      hide-footer
    >
      <b-row>
        <b-col cols>
          <b-form-group label="Nama Status" label-for="name" class>
            <b-form-input v-model="form.name" id="name" name="name"></b-form-input>
          </b-form-group>
        </b-col>
        <b-col cols>
          <b-form-group label="Nama Inisial" label-for="initial" class>
            <b-form-input v-model="form.initial" id="initial" name="initial"></b-form-input>
          </b-form-group>
        </b-col>
      </b-row>
      <b-row>
        <b-col cols>
          <b-form-group id="input-group-3" label="Warna:" label-for="input-3">
            <Chrome v-model="form.color" />
          </b-form-group>
        </b-col>
      </b-row>
      <b-row>
        <b-col cols>
          <b-form-group label="Catatan" label-for="note" class>
            <b-form-textarea id="textarea" v-model="form.note" placeholder rows="3" max-rows="6"></b-form-textarea>
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
          <span v-if="is_loading">Loading...</span>
        </b-col>
      </b-row>
    </b-modal>
  </div>
</template>

<script>
import axios from "axios";
import moment from "moment";
import { Chrome } from "vue-color";

export default {
  data() {
    return {
      getTitleForm: "Buat Roster Status",
      is_loading: false,
    };
  },
  components: {
    Chrome,
  },
  computed: {
    getBaseUrl() {
      return this.$store.state.base_url;
    },
    getUserId() {
      return this.$store.state.user?.id;
    },
    form() {
      return this.$store.state.rosterStatus.form;
    },
  },
  methods: {
    onCloseModal() {
      this.$store.commit("rosterStatus/CLEAR_FORM");
      this.$bvModal.hide("roster_status_form");
    },
    async onSend() {
      const Swal = this.$swal;

      // mengambil data hexa saja
      const request = {
        ...this.form,
        color: this.form.color.hex ? this.form.color.hex : this.form.color,
      };

      // console.info(request);

      await axios
        .post(`${this.getBaseUrl}/api/v1/roster-status/store`, request)
        .then((responses) => {
          // console.info(responses);
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

            this.$bvModal.hide("roster_status_form");
            this.$store.dispatch("rosterStatus/fetchData");
            this.$store.commit("rosterStatus/CLEAR_FORM");
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
        });
    },
  },
};
</script>

<style lang="scss" scoped>
</style>
