<template>
  <div>
    <b-modal
      id="job_order_confirmation"
      ref="job_order_confirmation"
      :title="title_form"
      size="md"
      class="modal-custom"
      hide-footer
    >
      <b-row>
        <b-col cols>
          <span>
            <b>Proyek :</b>
          </span>
          <br />
          <span>{{projectName ? projectName : '-'}}</span>
        </b-col>
      </b-row>
      <br />
      <b-row>
        <b-col cols>
          <span>
            <b>Pekerjaan :</b>
          </span>
          <br />
          <span>{{jobName ? jobName : '-'}}</span>
        </b-col>
      </b-row>
      <br />
      <b-row>
        <b-col cols>
          <span>
            <b>Keterangan Pekerjaan :</b>
          </span>
          <br />
          <span>{{form.job_note ? form.job_note : '-'}}</span>
        </b-col>
      </b-row>
      <br />
      <b-row>
        <b-col cols>
          <span>
            <b>Kategori :</b>
          </span>
          <br />
          <span>{{categoryName ? categoryName : '-'}}</span>
        </b-col>
      </b-row>
      <br />
      <b-row>
        <b-col cols>
          <span>
            <b>Tingkat Kesulitan :</b>
          </span>
          <br />
          <span>{{jobLevelName ? jobLevelName : '-'}}</span>
        </b-col>
      </b-row>
      <br />
      <b-row>
        <b-col cols>
          <span>
            <b>Catatan :</b>
          </span>
          <br />
          <span>{{form.note ? form.note : '-'}}</span>
        </b-col>
      </b-row>
      <br />
      <b-row>
        <b-col cols>
          <span>
            <b>Jam Mulai :</b>
          </span>
          <br />
          <span>{{form.hour_start}}</span>
        </b-col>
        <b-col cols>
          <span>
            <b>Waktu Selesai :</b>
          </span>
          <br />
          <span>{{form.datetime_estimation_end_readable ? form.datetime_estimation_end_readable : "-"}}</span>
        </b-col>
      </b-row>

      <br />
      <b-row>
        <b-col cols>
          <span>
            <b>Gambar :</b>
          </span>
          <br />
          <span>
            <img :src="form.image_bit" class="show-image" v-if="form.image_bit != null" />
          </span>
        </b-col>
      </b-row>
      <br />
      <b-row>
        <b-col cols>
          <span>
            <b>Karyawan :</b>
          </span>
          <br />
          <EmployeeTableConfirmation />
        </b-col>
      </b-row>

      <br />
      <b-row>
        <b-col>
          <b-button variant="info" @click="onCloseModal()">Tutup</b-button>
          <b-button
            v-if="!getReadOnly()"
            style="float: right"
            variant="success"
            @click="onSend()"
            :disabled="is_loading || getIsDisabledBtnSend"
          >Kirim</b-button>
          <span v-if="is_loading" style="float: right">Loading...</span>
        </b-col>
      </b-row>
    </b-modal>
  </div>
</template>

<script src="../Script/formConfirmation.js"></script>

<style lang="scss" scoped>
.show-image {
  max-width: 200px;
  max-height: 150px;
}
</style>
