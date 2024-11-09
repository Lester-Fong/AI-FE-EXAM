<template>
  <div class="loader" v-if="is_calling_api"></div>
  <div class="modal fade" id="article-modal" tabindex="-1" aria-labelledby="selectBlueprintModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
      <div class="modal-content px-3">
        <div class="modal-header bg-white">
          <h5 class="modal-title mb-3" id="selectBlueprintModalLabel">{{ is_edit ? "Edit" : "Create" }} Article</h5>
          <button type="button" class="btn-close" aria-label="Close" @click="onCloseModal"></button>
        </div>
        <div class="modal-body">
          <div class="row g-4">
            <div class="col-md-6">
              <label for="title" class="form-label fw-semibold">Title:</label><span class="text-danger"> *</span>
              <input type="text" class="form-control" id="title" v-model="title" placeholder="Enter your article title" />
              <small class="text-danger">{{ title_error }}</small>
            </div>

            <div class="col-md-6">
              <label for="company" class="form-label fw-semibold">Company:</label><span class="text-danger"> *</span>
              <select name="company" id="company" v-model="selected_company" class="form-select">
                <option value="" selected default hidden>Select Company</option>
                <option v-for="c in companies" :value="c.original_id">{{ c.name }}</option>
              </select>
              <small class="text-danger">{{ selected_company_error }}</small>
            </div>

            <div class="col-md-6">
              <label for="link" class="form-label fw-semibold">Link:</label><span class="text-danger"> *</span>
              <input type="text" class="form-control" id="link" v-model="link" />
              <small class="text-danger">{{ link_error }}</small>
            </div>

            <div class="col-md-6">
              <label for="link" class="form-label fw-semibold">Date:</label><span class="text-danger"> *</span>
              <VueDatePicker v-model="date"></VueDatePicker>
              <small class="text-danger">{{ link_error }}</small>
            </div>

            <div class="col-6">
              <label for="image" class="form-label">Thumbnail:</label><span class="text-danger"> *</span>
              <input ref="header_image" type="file" class="form-control" id="header_image" @change="onFileChanged($event)" />
              <img :src="display_header_img" alt="display_header_img" v-if="is_header_image" width="100" height="100" class="object-cover" />
              <small class="text-danger">{{ thumbnail_error }}</small>
            </div>

            <div class="col-12">
              <label class="form-label">Article Content: *</label>
              <div class="mb-3">
                <textarea id="summernote_blog" v-model="content"></textarea>
                <small class="text-danger">{{ content_error }}</small>
              </div>
            </div>
          </div>

          <div class="d-flex justify-content-end align-items-center mt-4">
            <button class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close" @click="onCloseModal">Cancel</button>
            <button class="btn btn-primary mx-3" @click="saveRecord('save')">Save</button>
            <button class="btn btn-dark" v-if="is_edit && user_type" @click="saveRecord('publish')">Publish</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
  
  
  <script>
export default {
  props: ["article", "is_edit", "companies"],
  emits: ["success", "close"],
  data: function () {
    return {
      title: "",
      link: "",
      date: new Date(),
      selected_company: "",
      content: "",
      is_header_image: false,
      display_header_img: "",
      thumbnail: "",
      thumbnail_error: "",
      title_error: "",
      link_error: "",
      date_error: "",
      content_error: "",
      selected_company_error: "",
      is_calling_api: false,
    };
  },

  mounted() {
    setTimeout(() => {
      this.$nextTick(function () {
        // first get rid of the summernote editor
        $("#container").find(".summernote").summernote("destroy");
        // next remove the extra code created by summernote
        $("#container").find(".note-editor").remove();
        // finally, remove the summernote element itself
        $("#container").find(".summernote").remove();
        // clone the template
        $("#container").append($(".template.summernote").clone());
        // initialize summernote
        $("#container").find(".summernote").summernote();
        $("#summernote_blog").summernote({
          placeholder: "Article Contents Here...",
          tabsize: 2,
          height: 120,
          toolbar: [
            ["style", ["style"]],
            ["font", ["bold", "underline", "clear"]],
            ["color", ["color"]],
            ["para", ["ul", "ol", "paragraph"]],
            ["table", ["table"]],
            ["insert", ["link", "picture", "video"]],
            ["view", ["fullscreen", "codeview", "help"]],
          ],
        });
        $("#summernote_blog").summernote("code", "");
      });
    }, 1000);
  },

  computed: {
    user_type() {
      return this.$store.state.userType;
    },
  },

  methods: {
    isFieldValidated() {
      let is_validated = true;
      if (this.title === "") {
        this.title_error = "Title is required";
        is_validated = false;
      } else {
        this.title_error = "";
      }

      if (!this.is_header_image) {
        this.thumbnail_error = "Article Logo is required";
        is_validated = false;
      } else {
        this.thumbnail_error = "";
      }

      if ($("#summernote_blog").summernote("code") === "") {
        this.content_error = "Content is required";
        is_validated = false;
      } else {
        this.content_error = "";
      }

      if (this.selected_company === "") {
        this.selected_company_error = "Company is required";
        is_validated = false;
      } else {
        this.selected_company_error = "";
      }

      if (this.link === "") {
        this.link_error = "Link is required";
        is_validated = false;
      } else {
        this.link_error = "";
      }

      if (this.date === "") {
        this.date_error = "Date is required";
        is_validated = false;
      } else {
        this.date_error = "";
      }

      return is_validated;
    },

    onFileChanged() {
      this.thumbnail = this.$refs.header_image.files[0];
      this.header_name = this.thumbnail.name ?? "";
      this.display_header_img = URL.createObjectURL(this.thumbnail);
      this.is_header_image = true;
    },

    onClearFields() {
      this.title = "";
      this.link = "";
      this.date = new Date();
      this.selected_company = "";
      this.content = "";
      this.is_header_image = false;
      this.display_header_img = "";
    },
    onClearError() {
      this.is_validated = false;
      this.thumbnail_error = "";
      this.title_error = "";
      this.link_error = "";
      this.date_error = "";
      this.content_error = "";
      this.selected_company_error = "";
    },
    saveRecord(saveType) {
      if (!this.isFieldValidated()) return;

      this.is_calling_api = true;
      this.$user_query("save_article", {
        article: {
          article_id: this.is_edit ? this.article.article_id : "",
          title: this.title,
          link: this.link,
          date: new Date(this.date).toISOString().split("T")[0],
          company_id: this.selected_company,
          status: saveType,
          content: $("#summernote_blog").summernote("code"),
          action_type: this.is_edit ? "update_record" : "add_article_record",
        },
        file: this.thumbnail,
      }).then((res) => {
        this.is_calling_api = false;
        const response = res.data.data.article;
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
          this.title = this.article.title;
          this.link = this.article.link;
          this.date = this.article.date;
          this.selected_company = this.article.company.original_id;
          $("#summernote_blog").summernote("code", this.article.content);
          this.display_header_img = "/" + this.article.image;
          this.is_header_image = true;
        } else {
          this.onClearFields();
        }
      },
    },
  },
};
</script>