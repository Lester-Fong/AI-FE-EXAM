<template>
  <div>
    <div v-if="isPortal">
      <UserHeader />
      <div class="container-fluid full-height px-0">
        <div class="row mx-0">
          <UserSidebar />
          <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 mt-5 pt-4">
            <router-view></router-view>
          </main>
        </div>
      </div>
    </div>
    <div v-else>
      <router-view></router-view>
    </div>
  </div>
</template>


<script>
import UserHeader from "./portal/includes/header.vue";
import UserSidebar from "./portal/includes/sidebar.vue";
export default {
  components: { UserHeader, UserSidebar },

  data() {
    return {
      isWeb: false,
      isPortal: false,
      isUserLoggedIn: false,
      user_rec: {},
    };
  },
  provide() {
    return {
      // using the Arrow function to preserve 'this'
      authenticateUser: (value) => {
        if (value) {
          this.onPopulateUserData();
        }
      },
    };
  },
  mounted() {
    const token = sessionStorage.getItem("user_api_token");
    if (token) {
      this.isPortal = true;
      this.isUserLoggedIn = true;
      this.onPopulateUserData();
    }
  },

  methods: {
    onPopulateUserData() {
      this.$user_query("user", {
        action_type: "current_user",
      }).then((res) => {
        this.user_rec = res.data.data.user[0];
        this.isPortal = true;
        this.isUserLoggedIn = true;
        this.$store.commit("updateUserRecord", this.user_rec);
      });
    },
  },

  watch: {
    $route(to, from) {
      if (to.meta.isWeb) {
        this.isWeb = true;
      } else {
        this.isWeb = false;
      }
    },
  },
};
</script>