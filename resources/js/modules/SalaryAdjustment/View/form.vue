<template>
  <div>
    <b-modal
      id="salary_adjustment_form"
      ref="salary_adjustment_form"
      :title="getTitleForm"
      size="md"
      class="modal-custom"
      hide-footer
    >
      <b-row>
        <b-col cols>
          <b-form-group label="Nama " label-for="name" class>
            <b-form-input v-model="form.name" id="name" name="name" :disabled="getReadOnly()"></b-form-input>
          </b-form-group>
        </b-col>
        <b-col cols>
          <b-form-group label="Pilih Jenis Waktu" label-for="type_time" class>
            <VueSelect
              id="type_time"
              class="cursor-pointer"
              v-model="form.type_time"
              placeholder="Pilih Kategori"
              :options="getOptionTypeTimes"
              :reduce="(data) => data.id"
              label="name"
              searchable
              style="min-width: 180px"
              :disabled="getReadOnly()"
            />
          </b-form-group>
        </b-col>
      </b-row>
      <b-row v-if="form.type_time == 'base_time'">
        <b-col cols>
          <label for="scope_id" style="display:inline-block">
            <span>Pilih Bulan</span>
          </label>
          <DatePicker
            id="month_start"
            v-model="form.month_start"
            format="YYYY-MM"
            type="month"
            placeholder="pilih bulan"
            :disabled="getReadOnly()"
          />
        </b-col>
        <b-col cols>
          <label for="scope_id">
            <b-form-checkbox
              style="display: inline"
              v-model="form.is_month_end"
              :disabled="getReadOnly()"
            ></b-form-checkbox>
            <span @click="onActiveDateEnd">Lebih dari 1 bulan</span>
          </label>
          <DatePicker
            v-if="form.is_month_end"
            id="month_end"
            v-model="form.month_end"
            format="YYYY-MM"
            type="month"
            placeholder="pilih bulan"
            :disabled="getReadOnly()"
          />
        </b-col>
      </b-row>
      <br />
      <b-row>
        <b-col cols="8">
          <b-form-group label="Pilih Jenis Uang" label-for="type_amount" class>
            <VueSelect
              id="type_amount"
              class="cursor-pointer"
              v-model="form.type_amount"
              :options="getOptionTypeAmount"
              :reduce="(data) => data.id"
              label="name"
              searchable
              style="min-width: 180px"
              :disabled="getReadOnly()"
            />
          </b-form-group>
        </b-col>
        <b-col cols="4">
          <b-form-group label="Nilai" label-for="amount" class>
            <b-form-input
              v-model="amount"
              id="amount"
              name="amount"
              @keypress="onReplaceAmount($event)"
              :disabled="getReadOnly()"
            ></b-form-input>
            <span class="note">*masukkan angka saja</span>
          </b-form-group>
        </b-col>
      </b-row>
      <b-row>
        <b-col cols="6">
          <label for="type_adjustment" style="display:inline-block; ">
            <!-- <span>penambahan / pengurangan</span> -->
            <span>Jenis</span>
          </label>
          <VueSelect
            id="type_adjustment"
            class="cursor-pointer"
            v-model="form.type_adjustment"
            placeholder="Pilih Jenis Penyesuaian"
            :options="getOptionTypeAdjustments"
            :reduce="(data) => data.id"
            label="name"
            searchable
            style="min-width: 180px"
            :disabled="getReadOnly()"
          />
        </b-col>
        <b-col cols="6">
          <b-form-group label="Keterangan" label-for="note" class>
            <b-form-input
              v-model="form.note"
              id="note"
              name="note"
              autocomplete="off"
              :disabled="getReadOnly()"
            ></b-form-input>
          </b-form-group>
        </b-col>
      </b-row>
      <b-row>
        <b-col cols="6">
          <b-form-checkbox
            id="is_thr"
            v-model="form.is_thr"
            name="is_thr"
            :value="true"
            :unchecked-value="false"
          >
            <span style="margin-left: 10px">Tunjangan Hari raya</span>
          </b-form-checkbox>
        </b-col>
        <b-col cols="6">
          <b-form-group label="Pilih Karyawan" label-for="image" class>
            <b-button variant="success" @click="onShowEmployee()">Data Karyawan</b-button>
          </b-form-group>
        </b-col>
      </b-row>
      <br />
      <b-row class="float-end">
        <b-col>
          <b-button variant="info" @click="onCloseModal()">Tutup</b-button>
          <b-button
            v-if="
                !getReadOnly()
            "
            variant="success"
            @click="onSend()"
            class="ml-8"
            :disabled="is_loading"
          >Simpan</b-button>
          <span v-if="is_loading">Loading...</span>
        </b-col>
      </b-row>
    </b-modal>
    <EmployeeHasParent />
  </div>
</template>

<script src="../Script/form.js"></script>

<style lang="scss" scoped>
.note {
  color: red;
  font-size: 12px;
}
</style>
