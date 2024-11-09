<template>
  <div class="row mx-0">
    <div class="d-lg-flex my-auto gap-4 full-height auth-left align-items-center justify-content-center flex-column col-lg-7 d-none">
      <img src="/public/images/auth-cover.svg" alt="cover image of login image" width="400" />
      <h1 class="text-center text-info fw-bold">Company Article Editor</h1>
    </div>
    <div class="col-lg-5 col-12 full-height">
      <form @submit.prevent="onSubmitForm" class="d-flex flex-column justify-content-center full-height mx-4">
        <h2 class="text-center mb-4">Login</h2>
        <div class="mb-3">
          <label for="email" class="form-label">Email address</label>
          <input type="email" class="form-control" id="email" v-model="email" aria-describedby="emailHelp" />
          <div id="emailHelp" class="form-text text-danger">{{ email_error }}</div>
        </div>
        <div class="mb-3">
          <label for="password" class="form-label">Password</label>
          <input type="password" class="form-control" id="password" v-model="password" />
          <div id="emailHelp" class="form-text text-danger">{{ password_error }}</div>
        </div>
        <button type="submit" class="btn btn-primary mt-4">Submit</button>
      </form>
    </div>
  </div>
</template>

<!-- LOGIN FEATURE AND CONNECT IT TO THE BACKEND -->
<script>
import CryptoJS from "crypto-js";
var secret_passphrase = process.env.MIX_SECRET_PASSPHRASE;

export default {
  data() {
    return {
      email: "",
      password: "",

      email_error: "",
      password_error: "",

      is_calling_api: false,
      isAuthenticated: false,
    };
  },
  inject: ["authenticateUser"],
  mounted() {
    sessionStorage.clear();
  },
  methods: {
    onRequiredFieldError() {
      if (!this.email) {
        this.email_error = "Email is required";
        return true;
      } else {
        this.email_error = "";
      }

      if (!this.password) {
        this.password_error = "Password is required";
        return true;
      } else {
        this.password_error = "";
      }
    },

    onSubmitForm() {
      if (this.onRequiredFieldError()) return;

      this.is_calling_api = true;
      this.$user_query("action_user", {
        user: {
          email: this.email,
          password: this.password,
          action_type: "login",
        },
      })
        .then((res) => {
          const response = res.data.data.user;
          if (res.data.errors) {
            let errors = Object.values(res.data.errors[0].extensions.validation).flat();
            let errors_keys = Object.keys(res.data.errors[0].extensions.validation).flat();

            const error_message = (name, index) => {
              this[name] = errors_keys.some((q) => q == index) ? errors[errors_keys.indexOf(index)] : "";
            };

            error_message("email_error", "user.email");
            error_message("password_error", "user.password");
          }
          if (response.error) {
            this.$swal("Error", response.message, "error");
          } else {
            this.isAuthenticated = true;
            const encryptedToken = CryptoJS.AES.encrypt(response.access_token, process.env.MIX_SECRET_PASSPHRASE).toString();
            const encryptedRefreshToken = CryptoJS.AES.encrypt(response.refresh_token, process.env.MIX_SECRET_PASSPHRASE).toString();
            sessionStorage.setItem("user_api_token", encryptedToken);
            sessionStorage.setItem("refresh-token", encryptedRefreshToken);
            sessionStorage.setItem("token-expiration", response.token_expiration);
            sessionStorage.setItem("login_type", "user");
            window.location.href = "/user/dashboard";
            this.authenticateUser(true);
          }
        })
        .catch((err) => {
          console.log(err);
        })
        .finally(() => {
          this.is_calling_api = false;
        });
    },
  },
};
</script>
