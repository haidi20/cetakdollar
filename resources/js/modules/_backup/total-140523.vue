<template>
  <div>
    <b-table-simple hover medium caption-top responsive :bordered="true">
      <b-thead>
        <b-tr>
          <b-th
            rowspan="2"
            width="15"
            style="vertical-align: middle"
            class="fixed-column"
            nowrap
          >Total Data</b-th>
          <b-th
            v-for="(date, index) in getDateRange"
            v-bind:key="`date-${index}`"
          >{{ setLabelDate(date) }}</b-th>
        </b-tr>
        <b-tr>
          <b-th
            v-for="(date, index) in getDateRange"
            v-bind:key="`name-date-${index}`"
            style="text-align-last: center"
          >{{ setLabelNameDate(date) }}</b-th>
        </b-tr>
      </b-thead>
      <b-tbody>
        <b-tr>
          <b-td class="fixed-column">MASUK</b-td>
          <b-td
            v-for="(date, index) in getDateRange"
            v-bind:key="`data-date-${index}`"
          >{{ getTotalByInitial("M", date) }}</b-td>
        </b-tr>
        <b-tr>
          <b-td class="fixed-column">OFF</b-td>
          <b-td
            v-for="(date, index) in getDateRange"
            v-bind:key="`data-date-${index}`"
          >{{ getTotalByInitial("OFF", date) }}</b-td>
        </b-tr>
        <b-tr>
          <b-td class="fixed-column">CUTI</b-td>
          <b-td
            v-for="(date, index) in getDateRange"
            v-bind:key="`data-date-${index}`"
          >{{ getTotalByInitial("C", date) }}</b-td>
        </b-tr>
        <b-tr>
          <b-td class="fixed-column">SAKIT</b-td>
          <b-td
            v-for="(date, index) in getDateRange"
            v-bind:key="`data-date-${index}`"
          >{{ getTotalByInitial("S", date) }}</b-td>
        </b-tr>
        <b-tr>
          <b-td class="fixed-column">TOTAL</b-td>
          <b-td
            v-for="(date, index) in getDateRange"
            v-bind:key="`data-date-${index}`"
          >{{ getTotalByInitial("ALL", date) }}</b-td>
        </b-tr>
      </b-tbody>
    </b-table-simple>
  </div>
</template>

<script>
import moment from "moment";

export default {
  computed: {
    getRosterStatuses() {
      return this.$store.state.rosterStatus.data;
    },
    getTotal() {
      return this.$store.state.roster.data.total;
    },
    getDateRange() {
      return this.$store.state.roster.date_range;
    },
  },
  watch: {
    getRosterStatuses(value, oldValue) {
      if (value.length > 0) {
        this.$store.dispatch("roster/fetchTotal", {
          rosterStasuses: value,
        });
      }
    },
  },
  methods: {
    getTotalByInitial(initial, date = null) {
      const listTotal = this.getTotal;

      //   console.info(listTotal);

      if (listTotal[initial] !== undefined) {
        return listTotal[initial][date];
      } else {
        return null;
      }
    },
    setLabelDate(date) {
      return moment(date).format("DD");
    },
    setLabelNameDate(date) {
      return moment(date).format("dddd");
    },
  },
};
</script>

<style lang="scss" scoped>
</style>
