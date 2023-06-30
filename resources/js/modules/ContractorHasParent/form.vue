<template>
  <div>
    <b-row>
      <b-col cols md="6">
        <b-button variant="info" size="sm" @click="onShowContractorMaster()">Data Kepala Pemborong</b-button>
      </b-col>
      <b-col cols md="4">
        <b-button variant="success" size="sm" @click="onAdd()" v-if="!getReadOnly()">Tambah</b-button>
      </b-col>
    </b-row>
    <br />
    <b-row v-for="(contractor, index) in form.contractors" :key="index">
      <b-col cols class="mb-4">
        <b-form-group label="Pilih Kepala Proyek" label-for="name" style="display: inline">
          <VueSelect
            id="contractor_id"
            class="cursor-pointer"
            v-model="contractor.contractor_id"
            :options="getOptionContractors"
            :reduce="(data) => data.id"
            label="name"
            searchable
            style="min-width: 180px"
            :disabled="getReadOnly()"
          />
        </b-form-group>
      </b-col>
      <b-col v-if="!getReadOnly()" cols="1" style="align-self: center;">
        <span @click="onDelete(index)" class="cursor-pointer">
          <!-- <i class="fas fa-trash" style="color: #BB2D3B;"></i> -->
          <i class="bi bi-trash" style="color: #BB2D3B;"></i>
        </span>
      </b-col>
    </b-row>
    <Contractor />
  </div>
</template>

<script>
import axios from "axios";
import moment from "moment";
import VueSelect from "vue-select";

// partials
import Contractor from "../Contractor/contractor";
export default {
  components: {
    VueSelect,
    Contractor,
  },
  computed: {
    getBaseUrl() {
      return this.$store.state.base_url;
    },
    getUserId() {
      return this.$store.state.user?.id;
    },
    getOptionContractors() {
      return this.$store.state.contractor.data;
    },
    form() {
      return this.$store.state.project.form;
    },
  },
  methods: {
    onShowContractorMaster() {
      this.$bvModal.show("contractor_form");
    },
    onAdd() {
      this.$store.commit("project/INSERT_FORM_NEW_CONTRACTOR");
    },
    onDelete(index) {
      //   console.info(index);
      this.$store.commit("project/DELETE_FORM_CONTRACTOR", { index });
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
