<template>
  <div>
    <div class="card">
      <div class="card-header">
        <h1 class="page-header">
          List of courses
          <button
            type="button"
            class="btn btn-primary float-right"
            v-on:click.prevent="showModal(false)"
          >
            + New
          </button>
        </h1>
      </div>
      <button
        type="button"
        class="btn btn-primary float-right"
        v-on:click.prevent="test(courses)"
      >
        test
      </button>

      <div class="card-body">
        <table class="table">
          <thead>
            <tr>
              <th scope="col">#</th>
              <th scope="col">Title</th>
              <th scope="col">Students</th>
              <th scope="col">Schedule</th>
              <th scope="col">Start date</th>
              <th scope="col">End date</th>
              <th scope="col"></th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(course, i) in courses" :key="course.id">
              <td>{{ i + 1 }}</td>
              <td>
                <a v-bind:href="'/get-courses/' + course.id">{{
                  course.name
                }}</a>
              </td>
              <td>
                <span class="badge badge-secondary">{{
                  course.students_count || 0
                }}</span>
              </td>
              <td>{{ course.schedule }}</td>
              <td>{{ course.start_date }}</td>
              <td>{{ course.end_date }}</td>
              <td width="15%">
                <a
                  v-bind:href="'/get-courses/' + course.id"
                  title="More info"
                  class="btn btn-light btn-sm"
                  ><i class="fas fa-search-plus"></i
                ></a>
                <a
                  href="#"
                  v-on:click.prevent="showModal(true, course)"
                  title="Edit"
                  class="btn btn-info btn-sm"
                  ><i class="far fa-edit"></i
                ></a>
                <a
                  href="#"
                  v-on:click.prevent="deleteCourse(course, i)"
                  title="Delete"
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
      id="modalCourse"
      tabindex="-1"
      role="dialog"
      aria-labelledby="CreateCourseModal"
      aria-hidden="true"
    >
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">
              {{ this.editMode ? "Edit" : "New" }} Course
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
          <form v-on:submit.prevent="update(course)" v-if="editMode">
            <div class="modal-body">
              <div class="form-row">
                <div class="form-group col-md-6">
                  <label for="name">Title</label>
                  <input
                    type="text"
                    v-model="course.name"
                    class="form-control"
                    id="name"
                    required
                  />
                </div>
                <div class="form-group col-md-6">
                  <label for="schedule">Schedule</label>
                  <input
                    type="time"
                    v-model="course.schedule"
                    class="form-control"
                    id="schedule"
                  />
                </div>
                <div class="form-group col-md-6">
                  <label for="start_date">Start date</label>
                  <input
                    type="date"
                    v-model="course.start_date"
                    class="form-control"
                    id="start_date"
                    required
                  />
                </div>
                <div class="form-group col-md-6">
                  <label for="end_date">End date</label>
                  <input
                    type="date"
                    v-model="course.end_date"
                    class="form-control"
                    id="end_date"
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
                    v-model="course.name"
                    class="form-control"
                    id="name"
                    placeholder="title"
                    required
                  />
                </div>
                <div class="form-group col-md-6">
                  <label for="schedule">Schedule</label>
                  <input
                    type="time"
                    v-model="course.schedule"
                    class="form-control"
                    id="schedule"
                  />
                </div>
                <div class="form-group col-md-6">
                  <label for="start_date">Start date</label>
                  <input
                    type="date"
                    v-model="course.start_date"
                    class="form-control"
                    id="start_date"
                    required
                  />
                </div>
                <div class="form-group col-md-6">
                  <label for="end_date">End date</label>
                  <input
                    type="date"
                    v-model="course.end_date"
                    class="form-control"
                    id="end_date"
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
      courses: [],
      editMode: false,
      course: {
        name: "",
        schedule: "",
        start_date: "",
        end_date: "",
      },
    };
  },
  created() {
    this.courses = this.getCourses();
  },
  watch: {
    editMode(newVvalue, oldValue) {
      if (!newVvalue && newVvalue != oldValue) this.clearModel();
    },
  },
  methods: {
    test(courses) {
      this.alert(JSON.stringify(courses), "success");
    },

    getCourses() {
      const url = "/courses";
      axios.get(url).then((response) => {
        this.courses = response.data;
      });
    },
    create() {
      const newCourse = this.course;

      axios
        .post("/courses", newCourse)
        .then((response) => {
          this.courses.unshift(response.data);
          this.alert("New course created successfully.", "success");
        })
        .catch((err) => {
          console.log(err);
          //          this.alert("Failed to create a new course.", "error");
          this.alert(err, "error");
        });
      this.editMode = false;
      this.closeModal();
    },
    update(course) {
      const data = {
        name: course.name,
        schedule: course.schedule,
        start_date: course.start_date,
        end_date: course.end_date,
      };

      axios
        .put(`/courses/${course.id}`, data)
        .then((response) => {
          this.getCourses();
          this.alert("Course updated.", "success");
        })
        .catch((err) => {
          console.log(err);
          this.alert("Failed to update a course.", "error");
        });
      this.editMode = false;
      this.closeModal();
    },
    deleteCourse(course, i) {
      const url = "/courses/" + course.id;
      swal
        .fire({
          title: `Do you really want to remove the course ${course.name}?`,
          text: "If you delete it, it can't be undone!",
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
                this.courses.splice(i, 1);
                this.alert("Course removed successfully.", "success");
              })
              .catch((err) => {
                this.alert("Failed to remove a course.", "error");
              });
          }
        });
    },
    showModal(editMode, course = {}) {
      this.editMode = editMode;
      if (this.editMode) {
        this.course.id = course.id;
        this.course.name = course.name;
        this.course.schedule = course.schedule;
        this.course.start_date = course.start_date;
        this.course.end_date = course.end_date;
      }
      $("#modalCourse").modal("show");
    },
    btnCancel() {
      this.editMode = false;
      this.closeModal();
    },
    clearModel() {
      this.course.id = "";
      this.course.name = "";
      this.course.schedule = "";
      this.course.start_date = "";
      this.course.end_date = "";
    },
    closeModal() {
      $("#modalCourse").modal("hide");
    },
  },
};
</script>