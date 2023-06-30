<template>
  <div>
    <b-row>
      <b-col cols md="6">
        <b-button variant="info" size="sm" @click="onShowOsMaster()">Data OS</b-button>
      </b-col>
      <b-col cols md="4">
        <b-button variant="success" size="sm" @click="onAdd()" v-if="!getReadOnly()">Tambah</b-button>
      </b-col>
    </b-row>
    <br />
    <b-row v-for="(os, index) in form.ordinary_seamans" :key="index">
      <b-col cols class="mb-4">
        <b-form-group label="Pilih OS" label-for="os_id" style="display: inline">
          <VueSelect
            id="os_id"
            class="cursor-pointer"
            v-model="os.ordinary_seaman_id"
            :options="getOptionOses"
            :reduce="(data) => data.id"
            label="name"
            searchable
            style="min-width: 180px"
            :disabled="getReadOnly()"
          />
        </b-form-group>
      </b-col>
      <b-col cols="1" style="align-self: center;">
        <span @click="onDelete(index)" class="cursor-pointer">
          <!-- <i class="fas fa-trash" style="color: #BB2D3B;"></i> -->
          <i class="bi bi-trash" style="color: #BB2D3B;"></i>
        </span>
      </b-col>
    </b-row>
    <OrdinarySeaman />
  </div>
</template>

<script>
import axios from "axios";
import moment from "moment";
import VueSelect from "vue-select";

// partials
import OrdinarySeaman from "../OrdinarySeaman/ordinarySeaman.vue";
export default {
  components: {
    VueSelect,
    OrdinarySeaman,
  },
  computed: {
    getBaseUrl() {
      return this.$store.state.base_url;
    },
    getUserId() {
      return this.$store.state.user?.id;
    },
    getOptionOses() {
      return this.$store.state.os.data;
    },
    form() {
      return this.$store.state.project.form;
    },
  },
  methods: {
    onShowOsMaster() {
      this.$bvModal.show("os_form");
    },
    onAdd() {
      this.$store.commit("project/INSERT_FORM_NEW_OS");
    },
    onDelete(index) {
      this.$store.commit("project/DELETE_FORM_OS", { index });
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
