<template>
  <div>
    <b-row style="margin-top: 10px">
      <b-col cols>
        <b-button variant="info" size="sm" class @click="onFilter()">Filter</b-button>
      </b-col>
      <b-col cols style="align-item: right">
        <b-button variant="success" size="sm" class="float-end" @click="onCreate()">Tambah</b-button>
      </b-col>
    </b-row>
    <br />
    <b-row>
      <b-col class="place-data">
        <template v-if="getLoadingTable">
          <b-tr>
            <b-td>Loading...</b-td>
          </b-tr>
        </template>
        <template v-else-if="getData.length > 0">
          <b-row v-for="(data, index) in getData" :key="index">
            <b-col class="place-item" @click="onOpenAction(data)">
              <b-row>
                <b-col cols>
                  <h5>{{data.employee_name}} - {{data.position_name}}</h5>
                </b-col>
              </b-row>
              <b-row>
                <b-col cols="6" class="flex flex-col">
                  <span>
                    <b>Jumlah Kasbon :</b>
                  </span>
                  <span>{{data.loan_amount_readable}}</span>

                  <span class="title-item">
                    <b>Keterangan :</b>
                  </span>
                  <span>{{data.reason}}</span>
                  <template v-if="data.approval_status == 'accept'">
                    <span class="title-item">
                      <b>Dibuat oleh :</b>
                    </span>
                    <span>{{data.creator_name}}</span>
                  </template>
                  <!-- <span class="title-item">Sudah Terbayarkan :</span>
                <span>Rp. 500.000</span>
                <span class="title-item">Belum Terbayarkan :</span>
                  <span>Rp. 1.000.000</span>-->
                </b-col>
                <b-col cols="6" class="flex flex-col">
                  <!-- <span>
                    <b>Status :</b>
                  </span>-->
                  <span
                    :class="`badge bg-${data.approval_color}`"
                    style="width:6rem; align-self: end;"
                  >{{data.approval_status_readable}}</span>
                  <template v-if="data.approval_status == 'reject'">
                    <span class="title-item">
                      <b>Catatan :</b>
                    </span>
                    <span>{{data.note}}</span>
                  </template>
                  <template v-if="data.approval_status == 'accept'">
                    <span class="title-item">
                      <b>Durasi :</b>
                    </span>
                    <span>{{data.duration}} Bulan</span>
                    <span class="title-item">
                      <b>Potongan Setiap Bulan :</b>
                    </span>
                    <span>{{data.monthly_deduction_readable}}</span>
                  </template>
                </b-col>
              </b-row>
            </b-col>
          </b-row>
        </template>
        <template v-else>
          <b-tr>
            <b-td>Data Kosong</b-td>
          </b-tr>
        </template>
        <vue-bottom-sheet ref="myBottomSheet" max-height="30%">
          <div class="flex flex-col">
            <div class="action-item" @click="onEdit">Ubah</div>
            <div class="action-item" @click="onDelete">Hapus</div>
            <div class="action-item"></div>
          </div>
        </vue-bottom-sheet>
      </b-col>
    </b-row>
    <FilterData />
    <Form />
  </div>
</template>

<script src="../Script/data.js"></script>

<style lang="scss" scoped>
.place-data {
  max-height: 500px;
  overflow-y: scroll;
}
.place-data::-webkit-scrollbar {
  display: none;
}
.place-item {
  border-bottom: 1px solid #dbdfea;
  padding: 0.5rem;
}
.action-item {
  padding: 25px 0px 25px 20px;
  border-bottom: 1px solid #dbdfea;
}
.title-item {
  margin-top: 10px;
}
</style>
