<template>
  <div>
    <b-modal
      id="salary_advance_filter"
      ref="salary_advance_filter"
      :title="getTitleForm"
      size="md"
      class="modal-custom"
      hide-footer
    >
      <b-row>
        <b-col>
          <label for="scope_id" style="display:inline-block">
            <b-form-checkbox style="display: inline" v-model="params.is_filter_month"></b-form-checkbox>
            <span @click="onActiveFilterMonth">pilih bulan</span>
          </label>
          <b-form-group label="Bulan" label-for="month" v-if="params.is_filter_month">
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
          <b-form-group label="Kategori" label-for="type" class>
            <VueSelect
              id="type"
              class="cursor-pointer"
              v-model="params.type"
              placeholder="Pilih Kategori"
              :options="getOptionTypes"
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
          <b-form-group label="Kata Kunci" label-for="data_by_type" class>
            <input
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
          <b-button variant="info" size="sm" class="float-end" @click="onFilter()">Kirim</b-button>
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
    getBaseUrl() {
      return this.$store.state.base_url;
    },
    getUserId() {
      return this.$store.state.user?.id;
    },
    getOptionTypes() {
      //   return this.$store.state.salaryAdvance.options.types.filter(
      //     (item) => this.params.is_filter_month && item.name != "all"
      //   );
      return this.$store.state.salaryAdvance.options.types.filter((item) => {
        if (!this.params.is_filter_month) {
          return item.id != "all" && item;
        }

        return item;
      });
    },
    getIsFilterMonth() {
      return this.$store.state.salaryAdvance.params.is_filter_month;
    },
    params() {
      return this.$store.state.salaryAdvance.params;
    },
  },
  watch: {
    getIsFilterMonth(value, oldValue) {
      if (value) {
        this.$store.commit("salaryAdvance/INSERT_PARAM_TYPE", { type: "all" });
      } else {
        this.$store.commit("salaryAdvance/INSERT_PARAM_TYPE", {
          type: "review",
        });
      }
    },
  },
  methods: {
    onFilter() {
      this.$bvModal.hide("salary_advance_filter");
      this.$store.dispatch("salaryAdvance/fetchData", {
        user_id: this.getUserId,
      });
    },
    onActiveFilterMonth() {
      this.params.is_filter_month = !this.params.is_filter_month;
    },
  },
};
</script>

<style lang="scss" scoped>
</style>
