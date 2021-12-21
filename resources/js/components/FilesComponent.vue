<template>
  <div>
    <div class="card">
      <div class="card-header">
        <h1 class="page-header">
          List of files in a course
          <button
            type="button"
            class="btn btn-primary float-right"
            v-on:click.prevent="showModal(false)"
          >
            + New file
          </button>
        </h1>
      </div>
      <button
        type="button"
        class="btn btn-primary float-right"
        v-on:click.prevent="test(files)"
      >
        + test
      </button>

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
            <tr v-for="(file, i) in files" :key="file.id">
              <td>{{ i + 1 }}</td>
              <td>
                <a v-bind:href="'/files/' + file.id">{{ file.name }}</a>
              </td>
              <td width="15%">
                <a
                  href="#"
                  v-on:click.prevent="showModal(true, file)"
                  title="Edit title"
                  class="btn btn-info btn-sm"
                  ><i class="far fa-edit"></i
                ></a>
                <a
                  href="#"
                  v-on:click.prevent="deleteFile(file, i)"
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
      id="modalFile"
      tabindex="-1"
      role="dialog"
      aria-labelledby="CreateFileModal"
      aria-hidden="true"
    >
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">
              {{ this.editMode ? "Edit" : "New" }} file
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
          <form v-on:submit.prevent="update(file)" v-if="editMode">
            <div class="modal-body">
              <div class="form-row">
                <div class="form-group col-md-6">
                  <label for="name">Title</label>
                  <input
                    type="text"
                    v-model="file.name"
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
                  <label for="name">Title</label>
                  <input
                    type="text"
                    v-model="file.name"
                    class="form-control"
                    id="name"
                    placeholder="Presentations"
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
  props: ["alert"],
  data() {
    return {
      files: [],
      editMode: false,
      file: {
        name: "",
      },
    };
  },
  created() {
    this.files = this.getFiles();
  },
  watch: {
    editMode(newVvalue, oldValue) {
      if (!newVvalue && newVvalue != oldValue) this.clearModel();
    },
  },
  methods: {
    test(files) {
      this.alert(JSON.stringify(files), "success");
    },

    getFiles() {
      const url = "/files/id";
      axios.get(url).then((response) => {
        this.files = response.data;
      });
    },
    create() {
      const newFile = this.file;

      axios
        .post("/files", newFile)
        .then((response) => {
          this.files.unshift(response.data);
          this.alert("New file was successfully created.", "success");
        })
        .catch((err) => {
          console.log(err);
          //          this.alert("Failed to create a new file.", "error");
          this.alert(err, "error");
        });
      this.editMode = false;
      this.closeModal();
    },
    update(file) {
      const data = {
        name: file.name,
      };

      axios
        .put(`/files/${file.id}`, data)
        .then((response) => {
          this.getFiles();
          this.alert("File was successfully updated.", "success");
        })
        .catch((err) => {
          console.log(err);
          this.alert("Failed to  update the file.", "error");
        });
      this.editMode = false;
      this.closeModal();
    },
    deleteFile(file, i) {
      const url = "/files/" + file.id;
      swal
        .fire({
          title: `Do you really want to remove this file ${file.name}?`,
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
                this.files.splice(i, 1);
                this.alert("The file was removed.", "success");
              })
              .catch((err) => {
                this.alert("Failed to remove the file.", "error");
              });
          }
        });
    },
    showModal(editMode, file = {}) {
      this.editMode = editMode;
      if (this.editMode) {
        this.file.id = file.id;
        this.file.name = file.name;
      }
      $("#modalFile").modal("show");
    },
    btnCancel() {
      this.editMode = false;
      this.closeModal();
    },
    clearModel() {
      this.file.id = "";
      this.file.name = "";
    },
    closeModal() {
      $("#modalFile").modal("hide");
    },
  },
};
</script>