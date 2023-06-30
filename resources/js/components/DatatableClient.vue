<template>
  <div>
    <b-row style="align-items: self-end">
      <slot v-if="filter" name="filter"></slot>
      <b-col cols="v-else"></b-col>
      <!-- <b-col :cols="filter ? 3 : 6"> -->
      <b-col cols>
        <b-form-input
          v-model="search"
          placeholder="cari..."
          @input="onSearch"
          :append-outer-icon="search ? 'times' : ''"
          @click:append-outer="search = ''"
          style="max-width: 200px; float:right"
        ></b-form-input>
      </b-col>
    </b-row>
    <br />
    <b-row>
      <b-col class="table-wrapper">
        <b-table-simple hover medium caption-top responsive :bordered="bordered">
          <b-thead head-variant="light">
            <b-tr>
              <b-th
                v-for="(item, index) in getColumns()"
                v-bind:key="index"
                :style="{ width: item.width }"
                :colspan="item.colspan"
                :rowspan="item.rowspan"
                style="vertical-align: middle"
                :class="item.class"
              >
                <slot v-if="item.label_custom" :name="item.label_custom"></slot>
                <span v-else>{{ item.label }}</span>
              </b-th>
              <slot name="thead">
                <!--  -->
              </slot>
            </b-tr>
            <b-tr>
              <slot name="theadSecond">
                <!--  -->
              </slot>
            </b-tr>
            <b-tr>
              <slot name="theadThird">
                <!--  -->
              </slot>
            </b-tr>
          </b-thead>

          <b-tbody>
            <!-- <div v-if="getLoadingTable"></div> -->
            <b-tr v-if="getLoadingTable">
              <b-td :colspan="countColumn">Loading...</b-td>
            </b-tr>
            <b-tr v-else-if="data.length == 0">
              <b-td :colspan="countColumn">Data Kosong</b-td>
            </b-tr>
            <slot v-else name="tbody" :filteredData="filteredData" :currentPage="currentPage"></slot>
          </b-tbody>
          <b-tfoot v-if="footer">
            <slot name="tfoot"></slot>
          </b-tfoot>
        </b-table-simple>
      </b-col>
    </b-row>
    <b-row>
      <b-col></b-col>
      <b-col col md="3" sm="12">
        <b-pagination
          class="float-end"
          v-model="currentPage"
          :total-rows="countData"
          :per-page="options.perPage"
          @change="onUpdateData"
        />
      </b-col>
    </b-row>
  </div>
</template>

<script>
export default {
  props: {
    data: Array,
    columns: Array,
    filter: Boolean,
    footer: Boolean,
    options: Object,
    bordered: Boolean,
    nameStore: String,
    nameLoading: String,
  },
  data() {
    return {
      newData: [...this.data],
      search: null,
      url: null,
      currentPage: 1,
      countData: 0,
      countColumn: 1,
    };
  },
  components: {
    //
  },
  mounted() {
    this.countData = this.data.length;
    this.countColumn = this.columns.length;
  },
  computed: {
    getLoadingTable() {
      return this.$store.state[this.nameStore].loading[this.nameLoading];
    },
    filteredData: {
      get() {
        if (this.data.length > 0) {
          let data = this.data;
          if (this.search) {
            data = data.filter((item) => {
              let found = false;
              for (const key in item) {
                if (item.hasOwnProperty(key)) {
                  if (
                    typeof item[key] === "string" &&
                    item[key]
                      .toLowerCase()
                      .indexOf(this.search.toLowerCase()) !== -1
                  ) {
                    found = true;
                    break;
                  }
                }
              }
              return found;
            });
          }

          const start = (this.currentPage - 1) * this.options.perPage;
          const end = start + this.options.perPage;

          this.countData = data.length;

          return data.slice(start, end);
        } else {
          return [];
        }
      },
      set(value) {
        this.newData = [...value];
      },
    },
  },
  watch: {
    data(newValue, oldValue) {
      this.newData = newValue;
      // this.countData = newValue.length;
    },
    newData(newValue, oldValue) {},
  },
  methods: {
    onUpdateData() {
      //
    },
    onSearch() {
      clearTimeout(this.timeout);
      this.timeout = setTimeout(() => {
        // reload
        this.filteredData = this.newData.filter((item) => {
          return Object.values(item)
            .join("")
            .toLowerCase()
            .includes(this.search.toLowerCase());
        });

        this.currentPage = 1;
      }, 500);
    },
    getColumns() {
      const columns = this.columns.filter((item) => item.label !== null);

      return columns;
    },
  },
};
</script>

<style lang="css" scoped></style>
