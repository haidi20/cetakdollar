<template>
  <div>
    <b-row>
      <b-col cols="4">
        <b-form-group label="Pilih Karyawan Berdasarkan" label-for="employee_base" class>
          <VueSelect
            id="employee_base"
            class="cursor-pointer"
            v-model="employee_base"
            :options="getOptionEmplyeeBases"
            :reduce="(data) => data.id"
            label="name"
            searchable
            style="min-width: 180px"
          />
        </b-form-group>
      </b-col>
      <b-col cols="8">
        <b-form-group
          v-if="form.employee_base == 'choose_employee'"
          label="Karyawan"
          label-for="employee_id"
          class
        >
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
        <b-form-group
          v-if="form.employee_base == 'position'"
          label="Jabatan"
          label-for="position_id"
          class
        >
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
        <!-- <b-form-group
          v-if="form.employee_base == 'job_order'"
          label="Job Order"
          label-for="job_order_id"
          class
        >
          <VueSelect
            id="job_order_id"
            class="cursor-pointer"
            v-model="form.job_order_id"
            placeholder="Pilih Job Order"
            :options="getOptionJobOrders"
            :reduce="(data) => data.id"
            label="name"
            searchable
            style="min-width: 180px"
          />
        </b-form-group>-->
      </b-col>
    </b-row>
    <b-row v-if="form.employee_base == 'choose_employee'">
      <b-col cols style="text-align: right">
        <b-button variant="success" @click="onChoose()">Pilih</b-button>
      </b-col>
    </b-row>
  </div>
</template>

<script src="../Script/formDesktop.js"></script>

<style lang="scss" scoped>
</style>
