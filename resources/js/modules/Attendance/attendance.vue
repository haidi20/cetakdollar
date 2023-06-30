<template>
  <div>
    <title>Absensi</title>
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
          <b-tab title="Utama">
            <Main />
          </b-tab>
          <b-tab title="Berdasarkan Karyawan">
            <Detail />
          </b-tab>
        </b-tabs>
      </div>
    </div>
  </div>
</template>

<script>
import Main from "./main.vue";
import Detail from "./detail.vue";
export default {
  props: {
    user: String,
    baseUrl: String,
  },
  data() {
    return {
      title: "Absensi",
      version: "v1.1",
    };
  },
  components: { Main, Detail },
  mounted() {
    this.$store.commit("INSERT_BASE_URL", { base_url: this.baseUrl });
    this.$store.commit("INSERT_USER", { user: JSON.parse(this.user) });

    this.$store.commit("attendance/INSERT_BASE_URL", {
      base_url: this.baseUrl,
    });
    this.$store.commit("master/INSERT_BASE_URL", {
      base_url: this.baseUrl,
    });
    this.$store.commit("employeeHasParent/INSERT_BASE_URL", {
      base_url: this.baseUrl,
    });

    this.$store.dispatch("attendance/fetchData");
    this.$store.dispatch("attendance/fetchDetail");
    this.$store.dispatch("employeeHasParent/fetchOption");
    this.$store.dispatch("master/fetchPosition");
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
