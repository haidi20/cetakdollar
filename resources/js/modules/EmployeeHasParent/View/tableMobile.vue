<template>
  <div>
    <b-row style="margin-top: 10px">
      <b-col cols>
        <span>
          Total :
          {{getData.length}}
        </span>
      </b-col>
    </b-row>
    <b-row>
      <b-col cols>
        <input
          v-model="params.search"
          @input="onSearch"
          type="text"
          placeholder="search..."
          style="width: 100%"
          class="form-control"
        />
      </b-col>
    </b-row>
    <br />
    <b-row>
      <b-col class="place-data">
        <template v-if="getFilteredData.length > 0">
          <b-row
            v-for="(item, index) in getFilteredData"
            :key="index"
            @click="onOpenAction(item, index)"
          >
            <b-col class="place-item">
              <b-row>
                <b-col cols>
                  <h6>{{item.employee_name}} ({{item.position_name}})</h6>
                  <span v-if="item.is_active != null">Aktif: {{item.is_active}}</span>
                  <!-- <span>{{item?.status_data}}</span> -->
                </b-col>
                <b-col cols="4" style="text-align: end">
                  <span :class="`badge bg-${item.status_color}`">{{ item.status_readable }}</span>
                </b-col>
              </b-row>
              <b-row v-if="getConditionAddInformation(item)">
                <b-col cols>
                  <span>Nama Proyek :</span>
                  <br />
                  <span>{{item?.project_name}}</span>
                </b-col>
                <b-col cols>
                  <span>Nama Pengawas :</span>
                  <span>{{item?.creator_name}}</span>
                </b-col>
              </b-row>
              <!-- <b-row>
              <b-col>
                <span>Masuk : 08:10</span>
              </b-col>
              </b-row>-->
              <!--  -->
            </b-col>
          </b-row>
        </template>
        <template v-else>
          <b-row>
            <b-col class="place-item">Data Kosong.</b-col>
          </b-row>
        </template>
      </b-col>
    </b-row>
    <b-modal id="action_list_employee" ref="action_list" title="Tombol Aksi" size="md" hide-footer>
      <div class="flex flex-col">
        <div class="action-item">
          <h5>{{getForm.employee_name}} - {{getForm.position_name}}</h5>
        </div>
        <div
          v-if="getConditionActionPending()"
          class="action-item"
          @click="onAction('pending', 'Tunda')"
        >tunda</div>
        <div
          v-if="getConditionActionActive()"
          class="action-item"
          @click="onAction('active', 'Aktif Kembali')"
        >Aktif Kembali</div>
        <!-- <div
          v-if="getConditionActionFinish()"
          class="action-item"
          @click="onAction('finish', 'Selesai')"
        >selesai</div>-->
        <div
          v-if="getConditionOvertime()"
          class="action-item"
          @click="onAction('overtime', 'Lembur')"
        >lembur</div>
        <div
          v-if="getConditionActionOvertimeFinish()"
          class="action-item"
          @click="onAction('overtime_finish', 'Selesai Lembur')"
        >lembur selesai</div>
        <div
          v-if="getConditionActionNonActiveOvertime()"
          class="action-item"
          @click="onNonActiveOvertime()"
        >lembur tidak aktif</div>
        <div
          v-if="getConditionActionOvertimeAgain()"
          class="action-item"
          @click="onActionOvertimeAgain()"
        >tidak jadi lembur selesai</div>
        <div v-if="getConditionActionDelete()" class="action-item" @click="onDelete()">hapus</div>
      </div>
    </b-modal>
  </div>
</template>

<script src="../Script/tableMobile.js"></script>

<style lang="scss" scoped>
.place-data {
  max-height: 300px;
  overflow-x: scroll;
}
.place-item {
  border-bottom: 1px solid #dbdfea;
  padding: 0.5rem;
}
.place-action {
  text-align: right;
  align-self: center;
}
.action-item {
  padding: 25px 0px 25px 20px;
  border-bottom: 1px solid #dbdfea;
}
</style>
