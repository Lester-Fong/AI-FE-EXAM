<template>
  <h4>User</h4>
  <div>
    <table id="user_table" class="table table-bordered dt-responsive nowrap table-striped align-middle w-100">
      <thead>
        <tr>
          <th>Name</th>
          <th>Type</th>
          <th>Status</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="a in users">
          <td class="fw-bold">{{ a.firstname + " " + a.lastname }}</td>
          <td>
            <span class="badge badge-pill fw-bold" :class="a.user_type ? 'bg-info' : 'bg-danger'">{{ !a.user_type ? "Writer" : "Editor" }}</span>
          </td>
          <td>
            <span class="badge badge-pill fw-bold" :class="a.user_status ? 'bg-primary' : 'bg-secondary'">{{ a.user_status ? "Active" : "Inactive" }}</span>
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
    <user-modal @close="onCloseModal" @success="onSuccessModal" :user="user" :is_edit="is_edit"></user-modal>
  </div>
</template>


<script>
import UserModal from "./user/form.vue";
export default {
  components: { UserModal },
  data() {
    return {
      users: [],
      filteredUsers: [],
      user: {},
      is_edit: false,
    };
  },
  mounted() {
    this.getUsers();
  },
  methods: {
    getUsers() {
      this.$user_query("user", {
        action_type: "display_all_users",
      }).then((response) => {
        console.log(response);

        this.filteredUsers = response.data.data.user;
      });
    },

    onCreateRecord() {
      this.is_edit = false;
      $("#user-modal").modal("show");
    },

    onSuccessModal() {
      this.is_edit = false;
      this.user = {};
      $("#user-modal").modal("hide");
      window.location.reload();
    },

    onCloseModal() {
      $("#user-modal").modal("hide");
      this.user = {};
      this.is_edit = false;
    },

    onEditRecord(user) {
      this.user = user;
      this.is_edit = true;
      $("#user-modal").modal("show");
    },
  },
  watch: {
    users() {
      this.$nextTick(() => {
        this.$onDatatable("#user_table", true);
        $(".new_record").on("click", this.onCreateRecord);
      });
    },
    filteredUsers: function () {
      this.users = this.filteredUsers;
    },
  },
};
</script>