<template>
  <div id="job_order_form">
    <b-row>
      <b-col cols>
        <b-form-group label="Proyek" label-for="project_id" class>
          <VueSelect
            id="project_id"
            class="cursor-pointer"
            v-model="form.project_id"
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
        <b-form-group label=" Jenis Pekerjaan" label-for="job_id" class>
          <VueSelect
            id="job_id"
            class="cursor-pointer"
            v-model="job_id"
            placeholder="Pilih jenis Pekerjaan"
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
    <b-row v-if="job_id == 'another'">
      <b-col cols>
        <b-form-group label="Nama Pekeraan lainnya" label-for="job_another_name" class>
          <b-form-input
            v-model="form.job_another_name"
            id="job_another_name"
            name="job_another_name"
          ></b-form-input>
        </b-form-group>
      </b-col>
    </b-row>
    <b-row v-if="job_id != 'another'">
      <b-col cols>
        <b-form-group label="Kode" label-for="job_code" class>
          <b-form-input v-model="form.job_code" id="job_code" name="job_code" disabled></b-form-input>
        </b-form-group>
      </b-col>
    </b-row>
    <b-row>
      <b-col cols>
        <b-form-group label="Keterangan Jenis Pekerjaan" label-for="job_note" class>
          <b-form-input
            v-model="form.job_note"
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
            v-model="form.category"
            placeholder="Pilih Kategori"
            :options="getOptionCategories"
            :reduce="(data) => data.id"
            label="name"
            :searchable="false"
            style="min-width: 180px"
          />-->
          <select
            v-model="form.category"
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
          <!-- <b-form-input type="hour" v-model="form.hour_start" id="hour_start" name="hour_start"></b-form-input> -->
          <input
            type="time"
            class="form-control"
            v-model="hour_start"
            id="hour_start"
            name="hour_start"
            :disabled="getReadOnly() || form.form_kind == 'edit'"
          />
        </b-form-group>
      </b-col>
      <b-col cols>
        <b-form-group label="Estimasi Waktu" label-for="estimation">
          <b-form-input
            v-model="estimation"
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
            v-model="time_type"
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
            form.datetime_estimation_end_readable
            ? form.datetime_estimation_end_readable
            : "-"
            }}
          </span>
        </b-form-group>
      </b-col>
    </b-row>
    <b-row>
      <b-col cols>
        <b-form-group label="Tingkat Kesulitan" label-for="job_level" class>
          <select
            v-model="form.job_level"
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
    <b-row v-if="!getReadOnly()">
      <b-col cols>
        <b-form-group :label="getLabelImage" label-for="image" class>
          <!-- <b-form-file id="image" v-model="form.image" :disabled="getReadOnly()"></b-form-file> -->
          <!-- v-model="form.image" -->
          <b-form-file
            id="image"
            :state="Boolean(is_image)"
            :disabled="getReadOnly()"
            @change="onInsertImage($event)"
          ></b-form-file>
        </b-form-group>
      </b-col>
    </b-row>
    <b-row>
      <b-col col sm="6">
        <b-form-group label="Pilih Karyawan" class>
          <b-button variant="success" @click="onShowEmployee()">Data Karyawan</b-button>
        </b-form-group>
      </b-col>
    </b-row>
    <b-row>
      <b-col cols>
        <b-form-group label="catatan tambahan" label-for="note" class>
          <b-form-input
            v-model="form.note"
            id="note"
            name="note"
            :disabled="getReadOnly()"
            autocomplete="off"
          ></b-form-input>
        </b-form-group>
      </b-col>
    </b-row>
    <br />
    <b-row>
      <b-col>
        <b-button variant="info" @click="onCloseModal()">Tutup</b-button>
        <!-- :disabled="is_loading || getIsDisabledBtnSend" -->
        <b-button
          v-if="!getReadOnly()"
          style="float: right"
          variant="success"
          @click="onConfirmation()"
        >Simpan</b-button>
        <!-- <span v-if="is_loading" style="float: right">Loading...</span> -->
      </b-col>
    </b-row>
    <EmployeeHasParent />
    <FormConfirmation />
  </div>
</template>

<script src="../Script/form.js"></script>

<style lang="scss" scoped>
#job_order_form {
  max-height: 500px;
  min-height: 400px;
  overflow-y: scroll;
}
#job_order_form::-webkit-scrollbar {
  display: none;
}
</style>
