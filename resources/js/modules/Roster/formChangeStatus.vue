<template>
  <div>
    <b-modal
      id="roster_change_status_form"
      ref="roster_change_status_form"
      :title="title_form"
      size="md"
      class="modal-custom"
      hide-footer
    >
      <b-row>
        <b-col cols="6" style="align-self: center;">
          <span>status sebelumnya :</span>
          <span :style="setStyle()">{{form.value}}</span>
        </b-col>
        <b-col cols="6">
          <b-form-group label="Status" label-for="date">
            <VueSelect
              id="roster_status_id"
              class="cursor-pointer"
              v-model="form.roster_status_id"
              placeholder="Pilih Status"
              :options="getOptionRosterStatuses"
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
          <b-button variant="success" size="sm" class="float-end" @click="onSend()">Kirim</b-button>
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
      title_form: "Ganti Status",
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
    getOptionRosterStatuses() {
      return this.$store.state.rosterStatus.data;
    },
    getDataSelected() {
      return this.$store.state.roster.data_selected;
    },
    form() {
      return this.$store.state.roster.form;
    },
  },
  methods: {
    onCloseModal() {
      this.$bvModal.hide("roster_change_status_form");
    },
    async onSend() {
      const Swal = this.$swal;

      // console.info(request);

      await axios
        .post(`${this.getBaseUrl}/api/v1/roster/store-change-status`, this.form)
        .then((responses) => {
          //   console.info(responses);

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

            this.$bvModal.hide("roster_change_status_form");
            this.$store.dispatch("roster/fetchData");
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
    setStyle() {
      return {
        padding: "1px 8px 1px 8px",
        backgroundColor: this.form.color,
      };
    },
  },
};
</script>

<style lang="scss" scoped>
</style>
