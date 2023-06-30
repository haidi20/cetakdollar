<template>
  <div>
    <DatatableClient
      :data="getData"
      :columns="columns"
      :options="options"
      nameStore="contractor"
      nameLoading="table"
      :filter="false"
      :footer="false"
      bordered
    >
      <template v-slot:tbody="{ filteredData }">
        <b-tr v-for="(item, index) in filteredData" :key="index">
          <b-td v-for="column in getColumns()" :key="column.label">{{ item[column.field] }}</b-td>
          <b-td>
            <!-- <b-button variant="info" size="sm" @click="onEdit(item)">

            </b-button>-->
            <span @click="onEdit(item)" class="cursor-pointer">
              <i class="bi bi-pencil" style="color: #31D2F2;"></i>
            </span>
            <span @click="onDelete(item)" class="cursor-pointer float-end">
              <i class="bi bi-trash" style="color: #BB2D3B;"></i>
            </span>
          </b-td>
        </b-tr>
      </template>
    </DatatableClient>
  </div>
</template>

<script>
import axios from "axios";
import moment from "moment";
import DatePicker from "vue2-datepicker";

import DatatableClient from "../../components/DatatableClient";

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
          label: "Alamat",
          field: "address",
          width: "100px",
          class: "",
        },
        {
          label: "No Handphone",
          field: "no_hp",
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
      return this.$store.state.contractor.data;
    },
    getLoadingTable() {
      return this.$store.state.contractor.loading.table;
    },
    getIsShowForm() {
      return this.$store.state.contractor.form.is_show_form;
    },
  },
  methods: {
    onEdit(form) {
      console.info(form);
      this.$store.commit("contractor/CLEAR_FORM");
      this.$store.commit("contractor/INSERT_FORM", {
        form: form,
      });
    },
    async onDelete(data) {
      const Swal = this.$swal;
      await Swal.fire({
        title: "Perhatian!!!",
        html: `Anda yakin ingin hapus <h2><b>${data.name}</b> ?</h2>`,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Ya hapus",
        cancelButtonText: "Batal",
      }).then(async (result) => {
        if (result.isConfirmed) {
          await axios
            .post(`${this.getBaseUrl}/api/v1/contractor/delete`, {
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

                this.$store.dispatch("contractor/fetchData");
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
    getColumns() {
      const columns = this.columns.filter((item) => item.label != "");
      return columns;
    },
    getPermissionAdd() {
      return true;
    },
  },
};
</script>

<style lang="scss" scoped>
</style>
