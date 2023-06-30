<template>
  <div>
    <!-- <b-row>
      <b-col cols>
        <b-form-group label="Jabatan" label-for="position_id" class>
          <VueSelect
            id="position_id"
            class="cursor-pointer"
            v-model="form.position_id"
            placeholder="Pilih Jabatan"
            :options="getOptionPositions"
            :reduce="(data) => data.id"
            label="name"
            searchable
            style="min-width: 180px"
          />
        </b-form-group>
      </b-col>
    </b-row>-->
    <template v-if="form.form_type == 'create' || form.form_type == 'edit'">
      <b-row>
        <b-col cols>
          <b-form-group label="Karyawan" label-for="employee_id" class>
            <VueSelect
              id="employee_id"
              class="cursor-pointer"
              v-model="form.employee_id"
              placeholder="Pilih Karyawan"
              :options="getOptionEmployees"
              :reduce="(data) => data.id"
              label="name_and_position"
              searchable
              style="min-width: 180px"
            />
          </b-form-group>
        </b-col>
      </b-row>
      <b-row>
        <b-col cols style="text-align: right">
          <b-button variant="success" @click="onChoose()">Pilih</b-button>
        </b-col>
      </b-row>
    </template>
    <template v-if="getConditionTime()">
      <b-row>
        <b-col cols>
          <b-form-group label="Tanggal Selesai Lembur" label-for="Tanggal" class>
            <DatePicker
              id="date"
              v-model="form.date"
              format="YYYY-MM-DD"
              type="date"
              placeholder="pilih tanggal"
            />
          </b-form-group>
        </b-col>
      </b-row>
      <b-row>
        <b-col cols="8">
          <b-form-group label="Jam Selesai Lembur" label-for="hour" class>
            <input type="time" class="form-control" v-model="form.hour" id="hour" name="hour" />
          </b-form-group>
        </b-col>
      </b-row>
    </template>
  </div>
</template>

<script src="../Script/formMobile.js"></script>

<style lang="scss" scoped>
</style>
