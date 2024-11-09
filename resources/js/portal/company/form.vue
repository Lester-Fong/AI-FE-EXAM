<template>
  <div class="loader" v-if="is_calling_api"></div>
  <div class="modal fade" id="company-modal" tabindex="-1" aria-labelledby="selectBlueprintModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content px-3">
        <div class="modal-header bg-white">
          <h5 class="modal-title mb-3" id="selectBlueprintModalLabel">{{ is_edit ? "Edit" : "Create" }} Company</h5>
          <button type="button" class="btn-close" aria-label="Close" @click="onCloseModal"></button>
        </div>
        <div class="modal-body">
          <div class="row g-4">
            <div class="col-6">
              <label for="image" class="form-label">Company Logo</label><span class="text-danger"> *</span>
              <input ref="header_image" type="file" class="form-control" id="header_image" @change="onFileChanged($event)" />
              <img :src="display_header_img" alt="display_header_img" v-if="is_header_image" width="100" height="100" class="object-cover" />
              <span class="text-danger">{{ logo_error }}</span>
            </div>
            <div class="col-md-6">
              <label for="name" class="form-label fw-semibold">Company Name:</label><span class="text-danger"> *</span>
              <input type="text" class="form-control" id="name" v-model="name" placeholder="Enter your company" />
              <small class="text-danger">{{ name_error }}</small>
            </div>

            <div class="col-md-6 d-flex align-items-center">
              <div class="form-check form-switch">
                <input v-model="status" class="form-check-input" type="checkbox" role="switch" id="statusState" />
                <label for="statusState" class="form-label fw-semibold me-3">Status</label>
              </div>
            </div>
          </div>

          <div class="d-flex justify-content-end align-items-center mt-4">
            <button class="btn btn-secondary me-3" data-bs-dismiss="modal" aria-label="Close" @click="onCloseModal">Cancel</button>
            <button class="btn btn-primary" @click="saveRecord">Save</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>


<script>
export default {
  props: ["company", "is_edit"],
  emits: ["success", "close"],
  data: function () {
    return {
      name: "",
      logo: "",
      status: false,

      is_validated: true,
      name_error: "",
      logo_error: "",
      description_error: "",

      company_id: "",
      is_calling_api: false,

      company_logo: "",
      header_name: "",
      display_header_img: "",
      is_header_image: false,

      logo_error: "",
    };
  },

  methods: {
    isFieldValidated() {
      let is_validated = true;
      if (this.name === "") {
        this.name_error = "Name is required";
        is_validated = false;
      } else {
        this.name_error = "";
      }

      if (!this.is_header_image) {
        this.logo_error = "Company Logo is required";
        is_validated = false;
      } else {
        this.logo_error = "";
      }

      return is_validated;
    },

    onFileChanged() {
      this.company_logo = this.$refs.header_image.files[0];
      this.header_name = this.company_logo.name ?? "";
      this.display_header_img = URL.createObjectURL(this.company_logo);
      this.is_header_image = true;
    },

    onClearFields() {
      this.name = "";
      this.position = "";
      this.status = "";
      this.is_header_image = false;
      this.display_header_img = "";
      this.company_logo = "";
      this.header_name = "";
    },
    onClearError() {
      this.is_validated = false;
      this.name_error = "";
      this.logo_error = "";
    },
    saveRecord() {
      if (!this.isFieldValidated()) return;

      this.is_calling_api = true;
      this.$user_query("save_company", {
        company: {
          company_id: this.company_id ?? "",
          name: this.name,
          status: this.status,
          action_type: this.is_edit ? "update_record" : "add_record",
        },
        file: this.company_logo,
      }).then((res) => {
        this.is_calling_api = false;
        const response = res.data.data.company;
        if (!response.error) {
          this.$swal("Success!", response.message, "success");
          this.$emit("success");
          this.onClearFields();
        } else {
          this.$swal("Error!", "An error occurred.", "error");
        }
      });
    },
    onCloseModal() {
      this.onClearFields();
      this.onClearError();
      this.$emit("close");
    },
  },

  watch: {
    is_edit: {
      handler: function (val) {
        if (val) {
          this.name = this.company.name;
          this.status = parseInt(this.company.status) === 1;
          this.company_id = this.company.company_id;
          this.display_header_img = "/" + this.company.logo;
          this.is_header_image = true;
        } else {
          this.onClearFields();
        }
      },
    },
  },
};
</script>