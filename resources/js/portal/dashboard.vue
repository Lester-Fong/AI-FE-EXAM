<template>
  <h4 class="text-primary">DASHBOARD</h4>
  <hr />

  <div class="d-flex align-items-center justify-content-between">
    <h6>For Edit Articles</h6>
    <router-link :to="{ name: 'ArticlePage' }" class="text-primary h6">View All</router-link>
  </div>
  <div class="row row-cols-1 row-cols-md-3 g-4 mb-5">
    <div class="col" v-for="a in for_edit_articles.slice(0, 6)">
      <div class="card">
        <div class="card-header">
          <img :src="`/${a.image}`" class="card-img-top" alt="..." />
        </div>
        <div class="card-body">
          <h5 class="card-title m-0">{{ a.title }}</h5>
          <small class="">written by - {{ `${a.writer.firstname} ${a.writer.lastname}` }}</small>
        </div>

        <div class="card-footer">
          <div class="d-flex align-items-center justify-content-between">
            <p class="card-text">
              <small class="text-muted m-0">{{ formatDate(a.date) }}</small>
            </p>
            <a :href="`http://publish-article.local/${a.link}`" target="_blank">Read More</a>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="d-flex align-items-center justify-content-between">
    <h6>Published Articles</h6>
    <router-link :to="{ name: 'ArticlePage' }" class="text-primary h6">View All</router-link>
  </div>
  <div class="row row-cols-1 row-cols-md-3 g-4 mb-5">
    <div class="col" v-for="a in published_articles.slice(0, 6)">
      <div class="card">
        <div class="card-header">
          <img :src="`/${a.image}`" class="card-img-top" alt="..." />
        </div>
        <div class="card-body">
          <h5 class="card-title m-0">{{ a.title }}</h5>
          <small class="d-block pt-2">written by - {{ `${a.writer.firstname} ${a.writer.lastname}` }}</small>
          <small v-show="a.editor">edited by - {{ `${a.editor.firstname} ${a.editor.lastname}` }}</small>
        </div>

        <div class="card-footer">
          <div class="d-flex align-items-center justify-content-between">
            <p class="card-text">
              <small class="text-muted m-0">{{ formatDate(a.date) }}</small>
            </p>
            <a :href="`http://publish-article.local/${a.link}`" target="_blank">Read More</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>


<script>
export default {
  name: "Dashboard",
  data() {
    return {
      articles: [],
    };
  },

  mounted() {
    this.getArticles();
  },

  computed: {
    user_type() {
      return this.$store.state.userType;
    },

    for_edit_articles() {
      return this.articles.filter((article) => article.status == 0);
    },

    published_articles() {
      return this.articles.filter((article) => article.status == 1);
    },
  },

  methods: {
    getArticles() {
      this.$user_query("article", {
        action_type: "display_articles",
      }).then((response) => {
        console.log(response.data.data.article);
        this.articles = response.data.data.article;
      });
    },

    formatDate(date) {
      return new Date(date).toLocaleDateString("en-US", {
        year: "numeric",
        month: "short",
        day: "2-digit",
      });
    },
  },
};
</script>