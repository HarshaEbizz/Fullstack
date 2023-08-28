<script>
export default {
  data: () => ({
    dataobj: "",
    tag_id: "",
    tag_name: "",
    addmodel: false,
    editmodel: false,
    tags: [],
    search: "",
    headers: [
      { key: "id", title: "ID" },
      { key: "tag_name", title: "Tag Name" },
      { key: "created_at", title: "Created Date" },
      { title: "Actions" },
    ],
    rules: [
      (value) => {
        if (value.trim() != "") return true;
        return "You must enter a tag name.";
      },
    ],
  }),
  created: function () {
    this.getTags();
  },
  methods: {
    async getTags() {
      const res = await axios.get("/tags/all", this.dataobj);
      this.tags = res.data;
    },
    async addTag() {
      if (this.tag_name) {
        this.dataobj = {
          tag_name: this.tag_name,
        };
        const res = await axios.post("/tags", this.dataobj);
        if (res.status == 200) {
          this.addmodel = false;
          this.tag_name = "";
          this.dataobj = "";
          this.getTags();
          this.$toast.success(res.data.message, {
            position: "top-right",
          });
        } else {
          this.$toast.error(res.data.message, {
            position: "top-right",
          });
        }
      }
    },
    async editTag(tag_id) {
      this.tag_id = tag_id;
      const res = await axios.get(`/tags/${tag_id}/edit`);
      this.tag_name = res.data.tag_name;
      this.editmodel = true;
    },
    async updateTag() {
      if (this.tag_name) {
        this.dataobj = {
          tag_name: this.tag_name,
        };
        const res = await axios.put(`/tags/${this.tag_id}`, this.dataobj);
        if (res.status == 200) {
          this.editmodel = false;
          this.tag_name = "";
          this.dataobj = "";
          this.getTags();
          this.$toast.success(res.data.message, {
            position: "top-right",
          });
        } else {
          this.$toast.error(res.data.message, {
            position: "top-right",
          });
        }
        console.log(res);
      }

      // this.tag_name = res.data.tag_name;
      // this.tag_id = "";
      this.editmodel = false;
    },
    async deleteTag(tag_id) {
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
              .delete(`/tags/${tag_id}`)
              .then((result) => {
                if (result.status == 200) {
                  this.getTags();
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
        <h3 class="page-title">Tags</h3>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Tables</a></li>
            <li class="breadcrumb-item active" aria-current="page">Tags</li>
          </ol>
        </nav>
      </div>
      <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
          <div class="card">
            <div class="card-body">
              <h4 class="card-title">Tags Table</h4>
              <v-row justify="center">
                <v-dialog v-model="addmodel" persistent width="1024">
                  <template v-slot:activator="{ props }">
                    <button
                      v-bind="props"
                      class="btn btn-gradient-success btn-md btn-icon-text"
                    >
                      <i class="mdi mdi-plus btn-icon-prepend"></i>
                      Add tag
                    </button>
                  </template>
                  <div class="d-flex align-items-center auth">
                    <div class="row flex-grow">
                      <div class="col-lg-12 mx-auto">
                        <div class="auth-form-light text-left p-5">
                          <div class="brand-logo">
                            <h3>Add tag</h3>
                          </div>
                          <v-form @submit.prevent>
                            <div class="form-group">
                              <v-text-field
                                v-model="tag_name"
                                :rules="rules"
                                label="Tag name"
                                required
                              ></v-text-field>
                            </div>
                            <div class="card-footer row">
                              <button
                                class="col-2 me-1 btn btn-outline-danger btn-fw"
                                @click="addmodel = false"
                              >
                                Close
                              </button>
                              <button
                                @click="addTag"
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
                            <h3>Edit tag</h3>
                          </div>
                          <v-form @submit.prevent>
                            <div class="form-group">
                              <v-text-field
                                v-model="tag_name"
                                :rules="rules"
                                label="Tag name"
                                required
                              ></v-text-field>
                            </div>
                            <div class="card-footer row">
                              <button
                                class="col-2 me-1 btn btn-outline-danger btn-fw"
                                @click="editmodel = false"
                              >
                                Close
                              </button>
                              <button
                                @click="updateTag()"
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
          <v-data-table :headers="headers" :items="tags" :search="search">
            <template v-slot:item="row">
              <tr>
                <td>{{ row.index + 1 }}</td>
                <td>{{ row.item.raw.tag_name }}</td>
                <td>{{ new Date(row.item.raw.created_at).toDateString() }}</td>
                <td>
                  <button
                    type="button"
                    class="btn btn-gradient-dark btn-icon-text me-3"
                    @click="editTag(row.item.raw.id)"
                  >
                    Edit
                    <i class="mdi mdi-file-check btn-icon-append"></i>
                  </button>
                  <button
                    type="button"
                    class="btn btn-gradient-danger btn-icon-text"
                    @click="deleteTag(row.item.raw.id)"
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
