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
          <!-- <b-form-group label="Bulan" label-for="month" class="place_filter_table">
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
          >Kirim</b-button>-->
          <b-button
            v-if="getCan('ekspor proyek')"
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
          <b-button
            v-if="getCan('tambah proyek') && getParentType != 'create'"
            variant="success"
            class="place_filter_table ml-4"
            size="sm"
            @click="onCreate()"
          >
            <i class="fas fa-plus"></i>
            Tambah
          </b-button>
        </b-col>
      </template>
      <template v-slot:tbody="{ filteredData }">
        <b-tr v-for="(item, index) in filteredData" :key="index">
          <b-td @click="onAction()">
            <ButtonAction class="cursor-pointer" type="click">
              <template v-slot:list_detail_button>
                <a
                  href="#"
                  v-if="getCan('lihat proyek')"
                  @click="onDetail(item, index)"
                >Informasi Lengkap</a>
                <a href="#" v-if="getCan('ubah proyek')" @click="onEdit(item)">Ubah</a>
                <a href="#" v-if="getCan('hapus proyek')" @click="onDelete(item)">Hapus</a>
              </template>
            </ButtonAction>
            <!-- <b-button variant="primary" size="sm" @click="onDetail(item)">Detail</b-button>
            <b-button variant="warning" size="sm" @click="onShowJobOrder(item)">Job Order</b-button>
            <b-button variant="info" size="sm" @click="onEdit(item)">Ubah</b-button>
            <b-button variant="danger" size="sm" @click="onDelete(item)">Hapus</b-button>-->
            <!-- <b-dropdown size="sm" left split text="Tombol" varian="info">
              <b-list-group>
                <b-list-group-item class="cursor-pointer">Semua Informasi</b-list-group-item>
                <b-list-group-item class="cursor-pointer">Job Order</b-list-group-item>
                <b-list-group-item class="cursor-pointer">Ubah</b-list-group-item>
                <b-list-group-item class="cursor-pointer">Hapus</b-list-group-item>
              </b-list-group>
            </b-dropdown>-->
          </b-td>
          <b-td>{{ item.name }}</b-td>
          <b-td>{{ item.location_name }}</b-td>
          <b-td>{{ item.date_end_readable }}</b-td>
          <b-td>{{ item.day_duration }} {{item.day_duration ? ' Hari' : null}}</b-td>
          <b-td>
            <template v-if="item.job_order_total > 0">
              {{ item.job_order_finish_total }}
              \ {{ item.job_order_total }}
            </template>
            <template v-else>
              <span>belum ada job order</span>
            </template>
          </b-td>
        </b-tr>
      </template>
    </DatatableClient>
  </div>
</template>

<script src="../Script/table.js"></script>

<style lang="scss" scoped>
.place_filter_table {
  align-items: self-end;
  margin-bottom: 0;
  display: inline-block;
}
</style>
