<template>
  <div style="height: 100%">
    <b-row style="height: 100%">
      <b-col v-if="!getIsMobile" col md="3" class></b-col>
      <b-col col :md="getIsMobile ? 12 : 6" id="main-content">
        <h4>{{getTitle}}</h4>
        <div style="margin-top: 15px">
          <template v-if="!getIsActiveForm">
            <Data />
          </template>
          <template v-else>
            <template v-if="getConditionForm()">
              <Form />
            </template>
            <template v-else>
              <FormAction />
            </template>
          </template>
        </div>
      </b-col>
      <b-col v-if="!getIsMobile" col md="3" class></b-col>
    </b-row>
  </div>
</template>

<script>
import { isMobile } from "../../../utils";
// import VueBottomSheet from "@webzlodimir/vue-bottom-sheet";
import Data from "./data";
import Form from "./form";
import FormAction from "./formAction.vue";

export default {
  props: {
    user: String,
    baseUrl: String,
    statuses: String,
  },
  data() {
    return {
      //
    };
  },
  components: {
    Data,
    Form,
    FormAction,
    // VueBottomSheet,
  },
  mounted() {
    const user = JSON.parse(this.user);
    this.$store.commit("INSERT_BASE_URL", { base_url: this.baseUrl });
    this.$store.commit("INSERT_USER", { user });

    ["jobOrder", "project", "employeeHasParent", "master"].map((item) => {
      this.$store.commit(`${item}/INSERT_BASE_URL`, {
        base_url: this.baseUrl,
      });
    });

    this.$store.commit(`employeeHasParent/INSERT_OPTION_STATUS`, {
      statuses: JSON.parse(this.statuses),
    });

    this.$store.dispatch("fetchPermission");
    this.$store.dispatch("master/fetchJob");
    // this.$store.dispatch("jobOrder/fetchData");
    this.$store.dispatch("master/fetchPosition");
    this.$store.dispatch("employeeHasParent/fetchOption");
    this.$store.dispatch("project/fetchDataBaseDateEnd", { user_id: user.id });

    this.$store.commit("employeeHasParent/INSERT_FORM_PARENT_NAME", {
      parent_name: "job_order",
    });
  },
  computed: {
    getIsMobile() {
      return isMobile();
    },
    getIsActiveForm() {
      return this.$store.state.jobOrder.is_active_form;
    },
    getTitle() {
      return this.$store.state.jobOrder.form.form_title;
    },
    form() {
      return this.$store.state.jobOrder.form;
    },
  },
  methods: {
    onClose() {
      this.$refs.myBottomSheet.close();
    },
    getConditionForm() {
      return (
        this.form.form_kind == "edit" ||
        this.form.form_kind == "read" ||
        this.form.form_kind == "create" ||
        this.form.form_kind == null
      );
    },
  },
};
</script>

<style lang="scss" scoped>
#main-content {
  background-color: white;
  //max-height: 90%;
  padding-top: 20px;
  border-radius: 30px;
  box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1),
    0 4px 6px -2px rgba(0, 0, 0, 0.05);
}
</style>
