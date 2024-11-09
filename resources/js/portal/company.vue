<template>
  <h4>Company</h4>
  <div>
    <table id="company_table" class="table table-bordered dt-responsive nowrap table-striped align-middle w-100">
      <thead>
        <tr>
          <th>Name</th>
          <th>Status</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="a in companies">
          <td class="fw-bold">{{ a.name }}</td>
          <td>
            <span class="badge badge-pill fw-bold" :class="a.status ? 'bg-info' : 'bg-danger'">{{ a.status ? "active" : "inactive" }}</span>
          </td>
          <td>
            <div class="btn-group">
              <button class="btn btn-secondary dropdown-toggle" type="button" id="defaultDropdown" data-bs-toggle="dropdown" data-bs-auto-close="true" aria-expanded="false"></button>
              <ul class="dropdown-menu" aria-labelledby="defaultDropdown">
                <li><a class="dropdown-item" href="#" @click="onEditRecord(a)">Edit</a></li>
              </ul>
            </div>
          </td>
        </tr>
      </tbody>
    </table>
    <company-modal @close="onCloseModal" @success="onSuccessModal" :company="company" :is_edit="is_edit"></company-modal>
  </div>
</template>


<script>
import CompanyModal from "./company/form.vue";
export default {
  components: { CompanyModal },
  data() {
    return {
      companies: [],
      filteredCompanies: [],
      company: {},
      is_edit: false,
    };
  },
  mounted() {
    this.getCompanies();
  },
  methods: {
    getCompanies() {
      this.$user_query("company", {
        action_type: "display_companies",
      }).then((response) => {
        this.filteredCompanies = response.data.data.company;
      });
    },

    onCreateRecord() {
      this.is_edit = false;
      $("#company-modal").modal("show");
    },

    onSuccessModal() {
      this.is_edit = false;
      $("#company-modal").modal("hide");
      this.company = {};
      // this.getCompanies();
      window.location.reload();
    },

    onCloseModal() {
      $("#company-modal").modal("hide");
      this.company = {};
      this.is_edit = false;
    },

    onEditRecord(company) {
      this.company = company;
      this.is_edit = true;
      $("#company-modal").modal("show");
    },
  },
  watch: {
    companies() {
      this.$nextTick(() => {
        this.$onDatatable("#company_table", true);
        $(".new_record").on("click", this.onCreateRecord);
      });
    },
    filteredCompanies: function () {
      this.companies = this.filteredCompanies;
    },
  },
};
</script>