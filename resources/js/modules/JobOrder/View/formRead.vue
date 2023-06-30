<template>
  <div id="job_order_form">
    <b-row>
      <b-col cols>
        <b-form-group label="Proyek" label-for="project_id" class>
          <VueSelect
            id="project_id"
            class="cursor-pointer"
            v-model="getForm.project_id"
            placeholder="Pilih Proyek"
            :options="getOptionProjects"
            :reduce="(data) => data.id"
            label="name"
            searchable
            style="min-width: 180px"
            :disabled="getReadOnly()"
          />
        </b-form-group>
      </b-col>
    </b-row>
    <b-row>
      <b-col cols>
        <b-form-group label="Pekerjaan" label-for="job_id" class>
          <VueSelect
            id="job_id"
            class="cursor-pointer"
            v-model="getForm.job_id"
            placeholder="Pilih Pekerjaan"
            :options="getOptionJobs"
            :reduce="(data) => data.id"
            label="name"
            searchable
            style="min-width: 180px"
            :disabled="getReadOnly()"
          />
        </b-form-group>
      </b-col>
    </b-row>
    <b-row>
      <b-col cols>
        <b-form-group label="Kode" label-for="job_code" class>
          <b-form-input v-model="getForm.job_code" id="job_code" name="job_code" disabled></b-form-input>
        </b-form-group>
      </b-col>
    </b-row>
    <b-row>
      <b-col cols>
        <b-form-group label="Keterangan Jenis Pekerjaan" label-for="job_note" class>
          <b-form-input
            v-model="getForm.job_note"
            id="job_note"
            name="job_note"
            :disabled="getReadOnly()"
            autocomplete="off"
          ></b-form-input>
        </b-form-group>
      </b-col>
    </b-row>
    <b-row>
      <b-col cols>
        <b-form-group label="Kategori" label-for="category" class>
          <!-- <VueSelect
            id="category"
            class="cursor-pointer"
            v-model="getForm.category"
            placeholder="Pilih Kategori"
            :options="getOptionCategories"
            :reduce="(data) => data.id"
            label="name"
            :searchable="false"
            style="min-width: 180px"
          />-->
          <select
            v-model="getForm.category"
            name="category"
            id="category"
            class="form-control"
            :disabled="getReadOnly()"
          >
            <option
              v-for="(category, index) in getOptionCategories"
              :key="index"
              :value="category.id"
            >{{category.name}}</option>
          </select>
        </b-form-group>
      </b-col>
    </b-row>
    <b-row>
      <b-col cols>
        <b-form-group label="Jam Mulai" label-for="hour_start">
          <!-- <b-form-input type="hour" v-model="getForm.hour_start" id="hour_start" name="hour_start"></b-form-input> -->
          <input
            type="time"
            class="form-control"
            v-model="getForm.hour_start"
            id="hour_start"
            name="hour_start"
            :disabled="getReadOnly() || getForm.form_kind == 'edit'"
          />
        </b-form-group>
      </b-col>
      <b-col cols>
        <b-form-group label="Estimasi Waktu" label-for="estimation">
          <b-form-input
            v-model="getForm.estimation"
            id="estimation"
            name="estimation"
            type="number"
            autocomplete="off"
            :disabled="getReadOnly()"
          ></b-form-input>
        </b-form-group>
      </b-col>
    </b-row>
    <b-row>
      <b-col cols="5" md="6">
        <b-form-group label="Jenis Waktu" label-for="time_type" class>
          <select
            v-model="getForm.time_type"
            name="time_type"
            id="time_type"
            class="form-control"
            :disabled="getReadOnly()"
          >
            <option
              v-for="(time_type, index) in getOptionTimeTypes"
              :key="index"
              :value="time_type.id"
            >{{time_type.name}}</option>
          </select>
        </b-form-group>
      </b-col>
      <b-col cols="7" md="6">
        <b-form-group label="Waktu Selesai " label-for="time_type" class>
          <span>
            {{
            getForm.datetime_estimation_end_readable
            ? getForm.datetime_estimation_end_readable
            : "-"
            }}
          </span>
        </b-form-group>
      </b-col>
    </b-row>
    <b-row>
      <b-col cols>
        <b-form-group label="Tingkat Kesulitan" label-for="job_level" class>
          <!-- <VueSelect
            id="job_level"
            class="cursor-pointer"
            v-model="getForm.job_level"
            placeholder="Pilih Pekerjaan"
            :options="getOptionJobLevels"
            :reduce="(data) => data.id"
            label="name"
            :searchable="false"
            style="min-width: 180px"
          />-->
          <select
            v-model="getForm.job_level"
            name="job_level"
            id="job_level"
            class="form-control"
            :disabled="getReadOnly()"
          >
            <option
              v-for="(job_level, index) in getOptionJobLevels"
              :key="index"
              :value="job_level.id"
            >{{job_level.name}}</option>
          </select>
        </b-form-group>
      </b-col>
    </b-row>
    <b-row>
      <b-col col sm="6">
        <b-form-group label="Pilih Karyawan" label-for="image" class>
          <b-button variant="success" @click="onShowEmployee()">Data Karyawan</b-button>
        </b-form-group>
      </b-col>
    </b-row>
    <b-row>
      <b-col cols>
        <b-form-group label="catatan tambahan" label-for="note" class>
          <b-form-input
            v-model="getForm.note"
            id="note"
            name="note"
            :disabled="getReadOnly()"
            autocomplete="off"
          ></b-form-input>
        </b-form-group>
      </b-col>
    </b-row>
    <br />
    <EmployeeHasParent />
  </div>
</template>

<script>
import VueSelect from "vue-select";
import EmployeeHasParent from "../../EmployeeHasParent/view/employeeHasParent";

export default {
  data() {
    return {
      is_image: false,
      is_loading: false,
    };
  },
  components: {
    VueSelect,
    EmployeeHasParent,
  },
  computed: {
    getBaseUrl() {
      return this.$store.state.base_url;
    },
    getUserId() {
      return this.$store.state.user?.id;
    },
    getOptionProjects() {
      return this.$store.state.project.data;
    },
    getOptionCategories() {
      return this.$store.state.jobOrder.options.categories;
    },
    getOptionJobs() {
      return this.$store.state.master.data.jobs;
    },
    getOptionJobLevels() {
      return this.$store.state.jobOrder.options.job_levels;
    },
    getOptionTimeTypes() {
      return this.$store.state.jobOrder.options.time_types;
    },
    getLabelImage() {
      return this.$store.state.jobOrder.form.label_image;
    },
    getForm() {
      return this.$store.state.jobOrder.form;
    },
  },
  methods: {
    onShowEmployee() {
      this.$bvModal.show("data_employee");
    },
    getReadOnly() {
      const readOnly = this.$store.getters["jobOrder/getReadOnly"];
      //   console.info(readOnly);

      return readOnly;
    },
  },
};
</script>

<style lang="scss" scoped>
#job_order_form {
  max-height: 500px;
  min-height: 400px;
  overflow-y: scroll;
}
#job_order_form::-webkit-scrollbar {
  //
}
</style>
