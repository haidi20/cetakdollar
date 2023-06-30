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
          <b-row v-for="(item, index) in getData" :key="index">
            <b-col class="place-item">
              <b-row>
                <b-col :cols="getIsMobile ? '12' : '10'" @click="onOpenAction(item)">
                  <h5>{{item.employee_name}} - {{item.position_name}}</h5>
                  <b-row>
                    <b-col cols>
                      <span>
                        <b>Waktu :</b>
                      </span>
                    </b-col>
                    <b-col cols>
                      <span>
                        <b>Tanggal :</b>
                      </span>
                    </b-col>
                  </b-row>
                  <b-row>
                    <b-col cols>
                      <span>{{item.duration_readable}}</span>
                    </b-col>
                    <b-col cols style="font-size: 14px">
                      <span>{{item.date_start_readable}}</span>
                      <br />
                      <span>{{item.date_end_readable}}</span>
                    </b-col>
                  </b-row>
                  <div>
                    <!-- <span>Pengawas :</span> -->
                    <span>
                      <b>Dibuat oleh :</b>
                    </span>
                    <br />
                    <span>{{item.creator_name}}</span>
                  </div>
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
          <div class="action-item" @click="onEdit">Ubah</div>
          <div class="action-item" @click="onDelete">Hapus</div>
          <div class="action-item"></div>
        </vue-bottom-sheet>
      </b-col>
    </b-row>
    <FilterData />
    <Form />
  </div>
</template>

<script>
import axios from "axios";
import moment from "moment";
import { isMobile } from "../../utils";
import Form from "./form";
import FilterData from "./filter.vue";
export default {
  data() {
    return {
      title: "",
    };
  },
  components: {
    FilterData,
    Form,
  },
  computed: {
    getBaseUrl() {
      return this.$store.state.base_url;
    },
    getUserId() {
      return this.$store.state.user?.id;
    },
    getData() {
      return this.$store.state.vacation.data;
    },
    getLoadingTable() {
      return this.$store.state.vacation.loading.table;
    },
    getIsMobile() {
      return isMobile();
    },
    form() {
      return this.$store.state.vacation.form;
    },
  },
  methods: {
    onOpenAction(data) {
      this.$store.commit("vacation/INSERT_FORM", {
        form: data,
      });

      if (!this.getConditionAction()) {
        return false;
      }

      this.$refs.myBottomSheet.open();
    },
    onCreate() {
      this.$store.commit("vacation/CLEAR_FORM");
      this.$store.commit("vacation/INSERT_FORM_TITLE", {
        form_title: "Tambah Cuti",
      });
      this.$bvModal.show("vacation_form");
    },
    onFilter() {
      this.$bvModal.show("vacation_filter");
    },
    onEdit(type, title) {
      //   console.info(type, title);

      if (this.getConditionAction()) {
        this.$refs.myBottomSheet.close();
        this.$store.commit("vacation/INSERT_FORM_TITLE", {
          form_title: "Ubah Cuti",
        });
        this.$bvModal.show("vacation_form");
      }
    },
    async onDelete() {
      if (!this.getConditionAction()) {
        return false;
      }

      this.$refs.myBottomSheet.close();
      //   console.info(this.form);
      const Swal = this.$swal;
      await Swal.fire({
        title: "Perhatian!!!",
        html: `Anda yakin ingin hapus data cuti dari karyawan ${this.form.employee_name} ?</h2>`,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Ya hapus",
        cancelButtonText: "Batal",
      }).then(async (result) => {
        if (result.isConfirmed) {
          await axios
            .post(`${this.getBaseUrl}/api/v1/vacation/delete`, {
              id: this.form.id,
              user_id: this.getUserId,
            })
            .then((responses) => {
              //   console.info(responses);
              const data = responses.data;

              const Toast = Swal.mixin({
                toast: true,
                position: "top-end",
                showConfirmButton: false,
                timer: 4000,
                timerProgressBar: true,
                didOpen: (toast) => {
                  toast.addEventListener("mouseenter", Swal.stopTimer);
                  toast.addEventListener("mouseleave", Swal.resumeTimer);
                },
              });

              if (data.success == true) {
                Toast.fire({
                  icon: "success",
                  title: data.message,
                });

                this.$store.dispatch("vacation/fetchData", {
                  user_id: this.getUserId,
                });
              }
            })
            .catch((err) => {
              console.info(err);

              const Toast = Swal.mixin({
                toast: true,
                position: "top-end",
                showConfirmButton: false,
                timer: 4000,
                timerProgressBar: true,
                didOpen: (toast) => {
                  toast.addEventListener("mouseenter", Swal.stopTimer);
                  toast.addEventListener("mouseleave", Swal.resumeTimer);
                },
              });

              Toast.fire({
                icon: "error",
                title: err.response.data.message,
              });
            });
        }
      });
    },
    getConditionAction() {
      //   console.info(Number(this.form.created_by), Number(this.getUserId));
      return Number(this.form.created_by) == Number(this.getUserId);
    },
  },
};
</script>


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
