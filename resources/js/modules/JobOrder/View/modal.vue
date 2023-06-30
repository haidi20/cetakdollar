<template>
  <div>
    <b-modal
      id="job_order_modal"
      ref="job_order_modal"
      :title="title_form"
      size="md"
      class="modal-custom"
      hide-footer
    >
      <FormRead />
      <br />
      <b-row class="float-right">
        <b-col>
          <b-button variant="info" @click="onCloseModal()">Tutup</b-button>
        </b-col>
      </b-row>
    </b-modal>
  </div>
</template>

<script>
import FormRead from "./formRead";

export default {
  data() {
    return {
      title_form: "Data Job Order",
    };
  },
  components: {
    FormRead,
  },
  methods: {
    onCloseModal() {
      this.$bvModal.hide("job_order_modal");
    },
  },
};
</script>

<style lang="scss" scoped>
</style>
