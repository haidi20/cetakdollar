<template>
  <div>
    <title>Laporan Kasbon</title>
    <div class="card shadow mb-4">
      <div class="card-header py-3">
        <b-row>
          <b-col class="place-title">
            <h2>{{ title }}</h2>
            <p class="version">{{ version }}</p>
          </b-col>
        </b-row>
        <b-row>
          <b-col>
            <FilterData />
          </b-col>
        </b-row>
        <br />
        <b-tabs content-class="mt-3">
          <b-tab title="Utama" @click="onChangeTab('main')" active>
            <Main />
          </b-tab>
        </b-tabs>
      </div>
    </div>
  </div>
</template>

<script>
import Main from "./main";
import FilterData from "./filterData.vue";
export default {
  props: {
    user: String,
    baseUrl: String,
  },
  data() {
    return {
      title: "Laporan Kasbon",
      version: "v1.1",
      tab_name: "main",
    };
  },
  components: { Main, FilterData },
  mounted() {
    this.$store.commit("INSERT_BASE_URL", { base_url: this.baseUrl });
    this.$store.commit("INSERT_USER", { user: JSON.parse(this.user) });

    ["salaryAdvanceReport", "master"].map((item) => {
      this.$store.commit(`${item}/INSERT_BASE_URL`, {
        base_url: this.baseUrl,
      });
    });

    this.$store.dispatch("fetchPermission");
  },
  methods: {
    onChangeTab(value) {
      this.tab_name = value;
    },
  },
};
</script>

<style lang="css" scoped>
.version {
  font-size: 13px;
  margin-left: 5px;
}
.place-title {
  display: -webkit-inline-box;
}
</style>
