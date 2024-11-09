<template>
  <nav id="sidebar" class="col-md-3 z-index-10 full-height col-lg-2 d-md-block theme-bg-primary sidebar collapse">
    <div class="mt-5 pt-4">
      <ul class="nav flex-column">
        <li class="nav-item">
          <router-link :to="{ name: 'DashboardPage' }" class="nav-link">Dashboard</router-link>
        </li>

        <li class="nav-item" v-if="user_type">
          <router-link :to="{ name: 'CompanyPage' }" class="nav-link">Companies</router-link>
        </li>

        <li class="nav-item">
          <router-link :to="{ name: 'ArticlePage' }" class="nav-link">All Media</router-link>
        </li>

        <li class="nav-item" v-if="user_type">
          <router-link :to="{ name: 'UserPage' }" class="nav-link">Users</router-link>
        </li>
      </ul>
    </div>
  </nav>
</template>

<script>
export default {
  data() {
    return {
      sidebar: false,
    };
  },
  computed: {
    user_type() {
      return this.$store.state.userType;
    },
  },
  methods: {
    toggleSidebar() {
      this.sidebar = !this.sidebar;
    },
  },
};
</script>

