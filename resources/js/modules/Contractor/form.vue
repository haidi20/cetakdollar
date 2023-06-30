<template>
  <div>
    <b-row>
      <b-col cols>
        <b-form-group label="Nama Kepala Pemborong" label-for="name" class>
          <b-form-input v-model="form.name" id="name" name="name" autocomplete="off"></b-form-input>
        </b-form-group>
      </b-col>
      <b-col cols>
        <b-form-group label="Alamat" label-for="address" class>
          <b-form-input v-model="form.address" id="address" name="address" autocomplete="off"></b-form-input>
        </b-form-group>
      </b-col>
      <b-col cols>
        <b-form-group label="No Handphone" label-for="no_hp" class>
          <b-form-input v-model="form.no_hp" id="no_hp" name="no_hp" autocomplete="off"></b-form-input>
        </b-form-group>
      </b-col>
    </b-row>
    <br />
    <b-row>
      <b-col style="text-align: end">
        <b-button variant="danger" @click="onCloseModal()">Hapus</b-button>
        <b-button
          variant="success"
          @click="onSend()"
          :disabled="is_loading"
          style="margin-left: 2rem"
        >Simpan</b-button>
        <span v-if="is_loading">Loading...</span>
      </b-col>
    </b-row>
  </div>
</template>

<script>
import axios from "axios";
import moment from "moment";

export default {
  data() {
    return {
      is_loading: false,
    };
  },
  computed: {
    getBaseUrl() {
      return this.$store.state.base_url;
    },
    getUserId() {
      return this.$store.state.user?.id;
    },
    getData() {
      return this.$store.state.contractorHasParent.data;
    },
    form() {
      return this.$store.state.contractor.form;
    },
  },
  methods: {
    onCloseModal() {
      this.$store.commit("contractor/CLEAR_FORM");
      //   this.$bvModal.hide("contractor_form");
    },
    async onSend() {
      const Swal = this.$swal;

      // mengambil data hexa saja
      const request = {
        ...this.form,
        user_id: this.getUserId,
      };

      // console.info(request);

      await axios
        .post(`${this.getBaseUrl}/api/v1/contractor/store`, request)
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

            this.$store.dispatch("contractor/fetchData");
            this.$store.commit("contractor/CLEAR_FORM");
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

