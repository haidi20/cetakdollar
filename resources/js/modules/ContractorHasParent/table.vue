<template>
  <div>
    <DatatableClient
      :data="getData"
      :columns="columns"
      :options="options"
      nameStore="contractorHasParent"
      nameLoading="table"
      :filter="true"
      :footer="false"
      bordered
    >
      <template v-slot:filter>
        <b-col col md="2" v-if="getPermissionAdd()">
          <b-button
            class="float-left"
            :variant="getIsShowForm ? 'danger' : 'success'"
            size="sm"
            @click="onShowForm"
            :disabled="getLoadingTable"
          >{{ getIsShowForm ? "Tutup" : "Tambah" }}</b-button>
        </b-col>
        <b-col cols></b-col>
      </template>
      <template v-slot:tbody="{ filteredData }">
        <b-tr v-for="(item, index) in filteredData" :key="index">
          <b-td v-for="column in getColumns()" :key="column.label">{{ item[column.field] }}</b-td>
          <b-td>
            <!-- <b-button variant="info" size="sm" @click="onEdit(item)">

            </b-button>-->
            <span @click="onEdit(item)" class="cursor-pointer">
              <i class="fas fa-edit" style="color: #31D2F2;"></i>
            </span>
            <span @click="onDelete(item)" class="cursor-pointer float-end">
              <i class="fas fa-trash" style="color: #BB2D3B;"></i>
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
      return this.$store.state.contractorHasParent.data;
    },
    getLoadingTable() {
      return this.$store.state.contractorHasParent.loading.table;
    },
    getIsShowForm() {
      return this.$store.state.contractorHasParent.form.is_show_form;
    },
  },
  methods: {
    onShowForm() {
      this.$store.commit("contractorHasParent/UPDATE_IS_SHOW_FORM", {
        value: !this.getIsShowForm,
      });
      //   console.info(this.is_show_form);
      if (this.is_show_form) {
        this.$store.commit("contractorHasParent/INSERT_TYPE_FORM", {
          type_form: "create",
        });
        this.$store.commit("contractorHasParent/CLEAR_FORM");
      }
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
