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
    <hr />
    <b-row>
      <b-col class="place-data">
        <template v-if="getLoadingData">
          <span>loading...</span>
        </template>
        <template v-else-if="getData.length > 0">
          <b-row v-for="(item, index) in getData" :key="index">
            <b-col class="place-item">
              <b-row>
                <b-col @click="onOpenAction(item)">
                  <b-row>
                    <b-col cols>
                      <h6>
                        <b>{{item.project_name}}</b>
                      </h6>
                    </b-col>
                    <b-col cols="4" style="text-align: end">
                      <span :class="`badge bg-${item.status_color}`">{{ item.status_readable }}</span>
                    </b-col>
                  </b-row>
                  <b-row>
                    <b-col cols>
                      <span>{{item.job_code}} - {{item.job_name}}</span>
                    </b-col>
                  </b-row>
                  <b-row>
                    <b-col cols>
                      <span>ket : {{ item.job_note != null ? onLimitSentence(item.job_note) : "-"}}</span>
                    </b-col>
                  </b-row>
                  <b-row class="place-content">
                    <b-col cols="6">
                      <span>
                        <b>Penilaian :</b>
                      </span>
                      <span>{{item.assessment_count}} / {{item.assessment_total}}</span>
                      <br />
                      <div>
                        <b-form-checkbox class="display-inline" value="true" disabled></b-form-checkbox>
                        <span>QC</span>
                      </div>
                      <div>
                        <b-form-checkbox class="display-inline" value="true" disabled></b-form-checkbox>
                        <span>Pengawas</span>
                      </div>
                    </b-col>
                    <b-col cols="6">
                      <span>
                        <b>Total :</b>
                        {{item.employee_total}}
                        <i class="bi bi-person"></i>
                      </span>
                      <br />
                      <span>
                        <b>Aktif :</b>
                        {{item.employee_active_total}}
                        <i
                          class="bi bi-person"
                        ></i>
                      </span>
                    </b-col>
                  </b-row>
                </b-col>
              </b-row>
            </b-col>
          </b-row>
        </template>
        <template v-else>
          <span>data kosong.</span>
        </template>
        <vue-bottom-sheet ref="myBottomSheet">
          <div class="flex flex-col">
            <!-- v-if="getFormStatus != 'pending'" -->
            <!-- <div class="action-item" @click="onAction('active', 'Mulai')">Mulai</div> -->
            <!-- <div class="action-item">{{getFormStatus}}</div> -->
            <div
              v-if="getFormStatus == 'active'"
              class="action-item"
              @click="onAction('finish', 'Selesai')"
            >Selesai</div>
            <div
              v-if="getFormStatus == 'active'"
              class="action-item"
              @click="onAction('pending', 'Tunda')"
            >Tunda</div>
            <div
              v-if="getConditionActionActive()"
              class="action-item"
              @click="onAction('active', 'Mulai Kembali')"
            >Mulai Kembali</div>
            <!-- v-if="getFormStatus != 'active'" -->
            <div
              v-if="getFormStatus == 'active'"
              class="action-item"
              @click="onAction('overtime', 'Lembur')"
            >Lembur</div>
            <div
              v-if="getFormStatus == 'overtime'"
              class="action-item"
              @click="onAction('overtime_finish', 'Lembur Selesai')"
            >Lembur Selesai</div>
            <div
              v-if="getFormStatus == 'finish'"
              class="action-item"
              @click="onAction('correction', 'Perbaikan')"
            >Perbaikan</div>
            <div
              v-if="getFormStatus == 'correction'"
              class="action-item"
              @click="onAction('correction_finish', 'Selesai Perbaikan')"
            >Perbaikan Selesai</div>
            <div class="action-item" @click="onEdit">Ubah</div>
            <div class="action-item" @click="onDetail">Detail</div>
            <!-- khusus untuk QC -->
            <div
              v-if="getFormStatus == 'active'"
              class="action-item"
              @click="onAction('assessment', 'Penilaian')"
            >Penilaian</div>
          </div>
          <div class="action-item-empty"></div>
          <div class="action-item-empty"></div>
          <div class="action-item-empty"></div>
        </vue-bottom-sheet>
      </b-col>
    </b-row>
    <FilterData />
  </div>
</template>

<script src="../Script/data.js"></script>

<style lang="scss" scoped>
.place-data {
  max-height: 500px;
  //max-height: 20%;
  overflow-y: scroll;
}
.place-data::-webkit-scrollbar {
  display: none;
}
.place-item {
  border-bottom: 1px solid #dbdfea;
  padding: 0.5rem;
  margin-bottom: 1rem;
}
.place-content {
  font-size: 15px;
  margin-top: 10px;
}
.action-item {
  padding: 25px 0px 25px 20px;
  border-bottom: 1px solid #dbdfea;
}
.action-item-empty {
  padding: 25px 0px 25px 20px;
}
.badge-success {
  padding: 0.115rem 0.5rem;
}
</style>
