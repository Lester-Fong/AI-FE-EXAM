<template>
  <h4>Article</h4>
  <div>
    <table id="article_table" class="table table-bordered dt-responsive nowrap table-striped align-middle w-100">
      <thead>
        <tr>
          <th>Image</th>
          <th>Title</th>
          <th>Link</th>
          <th>Writer</th>
          <th>Editor</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="a in articles">
          <td class="fw-bold">
            <img :src="'/' + a.image" alt="article thumbnail" width="100" />
          </td>
          <td class="fw-bold">{{ a.title }}</td>
          <td>
            <span>{{ a.link }}</span>
          </td>
          <td class="fw-bold">
            <span v-show="a.writer">{{ a.writer?.firstname + " " + a.writer?.lastname }}</span>
          </td>
          <td class="fw-bold">
            <span v-show="a.editor">{{ a.editor?.firstname + " " + a.editor?.lastname }}</span>
          </td>
          <td>
            <div class="btn-group" v-if="user_type || a.status == 0">
              <button class="btn btn-secondary dropdown-toggle" type="button" id="defaultDropdown" data-bs-toggle="dropdown" data-bs-auto-close="true" aria-expanded="false"></button>
              <ul class="dropdown-menu" aria-labelledby="defaultDropdown">
                <li><a class="dropdown-item" href="#" @click="onEditRecord(a)">Edit</a></li>
              </ul>
            </div>
          </td>
        </tr>
      </tbody>
    </table>
    <article-modal @close="onCloseModal" @success="onSuccessModal" :article="article" :is_edit="is_edit" :companies="companies"></article-modal>
  </div>
</template>


<script>
import ArticleModal from "./article/form.vue";
export default {
  components: { ArticleModal },
  data() {
    return {
      articles: [],
      filteredArticles: [],
      article: {},
      is_edit: false,
      companies: [],
    };
  },
  mounted() {
    this.getArticles();
  },
  computed: {
    user_type() {
      return this.$store.state.userType;
    },
  },
  methods: {
    getArticles() {
      this.$user_query("article", {
        action_type: "display_articles",
      }).then((response) => {
        this.filteredArticles = response.data.data.article;
      });
    },

    getCompanies() {
      this.$user_query("company", {
        action_type: "display_companies",
      }).then((response) => {
        this.companies = response.data.data.company;
      });
    },

    onCreateRecord() {
      this.is_edit = false;
      if (this.companies.length === 0) {
        this.getCompanies();
      }
      $("#article-modal").modal("show");
    },

    onSuccessModal() {
      this.is_edit = false;
      $("#article-modal").modal("hide");
      this.article = {};
      // this.getArticles();
      window.location.reload();
    },

    onCloseModal() {
      $("#article-modal").modal("hide");
      this.article = {};
      this.is_edit = false;
    },

    onEditRecord(article) {
      this.article = article;
      this.is_edit = true;
      if (this.companies.length === 0) {
        this.getCompanies();
      }
      $("#article-modal").modal("show");
    },
  },
  watch: {
    articles() {
      this.$nextTick(() => {
        this.$onDatatable("#article_table", !this.user_type);
        $(".new_record").on("click", this.onCreateRecord);
      });
    },
    filteredArticles: function () {
      this.articles = this.filteredArticles;
    },
  },
};
</script>