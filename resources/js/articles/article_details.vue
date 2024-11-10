<template>
  <div class="container">
    <header class="blog-header py-3">
      <div class="row flex-nowrap justify-content-between align-items-center">
        <div class="col-4 pt-1">
          <h4 class="link-secondary" href="#">Publish Article</h4>
        </div>
        <div class="col-4 d-flex justify-content-end align-items-center">
          <a class="link-secondary" href="#" aria-label="Search">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="mx-3" role="img" viewBox="0 0 24 24">
              <title>Search</title>
              <circle cx="10.5" cy="10.5" r="7.5" />
              <path d="M21 21l-5.2-5.2" />
            </svg>
          </a>
          <a class="btn btn-sm btn-outline-secondary" href="/login">Sign in</a>
        </div>
      </div>
    </header>
  </div>
  <main class="container full-height">
    <div class="p-4 p-md-5 mb-4 text-white rounded bg-article-thumb" :style="`background-image: url(/${article.image})`"></div>

    <div class="row g-5">
      <div class="col-md-8">
        <h1 class="text-center">
          {{ article.title }}
        </h1>
        <div class="mb-5" v-html="article.content"></div>
      </div>

      <div class="col-md-4">
        <div class="position-sticky" style="top: 2rem">
          <div class="p-4 mb-3 bg-light rounded">
            <h4 class="fst-italic">About</h4>
            <p class="mb-0">Customize this section to tell your visitors a little bit about your publication, writers, content, or something else entirely. Totally up to you.</p>
          </div>
        </div>
      </div>
    </div>
  </main>

  <footer class="blog-footer py-4 bg-dark text-center">
    <p class="text-white mb-0 lead">Made with ❤️ by Lester Fong</p>
  </footer>
</template>

<script>
export default {
  data() {
    return {
      article: {},
      link: this.$route.params.link,
    };
  },
  mounted() {
    if (this.link) {
      this.getArticle();
    } else {
      this.$swal({
        title: "Article not found",
        text: "The article you are looking for does not exist",
        icon: "error",
      }).then((res) => {
        this.$router.push("/login");
      });
    }
  },
  methods: {
    getArticle() {
      this.$user_query("front_article", {
        action_type: "get_article_by_link",
        link: this.link,
      }).then((res) => {
        this.article = res.data.data.article[0];
        console.log(this.article);

        if (this.article === null) {
          this.$swal({
            title: "Article not found",
            text: "The article you are looking for does not exist",
            icon: "error",
          }).then((res) => {
            this.$router.push("/login");
          });
        }
      });
    },
  },
};
</script>


<style scoped>
.bd-placeholder-img {
  font-size: 1.125rem;
  text-anchor: middle;
  -webkit-user-select: none;
  -moz-user-select: none;
  user-select: none;
}

@media (min-width: 768px) {
  .bd-placeholder-img-lg {
    font-size: 3.5rem;
  }
}
</style>