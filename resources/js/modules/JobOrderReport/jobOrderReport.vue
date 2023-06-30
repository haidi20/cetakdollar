<template>
  <div>
    <title>Laporan Job Order</title>
    <div class="card shadow mb-4">
      <div class="card-header py-3">
        <b-row>
          <b-col class="place-title">
            <h2>{{ title }}</h2>
            <p class="version">{{ version }}</p>
          </b-col>
        </b-row>
        <br />
        <b-tabs content-class="mt-3" active>
          <!-- semua data -->
          <b-tab title="Semua">
            <Main />
          </b-tab>
        </b-tabs>
      </div>
    </div>
    <Modal />
  </div>
</template>

<script>
import Main from "./main";
import Modal from "../jobOrder/View/modal";
export default {
  props: {
    user: String,
    baseUrl: String,
    statuses: String,
  },
  components: {
    Main,
    Modal,
  },
  data() {
    return {
      title: "Laporan Job Order",
      version: "v1.1",
    };
  },
  mounted() {
    this.$store.commit("INSERT_BASE_URL", { base_url: this.baseUrl });
    this.$store.commit("INSERT_USER", { user: JSON.parse(this.user) });

    ["jobOrder", "project", "employeeHasParent", "master"].map((item) => {
      this.$store.commit(`${item}/INSERT_BASE_URL`, {
        base_url: this.baseUrl,
      });
    });
    this.$store.dispatch("fetchPermission");

    this.$store.dispatch("master/fetchJob");
    this.$store.dispatch("master/fetchPosition");
    this.$store.dispatch("employeeHasParent/fetchOption");
    this.$store.dispatch("project/fetchDataBaseDateEnd");
    this.$store.dispatch("jobOrder/fetchDataReport");
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
