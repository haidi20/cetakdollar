<template>
  <div>
    <DatatableClient
      :data="getData"
      :columns="columns"
      :options="options"
      nameStore="salaryAdjustment"
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
              range
            />
          </b-form-group>
          <b-button class="place_filter_table" variant="success" size="sm" @click="onFilter()">Kirim</b-button>
          <b-button
            class="place_filter_table ml-4"
            variant="success"
            size="sm"
            @click="onExport()"
            :disabled="is_loading_export"
          >
            <i class="fas fa-file-excel"></i>
            Export
          </b-button>
          <span v-if="is_loading_export">Loading...</span>
        </b-col>
      </template>
      <template v-slot:tbody="{ filteredData }">
        <b-tr v-for="(item, index) in filteredData" :key="index">
          <b-td v-for="column in getColumns()" :key="column.label">{{ item[column.field] }}</b-td>
          <b-td>
            <!-- <a href="#" @click="onDelete(item)" class="fomr-control">Hapus</a> -->
            <b-button variant="primary" size="sm" @click="onAction('detail', item)">Detail</b-button>
            <b-button variant="info" size="sm" @click="onAction('edit', item)">Ubah</b-button>
            <b-button
              v-if="item.created_by == getUserId"
              variant="danger"
              size="sm"
              @click="onDelete(item)"
            >Hapus</b-button>
          </b-td>
        </b-tr>
      </template>
    </DatatableClient>
  </div>
</template>

<script src="../Script/table.js"></script>

<style lang="scss" scoped>
</style>
