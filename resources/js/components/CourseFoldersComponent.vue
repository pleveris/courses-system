<template>
  <div>
    <div class="card">
      <div class="card-header">
        <h1 class="page-header">
          List of folders in a course
          <button
            type="button"
            class="btn btn-primary float-right"
            v-on:click.prevent="showModal(false)"
          >
            + New folder
          </button>
        </h1>
      </div>
      <button
        type="button"
        class="btn btn-primary float-right"
        v-on:click.prevent="test(folders)"
      >
        test
      </button>

      course id: {{ courseid }} user id: {{ userid }}

      <div class="card-body">
        <table class="table">
          <thead>
            <tr>
              <th scope="col">#</th>
              <th scope="col">Title</th>
              <th scope="col"></th>
            </tr>
          </thead>

          <tbody>
            <tr v-for="(folder, i) in folders" :key="folder.id">
              <td>{{ i + 1 }}</td>
              <td>
                <a v-bind:href="'/folders/' + folder.id">{{ folder.name }}</a>
              </td>
              <td width="15%">
                <a
                  v-bind:href="'/folders/' + folder.id"
                  title="View files in this folder"
                  class="btn btn-light btn-sm"
                  ><i class="fas fa-search-plus"></i
                ></a>
                <a
                  href="#"
                  v-on:click.prevent="showModal(true, folder)"
                  title="Edit title"
                  class="btn btn-info btn-sm"
                  ><i class="far fa-edit"></i
                ></a>
                <a
                  href="#"
                  v-on:click.prevent="deleteFolder(folder, i)"
                  title="Remove"
                  class="btn btn-danger btn-sm"
                  ><i class="far fa-trash-alt"></i
                ></a>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Modal -->
    <div
      class="modal fade"
      id="modalFolder"
      tabindex="-1"
      role="dialog"
      aria-labelledby="CreateFolderModal"
      aria-hidden="true"
    >
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">
              {{ this.editMode ? "Edit" : "New" }} folder
            </h5>
            <button
              type="button"
              class="close"
              data-dismiss="modal"
              aria-label="Close"
            >
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form v-on:submit.prevent="update(folder)" v-if="editMode">
            <div class="modal-body">
              <div class="form-row">
                <div class="form-group col-md-6">
                  <label for="name">Name</label>
                  <input
                    type="text"
                    v-model="folder.name"
                    class="form-control"
                    id="name"
                    required
                  />
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button
                type="button"
                class="btn btn-secondary"
                v-on:click.prevent="btnCancel"
              >
                Cancel
              </button>
              <button type="submit" class="btn btn-primary">Submit</button>
            </div>
          </form>
          <form v-on:submit.prevent="create" v-else>
            <div class="modal-body">
              <div class="form-row">
                <div class="form-group col-md-6">
                  <input
                    type="hidden"
                    class="form-control"
                    id="user_id"
                    name="user_id"
                    <!--
                    value=" {{userid}}  "
                    --
                  />
                  />
                </div>
                <div class="form-group col-md-6">
                  <input
                    type="hidden"
                    class="form-control"
                    id="course_id"
                    name="course_id"
                    <!--
                    value="courseid"
                  />
                  -->
                </div>

                <div class="form-group col-md-6">
                  <label for="name">Title</label>
                  <input
                    type="text"
                    v-model="folder.name"
                    class="form-control"
                    id="name"
                    placeholder="Lectures"
                    required
                  />
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button
                type="button"
                class="btn btn-secondary"
                v-on:click.prevent="btnCancel"
              >
                Cancel
              </button>
              <button type="submit" class="btn btn-primary">Submit</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: ["alert", "courseid", "userid"],
  data() {
    return {
      folders: [],
      editMode: false,
      folder: {
        name: "",
        /*user_id: this.userid,
course_id: this.courseid,*/
      },
    };
  },
  created() {
    this.folders = this.getFolders();
  },
  watch: {
    editMode(newVvalue, oldValue) {
      if (!newVvalue && newVvalue != oldValue) this.clearModel();
    },
  },
  methods: {
    test(folders) {
      this.alert(JSON.stringify(folders), "success");
    },

    getFolders() {
      const url = "/coursefolders/" + this.courseid;
      axios.get(url).then((response) => {
        this.folders = response.data;
      });
    },
    create() {
      /*      const newFolder = this.folder; */
      this.folder.user_id = this.userid;
      this.folder.course_id = this.courseid;
      const newFolder = this.folder;
      axios
        .post("/folders", newFolder)
        .then((response) => {
          this.folders.unshift(response.data);
          this.alert("New folder was successfully created.", "success");
        })
        .catch((err) => {
          console.log(err);
          this.alert("Failed to create a new folder.", "error");
          /*          this.alert(JSON.stringify(err), "error");*/
          /*          this.alert(err.response, "error");*/
        });
      this.editMode = false;
      this.closeModal();
    },
    update(folder) {
      const data = {
        name: folder.name,
      };

      axios
        .put(`/folders/${folder.id}`, data)
        .then((response) => {
          this.getFolders();
          this.alert("Folder was successfully updated.", "success");
        })
        .catch((err) => {
          console.log(err);
          this.alert("Failed to  update the folder.", "error");
        });
      this.editMode = false;
      this.closeModal();
    },
    deleteFolder(folder, i) {
      const url = "/folders/" + folder.id;
      swal
        .fire({
          title: `Do you really want to remove this folder ${folder.name}?`,
          text: "This change cannot be undone!",
          icon: "warning",
          showCancelButton: true,
          confirmButtonColor: "#d33",
          confirmButtonText: "Remove",
          cancelButtonText: "Cancel",
        })
        .then((result) => {
          if (result.value) {
            axios
              .delete(url)
              .then((response) => {
                this.folders.splice(i, 1);
                this.alert("The folder was removed.", "success");
              })
              .catch((err) => {
                this.alert("Failed to remove the folder.", "error");
              });
          }
        });
    },
    showModal(editMode, folder = {}) {
      this.editMode = editMode;
      if (this.editMode) {
        this.folder.id = folder.id;
        this.folder.name = folder.name;
      }
      $("#modalFolder").modal("show");
    },
    btnCancel() {
      this.editMode = false;
      this.closeModal();
    },
    clearModel() {
      this.folder.id = "";
      this.folder.name = "";
    },
    closeModal() {
      $("#modalFolder").modal("hide");
    },
  },
};
</script>
