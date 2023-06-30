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
        <b-row>
          <b-col>
            <template v-if="tab_name != 'status'">
              <FilterData />
            </template>
            <template v-else>
              <b-row style="height: 58px">
                <b-col cols></b-col>
              </b-row>
            </template>
          </b-col>
        </b-row>
        <br />
        <b-tabs content-class="mt-3">
          <b-tab title="Utama" @click="onChangeTab('main')" active>
            <Main />
          </b-tab>
          <b-tab title="Total" @click="onChangeTab('total')">
            <Total />
          </b-tab>
          <b-tab title="Status" @click="onChangeTab('status')">
            <Status />
          </b-tab>
        </b-tabs>
      </div>
    </div>
  </div>
</template>

<script>
import Main from "./main";
import Status from "../RosterStatus/rosterStatus";
import Total from "./total.vue";
import FilterData from "./filterData.vue";
export default {
  props: {
    user: String,
    baseUrl: String,
  },
  data() {
    return {
      title: "Roster",
      version: "v1.1",
      tab_name: "main",
    };
  },
  components: { Main, Status, Total, FilterData },
  mounted() {
    this.$store.commit("INSERT_BASE_URL", { base_url: this.baseUrl });
    this.$store.commit("INSERT_USER", { user: JSON.parse(this.user) });

    ["roster", "rosterStatus", "master"].map((item) => {
      this.$store.commit(`${item}/INSERT_BASE_URL`, {
        base_url: this.baseUrl,
      });
    });

    this.$store.dispatch("roster/fetchData");
    this.$store.dispatch("rosterStatus/fetchData");
    this.$store.dispatch("master/fetchPosition");
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
