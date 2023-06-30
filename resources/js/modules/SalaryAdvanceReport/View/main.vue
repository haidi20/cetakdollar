<template>
  <div>
    <DatatableClient
      :data="getData"
      :columns="columns"
      :options="options"
      nameStore="salaryAdvanceReport"
      nameLoading="table"
      :filter="false"
      :footer="false"
      bordered
    >
      <template v-slot:tbody="{ filteredData }">
        <b-tr v-for="(item, index) in filteredData" :key="index">
          <b-td>
            <ButtonAction
              v-if="
                getConditionOnbehalf(item)
                || getConditionApproval(item)
            "
              class="cursor-pointer"
              type="click"
            >
              <template v-slot:list_detail_button>
                <template v-if="getCan('perwakilan laporan kasbon')">
                  <a
                    href="#"
                    v-if="getConditionOnbehalf(item)"
                    @click="onApprove(item, 'accept_onbehalf')"
                  >Terima Perwakilan Direktur</a>
                </template>
                <template v-if="getConditionApproval(item)">
                  <!-- v-if="item.approval_status != 'accept'" -->
                  <a href="#" @click="onApprove(item, 'accept')">Terima</a>
                  <a
                    href="#"
                    v-if="getConditionReject(item)"
                    @click="onApprove(item, 'reject')"
                  >Tolak</a>
                </template>
                <!-- <a href="#" v-if="getCan('hapus laporan kasbon')" @click="onDelete(item)">Hapus</a> -->
              </template>
            </ButtonAction>
          </b-td>
          <b-td v-for="column in getColumns()" :key="column.label">
            <template v-if="column.field == 'approval_label'">
              <span
                :class="`badge bg-${item.approval_color} `"
                style="width:6rem"
              >{{item.approval_status_readable}}</span>
            </template>
            <template v-else-if="column.field == 'approval_description'">
              <span v-html="item.approval_description" class></span>
            </template>
            <template v-else>
              <span class>{{ item[column.field] }}</span>
            </template>
          </b-td>
        </b-tr>
      </template>
    </DatatableClient>
    <FormApproval />
  </div>
</template>

<script src="../Script/main.js"></script>

<style lang="scss" scoped>
.place_filter_table {
  align-items: self-end;
  margin-bottom: 0;
  display: inline-block;
}
</style>
