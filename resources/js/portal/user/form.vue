<template>
  <div class="modal fade" id="user-modal" tabindex="-1" aria-labelledby="selectBlueprintModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content px-3">
        <div class="modal-header bg-white">
          <h5 class="modal-title mb-3" id="selectBlueprintModalLabel">{{ is_edit ? "Edit" : "Create" }} User</h5>
          <button type="button" class="btn-close" aria-label="Close" @click="onCloseModal"></button>
        </div>
        <div class="modal-body">
          <div class="row g-4">
            <div class="col-md-6">
              <label for="fname" class="form-label fw-semibold">Firstname:</label><span class="text-danger"> *</span>
              <input type="text" class="form-control" id="fname" v-model="firstname" placeholder="Enter your firstname" />
              <small class="text-danger">{{ firstname_error }}</small>
            </div>
            <div class="col-md-6">
              <label for="lastname" class="form-label fw-semibold">Lastname:</label><span class="text-danger"> *</span>
              <input type="text" class="form-control" id="lastname" v-model="lastname" placeholder="Enter your lastname" />
              <small class="text-danger">{{ lastname_error }}</small>
            </div>
            <div class="col-md-6">
              <label for="email" class="form-label fw-semibold">Email:</label><span class="text-danger"> *</span>
              <input type="email" class="form-control" id="email" v-model="email" placeholder="Enter your email" />
              <small class="text-danger">{{ email_error }}</small>
            </div>
            <div class="col-md-6">
              <label for="password" class="form-label fw-semibold">Password:</label><span class="text-danger"> *</span>
              <input type="password" class="form-control" id="password" v-model="password" placeholder="Enter your password" />
              <small class="text-danger">{{ password_error }}</small>
            </div>

            <div class="col-md-6 d-flex align-items-center">
              <label for="statusState" class="form-label fw-semibold me-2">Writer</label>
              <div class="form-check form-switch">
                <input v-model="userType" class="form-check-input" type="checkbox" role="switch" id="statusState" />
                <label for="statusState" class="form-label fw-semibold me-3">Editor</label>
              </div>
            </div>

            <div class="col-md-6 d-flex align-items-center">
              <div class="form-check form-switch">
                <input v-model="status" class="form-check-input" type="checkbox" role="switch" id="status" />
                <label for="status" class="form-label fw-semibold me-3">{{ status ? "Active" : "Inactive" }}</label>
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
  </div>
</template>
  
  
  <script>
export default {
  props: ["user", "is_edit"],
  emits: ["success", "close"],
  data: function () {
    return {
      firstname: "",
      lastname: "",
      email: "",
      password: "",
      userType: false,
      firstname_error: "",
      lastname_error: "",
      email_error: "",
      password_error: "",
      status: false,
    };
  },

  methods: {
    isFieldValidated() {
      let is_validated = true;
      if (this.firstname === "") {
        this.firstname_error = "Firstname is required";
        is_validated = false;
      }

      if (this.lastname === "") {
        this.lastname_error = "Lastname is required";
        is_validated = false;
      }

      if (this.email === "") {
        this.email_error = "Email is required";
        is_validated = false;
      }

      if (this.password === "" && !this.is_edit) {
        this.password_error = "Password is required";
        is_validated = false;
      }

      return is_validated;
    },

    onClearFields() {
      this.firstname = "";
      this.lastname = "";
      this.email = "";
      this.password = "";
      this.userType = false;
      this.status = false;
    },
    onClearError() {
      this.firstname_error = "";
      this.lastname_error = "";
      this.email_error = "";
      this.password_error = "";
    },
    saveRecord() {
      if (!this.isFieldValidated()) return;
      this.$user_query("save_user", {
        user: {
          user_id: this.is_edit ? this.user.user_id : null,
          firstname: this.firstname,
          lastname: this.lastname,
          email: this.email,
          password: this.password,
          user_type: this.userType ? 1 : 0,
          user_status: this.status,
          action_type: this.is_edit ? "update_user" : "create_user",
        },
        file: this.user_logo,
      }).then((res) => {
        const response = res.data.data.user;
        console.log(response);

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
          console.log(this.user);
          this.firstname = this.user.firstname;
          this.lastname = this.user.lastname;
          this.email = this.user.email;
          this.userType = this.user.user_type;
          this.status = this.user.user_status;
        } else {
          this.onClearFields();
        }
      },
    },
  },
};
</script>