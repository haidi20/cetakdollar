<template>
  <div>
    <DatatableClient
      :data="getData"
      :columns="columns"
      :options="options"
      nameStore="project"
      nameLoading="table"
      :filter="true"
      :footer="false"
      bordered
    >
      <template v-slot:filter>
        <b-col cols>
          <b-form-group label="Bulan" label-for="month" class="place_filter_table">
            <DatePicker
              id="month"
              v-model="params.month"
              format="YYYY-MM"
              type="month"
              placeholder="pilih bulan"
            />
          </b-form-group>
          <b-button
            class="place_filter_table"
            variant="success"
            size="sm"
            @click="onFilter()"
            :disabled="getIsLoadingData"
          >Kirim</b-button>
        </b-col>
      </template>
      <template v-slot:tbody="{ filteredData }">
        <b-tr
          v-for="(item, index) in filteredData"
          :key="index"
          @click="onChoose(item.id, index)"
          class="cursor-pointer"
        >
          <b-td>
            <label for="scope_id">
              <b-form-checkbox style="display: inline" v-model="item.is_selected"></b-form-checkbox>
            </label>
          </b-td>
          <b-td>{{ item.name }}</b-td>
          <b-td>{{ item.company_name }}</b-td>
          <b-td>{{ item.date_end_readable }}</b-td>
          <b-td>{{ item.day_duration }} Hari</b-td>
        </b-tr>
      </template>
    </DatatableClient>
  </div>
</template>

<script src="../Script/tableHasParent.js"></script>

<style lang="scss" scoped>
.place_filter_table {
  align-items: self-end;
  margin-bottom: 0;
  display: inline-block;
}
</style>
