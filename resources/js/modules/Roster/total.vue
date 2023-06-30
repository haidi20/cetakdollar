<template>
  <div>
    <b-table-simple hover medium caption-top responsive :bordered="true">
      <b-thead>
        <b-tr>
          <b-th rowspan="2" width="15" style="vertical-align: middle" class="fixed-column" nowrap></b-th>
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
        <tr v-for="(position, index) in getPositions" :key="index">
          <b-td :style="setStyle(position)">{{position.name}}</b-td>
          <b-td
            v-for="(date, index) in getDateRange"
            v-bind:key="`data-date-${index}`"
            :style="setBackground(position, getTotalByPosition(position.id, date))"
          >{{ getTotalByPosition(position.id, date) }}</b-td>
        </tr>
      </b-tbody>
    </b-table-simple>
  </div>
</template>

<script>
import moment from "moment";

export default {
  computed: {
    getTotal() {
      return this.$store.state.roster.data.total;
    },
    getDateRange() {
      return this.$store.state.roster.date_range;
    },
    getPositions() {
      return this.$store.state.master.data.positions;
    },
  },
  watch: {
    getPositions(value, oldValue) {
      if (value.length > 0) {
        this.$store.dispatch("roster/fetchTotal", { positions: value });
      }
    },
  },
  methods: {
    getTotalByPosition(position_id, date = null) {
      const listTotal = this.getTotal;

      //   console.info(listTotal);

      if (listTotal[position_id] !== undefined) {
        return listTotal[position_id][date];
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
    setBackground(position, value) {
      //   console.info(value, position.minimum_employee);
      return {
        backgroundColor:
          Number(value) < Number(position.minimum_employee) ? "orange" : null,
      };
    },
    setStyle(data) {
      if (data.id == "all") {
        return {
          color: "white",
          backgroundColor: "#435EBE",
        };
      }
    },
  },
};
</script>

<style lang="scss" scoped>
</style>
