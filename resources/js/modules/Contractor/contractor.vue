<template>
  <div>
    <b-modal
      id="contractor_form"
      ref="contractor_form"
      :title="getTitleForm"
      size="lg"
      class="modal-custom"
      hide-footer
    >
      <Form />
      <br />
      <Table />
      <br />
      <b-row>
        <b-col>
          <b-button variant="info" @click="onCloseModal()">Tutup</b-button>
        </b-col>
      </b-row>
    </b-modal>
  </div>
</template>

<script>
import Table from "./table";
import Form from "./form";
export default {
  data() {
    return {
      getTitleForm: "Data Kepala Pemborong",
    };
  },
  components: {
    Form,
    Table,
  },
  methods: {
    onCloseModal() {
      this.$bvModal.hide("contractor_form");
    },
  },
};
</script>

<style lang="scss" scoped>
</style>
