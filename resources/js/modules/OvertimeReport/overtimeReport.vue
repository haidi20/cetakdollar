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
          <b-tab title="Utama">
            <Main />
          </b-tab>
        </b-tabs>
      </div>
    </div>

    <Form />
  </div>
</template>

<script>
import Main from "./main.vue";
import Form from "./form.vue";
export default {
  props: {
    user: String,
    baseUrl: String,
    statuses: String,
  },
  data() {
    return {
      title: "Laporan SPL",
      version: "v1.1",
    };
  },
  components: {
    Main,
    Form,
  },
  mounted() {
    this.$store.commit("INSERT_BASE_URL", { base_url: this.baseUrl });
    this.$store.commit("INSERT_USER", { user: JSON.parse(this.user) });

    ["jobOrder", "master"].map((item) => {
      this.$store.commit(`${item}/INSERT_BASE_URL`, {
        base_url: this.baseUrl,
      });
    });
    this.$store.dispatch("fetchPermission");

    this.$store.dispatch("jobOrder/fetchDataOvertimeReport");
  },
  computed: {
    getBaseUrl() {
      return this.$store.state.base_url;
    },
    getUserId() {
      return this.$store.state.user?.id;
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
