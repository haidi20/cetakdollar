<template>
  <div>
    <b-modal
      id="job_order_filter"
      ref="job_order_filter"
      :title="getTitleForm"
      size="md"
      class="modal-custom"
      hide-footer
    >
      <b-row>
        <b-col cols>
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
        <b-col cols>
          <b-form-group label="Pilih Status" label-for="status" class>
            <VueSelect
              id="status"
              class="cursor-pointer"
              v-model="params.status"
              :options="getOptionStatuses"
              :reduce="(data) => data.id"
              label="name"
              searchable
              style="min-width: 180px"
            />
          </b-form-group>
        </b-col>
      </b-row>
      <b-row>
        <b-col cols>
          <b-form-group label="Pilih Data Berdasarkan" label-for="created_by" class>
            <VueSelect
              id="created_by"
              class="cursor-pointer"
              v-model="params.created_by"
              placeholder="Pilih Data Berdasarkan"
              :options="getOptionCreateByes"
              :reduce="(data) => data.id"
              label="name"
              searchable
              style="min-width: 180px"
            />
          </b-form-group>
        </b-col>
      </b-row>
      <b-row>
        <b-col>
          <b-form-group label="Kata Kunci" label-for="search" class>
            <input
              id="search"
              name="search"
              type="text"
              v-model="params.search"
              placeholder="search..."
              style="width: 100%"
              class="form-control"
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
    getOptionStatuses() {
      return this.$store.state.jobOrder.options.statuses;
    },
    getOptionCreateByes() {
      return this.$store.state.jobOrder.options.create_byes;
    },
    params() {
      return this.$store.state.jobOrder.params;
    },
  },
  methods: {
    onSend() {
      this.$bvModal.hide("job_order_filter");

      this.$store.dispatch("jobOrder/fetchData");
    },
    onCloseModal() {
      this.$bvModal.hide("job_order_filter");
    },
  },
};
</script>

<style lang="scss" scoped>
#job_order_filter {
  //z-index: 10;
}
</style>
