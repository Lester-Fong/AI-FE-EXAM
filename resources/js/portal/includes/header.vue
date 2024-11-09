<template>
  <header class="position-absolute top-0 w-100 theme-bg-secondary">
    <nav class="navbar navbar-expand-lg py-2 navbar-light d-flex justify-content-end px-4">
      <small class="text-left h6 text-warning pointer" @click="handleLogout">Logout</small>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
    </nav>
  </header>
</template>


<script>
export default {
  methods: {
    handleLogout() {
      this.$swal({
        title: "Are you sure?",
        text: "You are about to logout",
        icon: "warning",
        confirmButtonText: "Yes, Logout",
        showCancelButton: true,
        cancelButtonText: "No, Cancel",
      }).then((result) => {
        if (result.isConfirmed) {
          window.location.href = "/login";
          sessionStorage.clear();
        }
      });
    },
  },
};
</script>
