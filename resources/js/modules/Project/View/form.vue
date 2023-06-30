<template>
  <div>
    <b-row>
      <b-col cols>
        <b-form-group label="Nama Proyek" label-for="name" class>
          <b-form-input
            v-model="form.name"
            id="name"
            name="name"
            autocomplete="off"
            :disabled="getReadOnly()"
          ></b-form-input>
        </b-form-group>
      </b-col>
      <b-col cols>
        <b-form-group label="Kapal" label-for="barge_id" class>
          <VueSelect
            id="barge_id"
            class="cursor-pointer"
            v-model="form.barge_id"
            placeholder="Pilih Kapal"
            :options="getOptionBarges"
            :reduce="(data) => data.id"
            label="name"
            searchable
            style="min-width: 180px"
            :disabled="getReadOnly()"
          />
        </b-form-group>
      </b-col>
      <!-- <b-col cols>
        <b-form-group label="Jenis Pekerjaan" label-for="work_type" class>
          <VueSelect
            id="work_type"
            class="cursor-pointer"
            v-model="form.work_type"
            placeholder="Pilih Jenis Pekerjaan"
            :options="getOptionJobs"
            :reduce="(data) => data.id"
            label="name"
            searchable
            style="min-width: 180px"
          />
        </b-form-group>
      </b-col>-->
    </b-row>
    <b-row>
      <b-col cols>
        <b-form-group label="Jenis Proyek" label-for="type" class>
          <VueSelect
            id="type"
            class="cursor-pointer"
            v-model="form.type"
            placeholder="Pilih Jenis Proyek"
            :options="getOptionTypes"
            :reduce="(data) => data.id"
            label="name"
            searchable
            style="min-width: 180px"
            :disabled="getReadOnly()"
          />
        </b-form-group>
      </b-col>
      <b-col cols>
        <b-form-group label="Lokasi" label-for="location_id" class>
          <VueSelect
            id="location_id"
            class="cursor-pointer"
            v-model="form.location_id"
            placeholder="Pilih Lokasi"
            :options="getOptionLocations"
            :reduce="(data) => data.id"
            label="name"
            searchable
            style="min-width: 180px"
            :disabled="getReadOnly()"
          />
        </b-form-group>
      </b-col>
      <!-- <b-col cols>
        <b-form-group label="Perusahaan" label-for="company_id" class>
          <VueSelect
            id="company_id"
            class="cursor-pointer"
            v-model="form.company_id"
            placeholder="Pilih Perusahaan"
            :options="getOptionCompanies"
            :reduce="(data) => data.id"
            label="name"
            searchable
            style="min-width: 180px"
            :disabled="getReadOnly()"
          />
        </b-form-group>
      </b-col>-->
    </b-row>
    <b-row>
      <b-col cols>
        <b-form-group label="Pengawas" label-for="foreman_id" class>
          <VueSelect
            id="foreman_id"
            class="cursor-pointer"
            v-model="form.foreman_id"
            placeholder="Pilih Pengawas"
            :options="getOptionForemans"
            :reduce="(data) => data.id"
            label="name"
            searchable
            style="min-width: 180px"
            :disabled="getReadOnly()"
          />
        </b-form-group>
      </b-col>
      <b-col cols>
        <b-form-group label="Tanggal Selesai" label-for="date_end">
          <DatePicker
            id="date_end"
            v-model="date_end"
            format="YYYY-MM-DD"
            type="date"
            style="width: 100%"
            :disabled-date="(date, currentValue) => disabledDate(date, currentValue)"
            :disabled="getReadOnly()"
          />
        </b-form-group>
      </b-col>
      <b-col cols>
        <b-form-group label="Lama Pengerjaan" label-for="date_end">
          <span>{{form.day_duration}} Hari</span>
        </b-form-group>
      </b-col>
    </b-row>
    <b-row>
      <b-col cols>
        <b-form-group label="Biaya Proyek" label-for="price" class>
          <input
            type="text"
            class="form-control"
            v-model="price"
            id="price"
            name="price"
            autocomplete="off"
            :disabled="getReadOnly()"
            @keypress="onNumberOnly"
          />
        </b-form-group>
      </b-col>
      <b-col cols>
        <b-form-group label="DP (Down Payment) " label-for="down_payment" class>
          <b-form-input
            v-model="down_payment"
            id="down_payment"
            name="down_payment"
            autocomplete="off"
            :disabled="getReadOnly()"
            @keypress="onNumberOnly"
          ></b-form-input>
        </b-form-group>
      </b-col>
      <b-col cols>
        <b-form-group label="Sisa Yang Dibayarkan" label-for="remaining_payment" class>
          <b-form-input
            v-model="form.remaining_payment_readable"
            id="remaining_payment"
            name="remaining_payment"
            autocomplete="off"
            disabled
          ></b-form-input>
        </b-form-group>
      </b-col>
    </b-row>
    <b-row>
      <b-col col md="8">
        <b-form-group label="Catatan" label-for="note" class>
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
  </div>
</template>

<script src="../Script/form.js"></script>

<style lang="scss" scoped>
</style>
