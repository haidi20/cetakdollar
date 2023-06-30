<!-- STORE INI DIBUTUHKAN JIKA ADA KEBUTUHAN DATA KEPALA KONTRAKTOR LEBIH KOMPLEKS -->
<template>
  <div>
    <b-row>
      <b-col col md="6">
        <!-- <Form v-if="is_show_form" /> -->
        <Form />
      </b-col>
      <!-- <b-col col :md="getWidthTable()">
        <Table />
      </b-col>-->
    </b-row>
  </div>
</template>

<script>
// partials
import Form from "./form";
import Table from "./table";

export default {
  data() {
    return {
      //
    };
  },
  components: {
    Form,
    Table,
  },
  mounted() {
    // this.$bvModal.show("finance_item");
  },
  computed: {
    is_show_form: {
      get() {
        return this.$store.state.contractorHasParent.form.is_show_form;
      },
      set(value) {
        this.$store.commit("contractorHasParent/UPDATE_IS_SHOW_FORM", {
          value: value,
        });
      },
    },
  },
  watch: {
    //
  },
  methods: {
    getWidthTable() {
      return this.is_show_form ? 9 : 12;
    },
  },
};
</script>

<style lang="scss" scoped>
.cursor-pointer {
  cursor: pointer;
}
</style>

<style lang="scss" scoped>
</style>
