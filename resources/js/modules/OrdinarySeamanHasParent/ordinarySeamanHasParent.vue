<!-- STORE INI DIBUTUHKAN JIKA ADA KEBUTUHAN DATA KEPALA KONTRAKTOR LEBIH KOMPLEKS -->
<template>
  <div>
    <b-row>
      <b-col col md="6">
        <Form />
      </b-col>
    </b-row>
  </div>
</template>

<script>
import VueSelect from "vue-select";
import DatePicker from "vue2-datepicker";

// partials
import Form from "./form";

export default {
  data() {
    return {
      //
    };
  },
  components: {
    Form,
  },
  mounted() {
    // this.$bvModal.show("finance_item");
  },
  computed: {
    //
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
