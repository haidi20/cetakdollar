<template>
  <div>
    <b-row>
      <b-col cols>
        <b-form-group label="Tanggal" label-for="Tanggal" class>
          <DatePicker
            id="date"
            v-model="form.date"
            format="YYYY-MM-DD"
            type="date"
            placeholder="pilih tanggal"
            :disabled="getConditionDisableDate()"
          />
        </b-form-group>
      </b-col>
      <b-col cols>
        <b-form-group label="Jam" label-for="hour" class>
          <!-- get data awal lembur dari pengaturan -->
          <input type="time" v-model="form.hour" id="hour" name="hour" class="form-control" />
        </b-form-group>
      </b-col>
    </b-row>
    <b-row v-if="getConditionImage()">
      <b-col cols>
        <b-form-group label="Masukkan Foto" label-for="image" class>
          <b-form-file id="image" v-model="form.image"></b-form-file>
        </b-form-group>
      </b-col>
    </b-row>
    <b-row v-if="getFormKind == 'overtime'">
      <b-col col sm="6">
        <b-form-group label="Pilih Karyawan" class>
          <b-button variant="success" @click="onShowEmployee()">Data Karyawan</b-button>
        </b-form-group>
      </b-col>
    </b-row>
    <b-row>
      <b-col cols>
        <b-form-group :label="getLabelNote()" label-for="note" class>
          <b-form-input
            type="text"
            v-model="form.status_note"
            id="note"
            name="note"
            class="form-control"
          />
        </b-form-group>
      </b-col>
    </b-row>
    <b-row>
      <b-col>
        <b-button variant="info" @click="onCloseModal()">Tutup</b-button>
        <b-button
          style="float: right"
          variant="success"
          @click="onSend()"
          :disabled="is_loading"
        >Simpan</b-button>
        <span v-if="is_loading" style="float: right">Loading...</span>
      </b-col>
    </b-row>
    <EmployeeHasParent />
  </div>
</template>

<script src="../Script/formAction.js"></script>

<style lang="scss" scoped>
</style>
