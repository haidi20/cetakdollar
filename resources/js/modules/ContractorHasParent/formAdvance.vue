<template>
  <div>
    <b-row style="margin-top: 3rem">
      <b-col cols>
        <!-- <b-form-group label="Pilih Kepala Proyek" label-for="name" class>
          <b-form-input v-model="form.name" id="name" name="name" autocomplete="off"></b-form-input>
        </b-form-group>-->
        <label for="name" style="display:inline-block">
          <span>Pilih Kepala Proyek</span>
          <span @click="onShowContractorMaster" class="cursor-pointer">
            <i class="fas fa-edit"></i>
          </span>
        </label>
        <VueSelect
          id="contractor_id"
          class="cursor-pointer"
          v-model="form.contractor_id"
          :options="getOptionContractors"
          :reduce="(data) => data.id"
          label="name"
          searchable
          style="min-width: 180px"
        />
      </b-col>
    </b-row>
  </div>
</template>

<script>
import axios from "axios";
import moment from "moment";
import VueSelect from "vue-select";
export default {
  components: {
    VueSelect,
  },
  computed: {
    getOptionContractors() {
      return this.$store.state.contractor.data;
    },
    form() {
      return this.$store.state.contractorHasParent.form;
    },
  },
  methods: {
    onShowContractorMaster() {
      // show
    },
  },
};
</script>

<style lang="scss" scoped>
</style>
