<template>
  <div>
    <b-tabs content-class="mt-3">
      <b-tab title="Data" active>
        <b-row style="margin-top: 10px">
          <b-col cols>
            <b-button variant="info" size="sm" class @click="onFilter()">Filter</b-button>
          </b-col>
          <b-col cols style="align-item: right">
            <b-button
              v-if="getCan('tambah job order')"
              variant="success"
              size="sm"
              class="float-end"
              @click="onCreate()"
            >Tambah</b-button>
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
                          <h5>
                            <b>{{item.project_name}}</b>
                          </h5>
                        </b-col>
                        <b-col cols="4" style="text-align: end">
                          <span :class="`badge bg-${item.status_color}`">{{ item.status_readable }}</span>
                        </b-col>
                      </b-row>
                      <b-row>
                        <b-col cols>
                          <span>{{item.job_code}} {{item.job_code ? '-' : null}} {{item.job_name}}</span>
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
                          <!-- <div>
                        <b-form-checkbox class="display-inline" value="true" disabled></b-form-checkbox>
                        <span>QC</span>
                      </div>
                      <div>
                        <b-form-checkbox class="display-inline" value="true" disabled></b-form-checkbox>
                        <span>Pengawas</span>
                          </div>-->
                          <div v-for="(assessment, key) in item.job_order_assessments" :key="key">
                            <b-form-checkbox class="display-inline" v-model="isChecked" disabled></b-form-checkbox>
                            <span>{{assessment.group_name}}</span>
                          </div>
                        </b-col>
                        <b-col cols="6">
                          <span>
                            <b>Karyawan</b>
                          </span>
                          :
                          <span>
                            <!-- <i class="bi bi-person"></i> -->
                            {{item.employee_active_total}} /
                            {{item.employee_total}}
                          </span>
                          <br />
                          <span>
                            <!-- <b>{{item.creator_group_name}}</b> -->
                            <b>Dibuat Oleh :</b>
                          </span>
                          <br />
                          <span>{{item.creator_name}}</span>
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

            <b-modal id="action_list" ref="action_list" title="Tombol Aksi" size="md" hide-footer>
              <div class="flex flex-col">
                <!-- v-if="getFormStatus != 'pending'" -->
                <!-- <div class="action-item" @click="onAction('active', 'Mulai')">Mulai</div> -->
                <!-- <div class="action-item">{{getFormStatus}}</div> -->
                <template v-if="getUserGroupName != 'Quality Control'">
                  <!-- <div
                v-if="getFormStatus == 'active'"
                class="action-item"
                @click="onAction('finish', 'Selesai')"
                  >Selesai</div>-->
                  <div
                    v-if="getConditionPending()"
                    class="action-item"
                    @click="onAction('pending', 'Tunda')"
                  >Tunda</div>
                  <div
                    v-if="getFormStatus == 'pending'"
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
                </template>
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
                <!-- khusus untuk QC -->
                <div
                  v-if="getFormStatus == 'active' || getFormStatus == 'assessment'"
                  class="action-item"
                  @click="onActionAssessment('assessment', 'Penilaian')"
                >Penilaian</div>
                <div class="action-item" @click="onShowEmployee()">Karyawan</div>
                <div v-if="getConditionEdit()" class="action-item" @click="onEdit">Ubah</div>
                <div class="action-item" @click="onRead">Lihat</div>
                <div
                  v-if="this.getForm.created_by == this.getUserId"
                  class="action-item"
                  style="color: red;"
                  @click="onDelete()"
                >Hapus</div>
              </div>
            </b-modal>
          </b-col>
        </b-row>
      </b-tab>
      <!-- <b-tab title="Karyawan">
        <h1>karyawan</h1>
      </b-tab>-->
    </b-tabs>

    <FilterData />
    <EmployeeHasParent />
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
