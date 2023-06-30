<template>
  <div>
    <title>Roster</title>
    <div class="card shadow mb-4">
      <div class="card-header py-3">
        <b-row>
          <b-col class="place-title">
            <h2>{{ title }}</h2>
            <p class="version">{{ version }}</p>
          </b-col>
        </b-row>
        <br />
        <Table />
        <FormModal />
      </div>
    </div>
  </div>
</template>

<script>
import Table from "./table";
import FormModal from "./formModal";

export default {
  props: {
    user: String,
    baseUrl: String,
  },
  data() {
    return {
      title: "Proyek",
      version: "v1.3",
    };
  },
  components: {
    Table,
    FormModal,
  },
  mounted() {
    this.$store.commit("INSERT_BASE_URL", { base_url: this.baseUrl });
    this.$store.commit("INSERT_USER", { user: JSON.parse(this.user) });

    [
      "os",
      "master",
      "project",
      "jobOrder",
      "contractor",
      "employeeHasParent",
    ].map((item) => {
      this.$store.commit(`${item}/INSERT_BASE_URL`, {
        base_url: this.baseUrl,
      });
    });

    this.$store.dispatch("fetchPermission");
    this.$store.dispatch("project/fetchData");
    this.$store.dispatch("contractor/fetchData");
    this.$store.dispatch("os/fetchData");
    this.$store.dispatch("master/fetchJob");
    this.$store.dispatch("master/fetchBarge");
    this.$store.dispatch("master/fetchLocation");
    // this.$store.dispatch("master/fetchCompany");
    this.$store.dispatch("employeeHasParent/fetchForeman");

    // this.$bvModal.show("project_form");
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
