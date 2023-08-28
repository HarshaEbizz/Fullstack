<script>
export default {
  data: () => ({
    dataobj: "",
    editdataobj: "",
    category_id: "",
    category_name: "",
    category_image: "",
    edit_category_image: "",
    preview: "",
    addmodel: false,
    editmodel: false,
    categories: [],
    url: null,
    search: "",
    headers: [
      { key: "id", title: "ID" },
      { key: "category_name", title: "Category Name" },
      { key: "category_image", title: "image" },
      { key: "created_at", title: "Created Date" },
      { title: "Actions" },
    ],
    nameRules: [
      (value) => {
        if (value.trim() != "") return true;
        return "You must enter a category name.";
      },
    ],
    imageRules: [
      (value) => {
        if (!value) return "Image is required";
        // if (value[0].size < 2000000)
        // return "Image size should be less than 2 MB!";
      },
    ],
  }),
  created: function () {
    this.getcategories();
  },
  methods: {
    previewImage(event) {
      this.preview = null;
      var input = event.target;
      var fileName = event.target.files[0].name;
      var extension = fileName
        .substring(fileName.lastIndexOf(".") + 1)
        .toLowerCase();
      if (
        extension == "gif" ||
        extension == "png" ||
        extension == "bmp" ||
        extension == "jpeg" ||
        extension == "jpg"
      ) {
        if (input.files) {
          var reader = new FileReader();
          reader.onload = (e) => {
            this.preview = e.target.result;
          };
          // this.category_image = input.files[0];
          reader.readAsDataURL(input.files[0]);
        }
      } else {
        input.value = "";
        element.preview = null;
        alert("Photo only allows file types of GIF, PNG, JPG, JPEG and BMP. ", {
          type: "danger",
        });
      }
    },
    async getcategories() {
      const res = await axios.get("/categories/all", this.dataobj);
      this.categories = res.data;
    },
    async addcategory() {
      this.dataobj = {
        category_name: this.category_name,
        category_image: this.category_image[0],
      };
      const res = await axios.post("/categories", this.dataobj, {
        headers: {
          "Content-Type": "multipart/form-data",
        },
      });
      if (res.status == 200) {
        this.addmodel = false;
        this.category_name = "";
        this.category_image = "";
        this.dataobj = "";
        this.getcategories();
        this.$toast.success(res.data.message, {
          position: "top-right",
        });
      } else {
        this.$toast.error(res.data.message, {
          position: "top-right",
        });
      }
    },
    async editcategory(category_id) {
      this.category_id = category_id;
      const res = await axios.get(`/categories/${category_id}/edit`);
      this.category_name = res.data.category_name;
      this.category_image = "storage/" + res.data.category_image;
      console.log(this.category_image);
      this.editmodel = true;
    },
    async updatecategory() {
      let formData = new FormData();
      formData.append("category_name", this.category_name);
      if (this.edit_category_image[0]) {
        formData.append("category_image", this.edit_category_image[0]);
      }
      formData.append("_method", "PUT");

      const res = await axios.post(
        `/categories/${this.category_id}`,
        formData,
        {
          headers: {
            "Content-Type": "multipart/form-data",
          },
        }
      );
      if (res.status == 200) {
        this.editmodel = false;
        this.category_name = "";
        this.category_image = "";
        this.getcategories();
        this.$toast.success(res.data.message, {
          position: "top-right",
        });
      } else {
        this.$toast.error(res.data.message, {
          position: "top-right",
        });
      }
    },
    async deletecategory(category_id) {
      this.$swal
        .fire({
          title: "Are you sure?",
          text: "You won't be able to revert this!",
          icon: "warning",
          showCancelButton: true,
          confirmButtonColor: "#3085d6",
          cancelButtonColor: "#d33",
          confirmButtonText: "Yes, delete it!",
        })
        .then((result) => {
          if (result.isConfirmed) {
            const res = axios
              .delete(`/categories/${category_id}`)
              .then((result) => {
                if (result.status == 200) {
                  this.getcategories();
                  this.$swal.fire("Deleted!", result.data.message, "success");
                }
              })
              .catch((error) => {
                console.log(error);
              });
          }
        });
    },
  },
};
</script>
<template>
  <div class="main-panel">
    <div class="content-wrapper">
      <div class="page-header">
        <h3 class="page-title">Categories</h3>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Tables</a></li>
            <li class="breadcrumb-item active" aria-current="page">
              Categories
            </li>
          </ol>
        </nav>
      </div>

      <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
          <div class="card">
            <div class="card-body">
              <h4 class="card-title">categories Table</h4>
              <v-row justify="center">
                <v-dialog v-model="addmodel" persistent width="1024">
                  <template v-slot:activator="{ props }">
                    <button
                      v-bind="props"
                      class="btn btn-gradient-success btn-md btn-icon-text"
                    >
                      <i class="mdi mdi-plus btn-icon-prepend"></i>
                      Add category
                    </button>
                  </template>
                  <div class="d-flex align-items-center auth">
                    <div class="row flex-grow">
                      <div class="col-lg-12 mx-auto">
                        <div class="auth-form-light text-left p-5">
                          <div class="brand-logo">
                            <h3>Add category</h3>
                          </div>
                          <v-form @submit.prevent>
                            <div class="form-group">
                              <v-text-field
                                v-model="category_name"
                                :rules="nameRules"
                                label="category name"
                                required
                              ></v-text-field>
                            </div>
                            <div class="mb-3" v-if="preview">
                              <div class="crop-image-preview-container">
                                <p>Preview Here:</p>
                                <img id="crop-image" :src="preview" />
                              </div>
                            </div>
                            <div class="form-group">
                              <v-file-input
                                v-model="category_image"
                                :rules="imageRules"
                                @change="previewImage"
                                accept="image/png, image/jpeg, image/bmp"
                                label="Category image"
                              ></v-file-input>
                            </div>
                            <div class="card-footer row">
                              <button
                                class="col-2 me-1 btn btn-outline-danger btn-fw"
                                @click="addmodel = false"
                              >
                                Close
                              </button>
                              <button
                                @click="addcategory"
                                class="col-2 btn btn-outline-info btn-fw"
                                type="submit"
                              >
                                Submit
                              </button>
                            </div>
                          </v-form>
                        </div>
                      </div>
                    </div>
                  </div>
                </v-dialog>
              </v-row>
              <v-row justify="center">
                <v-dialog v-model="editmodel" persistent width="1024">
                  <div class="d-flex align-items-center auth">
                    <div class="row flex-grow">
                      <div class="col-lg-12 mx-auto">
                        <div class="auth-form-light text-left p-5">
                          <div class="brand-logo">
                            <h3>Edit category</h3>
                          </div>
                          <v-form @submit.prevent id="edit_category_form">
                            <div class="form-group">
                              <v-text-field
                                v-model="category_name"
                                :rules="nameRules"
                                label="category name"
                                required
                              ></v-text-field>
                            </div>
                            <div class="mb-3" v-if="category_image && !preview">
                              <div class="crop-image-preview-container">
                                <p>Preview Here:</p>
                                <img id="crop-image" :src="category_image" />
                              </div>
                            </div>
                            <div class="mb-3" v-else>
                              <div class="crop-image-preview-container">
                                <p>Preview Here:</p>
                                <img id="crop-image" :src="preview" />
                              </div>
                            </div>
                            <div class="form-group">
                              <v-file-input
                                v-model="edit_category_image"
                                @change="previewImage"
                                accept="image/png, image/jpeg, image/bmp"
                                label="Category image"
                              ></v-file-input>
                            </div>
                            <div class="card-footer row">
                              <button
                                class="col-2 me-1 btn btn-outline-danger btn-fw"
                                @click="editmodel = false"
                              >
                                Close
                              </button>
                              <button
                                @click="updatecategory()"
                                class="col-2 btn btn-outline-info btn-fw"
                                type="submit"
                              >
                                Submit
                              </button>
                            </div>
                          </v-form>
                        </div>
                      </div>
                    </div>
                  </div>
                </v-dialog>
              </v-row>
            </div>
          </div>
        </div>
        <div class="row col-lg-12">
          <v-text-field
            v-model="search"
            append-icon="search"
            label="Search"
            single-line
            hide-details
          ></v-text-field>
          <v-data-table :headers="headers" :items="categories" :search="search">
            <template v-slot:item="row">
              <tr>
                <td>{{ row.index + 1 }}</td>
                <td>{{ row.item.raw.category_name }}</td>
                <td>
                  <div class="widget-content-left mr-3">
                    <div class="widget-content-left">
                      <img
                        width="240"
                        class="rounded-circle rounded_bg"
                        :src="'storage/' + row.item.raw.category_image"
                      />
                    </div>
                  </div>
                </td>
                <td>{{ new Date(row.item.raw.created_at).toDateString() }}</td>
                <td>
                  <button
                    type="button"
                    class="btn btn-gradient-dark btn-icon-text me-3"
                    @click="editcategory(row.item.raw.id)"
                  >
                    Edit
                    <i class="mdi mdi-file-check btn-icon-append"></i>
                  </button>
                  <button
                    type="button"
                    class="btn btn-gradient-danger btn-icon-text"
                    @click="deletecategory(row.item.raw.id)"
                  >
                    <i class="mdi mdi-delete btn-icon-prepend"></i>
                    Delete
                  </button>
                </td>
              </tr>
            </template>
          </v-data-table>
        </div>
      </div>
    </div>
  </div>
</template>