<template>
  <div>
    <b-modal
      id="vacation_filter"
      ref="vacation_filter"
      :title="getTitleForm"
      size="md"
      class="modal-custom"
      hide-footer
    >
      <b-row>
        <b-col>
          <b-form-group label="Bulan" label-for="month">
            <DatePicker
              id="month"
              v-model="params.month"
              format="YYYY-MM"
              type="month"
              placeholder="pilih Bulan"
              style="width: 100%"
            />
          </b-form-group>
        </b-col>
      </b-row>
      <b-row>
        <b-col>
          <b-form-group label="Kata Kunci" label-for="type_by" class>
            <input
              type="text"
              placeholder="search..."
              style="width: 100%"
              v-model="params.search"
              class="form-control"
            />
          </b-form-group>
        </b-col>
      </b-row>
      <br />
      <b-row>
        <b-col>
          <b-button variant="info" @click="onCloseModal()">Tutup</b-button>
          <b-button
            variant="success"
            size="sm"
            class="float-end"
            @click="onSend()"
            :disabled="getLoadingTable"
          >Kirim</b-button>
        </b-col>
      </b-row>
    </b-modal>
  </div>
</template>

<script>
import VueSelect from "vue-select";

export default {
  data() {
    return {
      getTitleForm: "Filter Data",
    };
  },
  components: {
    VueSelect,
  },
  computed: {
    getLoadingTable() {
      return this.$store.state.vacation.loading.table;
    },
    params() {
      return this.$store.state.vacation.params;
    },
  },
  methods: {
    onSend() {
      this.$bvModal.hide("vacation_filter");
      this.$store.dispatch("vacation/fetchData");
    },
    onCloseModal() {
      this.$bvModal.hide("vacation_filter");
    },
  },
};
</script>

<style lang="scss" scoped>
</style>
