<template>
  <div>
    <DatatableClient
      :data="getData"
      :columns="columns"
      :options="options"
      nameStore="rosterStatus"
      nameLoading="table"
      :filter="true"
      :footer="false"
      bordered
    >
      <template v-slot:filter>
        <b-col cols>
          <b-button variant="success" size="sm" @click="onCreate()">Tambah</b-button>
        </b-col>
      </template>
      <template v-slot:tbody="{ filteredData }">
        <b-tr v-for="(item, index) in filteredData" :key="index">
          <b-td>{{ item.name }}</b-td>
          <b-td>{{ item.initial }}</b-td>
          <b-td>{{ item.note }}</b-td>
          <b-td :style="{backgroundColor: item.color}">
            <p :style="{ color: item.color }">.</p>
          </b-td>
          <b-td>
            <b-button variant="info" size="sm" @click="onEdit(item)">Ubah</b-button>
            <b-button variant="danger" size="sm" @click="onDelete(item)">Hapus</b-button>
          </b-td>
        </b-tr>
      </template>
    </DatatableClient>
    <Form />
  </div>
</template>

<script>
import axios from "axios";
import moment from "moment";
import DatePicker from "vue2-datepicker";

import DatatableClient from "../../components/DatatableClient";
import Form from "./form";

export default {
  data() {
    return {
      columns: [
        {
          label: "Nama",
          field: "name",
          width: "100px",
          class: "",
        },
        {
          label: "Nama Inisial",
          field: "initial",
          width: "100px",
          class: "",
        },
        {
          label: "Catatan",
          field: "note",
          width: "100px",
          class: "",
        },
        {
          label: "Warna",
          field: "color",
          width: "100px",
          class: "",
        },
        {
          label: "",
          class: "",
          width: "10px",
        },
      ],
      options: {
        perPage: 5,
        // perPageValues: [5, 10, 25, 50, 100],
      },
    };
  },
  components: {
    Form,
    DatePicker,
    DatatableClient,
  },
  computed: {
    getBaseUrl() {
      return this.$store.state.base_url;
    },
    getUserId() {
      return this.$store.state.user?.id;
    },
    getData() {
      return this.$store.state.rosterStatus.data;
    },
  },
  methods: {
    onEdit(form) {
      this.$store.commit("rosterStatus/CLEAR_FORM");
      this.$store.commit("rosterStatus/INSERT_FORM", {
        form: form,
      });
      this.$bvModal.show("roster_status_form");
    },
    onCreate() {
      this.$store.commit("rosterStatus/CLEAR_FORM");
      this.$bvModal.show("roster_status_form");
    },
    async onDelete(data) {
      const Swal = this.$swal;
      await Swal.fire({
        title: "Perhatian!!!",
        html: `Anda yakin ingin hapus Status <h2><b>${data.name}</b> ?</h2>`,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Ya hapus",
        cancelButtonText: "Batal",
      }).then(async (result) => {
        if (result.isConfirmed) {
          await axios
            .post(`${this.getBaseUrl}/api/v1/roster-status/delete`, {
              id: data.id,
              user_id: this.getUserId,
            })
            .then((responses) => {
              //   console.info(responses);
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

                this.$store.dispatch("rosterStatus/fetchData");
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
        }
      });
    },
    customLabel(color) {
      return {
        backgroundColor: color,
      };
    },
  },
};
</script>

<style lang="scss" scoped>
</style>
