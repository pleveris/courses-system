<template>
  <div>
    <div class="card">
      <div class="card-header">
        <h1 class="page-header">
          {{ course.name }}
          <button
            type="button"
            v-on:click.prevent="showModal(course)"
            class="btn btn-primary float-right"
          >
            + Subscribe a student
          </button>
        </h1>
      </div>
      <button
        type="button"
        class="btn btn-primary float-right"
        v-on:click.prevent="test(course)"
      >
        test
      </button>

      <div class="card-body">
        <ul class="nav nav-tabs" role="tablist">
          <li class="nav-item">
            <a
              class="nav-link active"
              href="#course"
              role="tab"
              data-toggle="tab"
              >Course</a
            >
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#student" role="tab" data-toggle="tab"
              >Subscribed students</a
            >
          </li>
        </ul>

        <!-- Tab panes -->
        <div class="tab-content">
          <div role="tabpanel" class="tab-pane fade in active" id="course">
            <div class="table-reponsive mt-3">
              <table class="vertical-table">
                <tr>
                  <th scope="row">Course:</th>
                  <td>{{ course.name }}</td>
                </tr>
                <tr>
                  <th scope="row">Schedule:</th>
                  <td>{{ course.schedule }}</td>
                </tr>
                <tr>
                  <th scope="row">Start date:</th>
                  <td>{{ course.start_date }}</td>
                </tr>
                <tr>
                  <th scope="row">End date:</th>
                  <td>{{ course.end_date }}</td>
                </tr>
                <tr>
                  <th scope="row">Registered:</th>
                  <td>{{ course.created_at }}</td>
                </tr>
              </table>
            </div>
            <p>
              This course has {{ course.folders.length || 0 }} folders and
              {{ course.files.length || 0 }} files.
            </p>
            <br />
            <p>
              You can view them in
              <a
                v-bind:href="'/course-folders/' + course.id"
                title="Course folders section"
                class="btn btn-light btn-sm"
                >Course folders section</a
              >
            </p>
          </div>

          <div role="tabpanel" class="tab-pane fade" id="student">
            <div class="table-reponsive mt-3">
              <table class="table">
                <thead>
                  <tr>
                    <th scope="col">#</th>
                    <th scope="col">Name</th>
                    <th scope="col">Email</th>
                    <th scope="col">Birth date</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(student, i) in course.students" :key="student.id">
                    <td>{{ i + 1 }}</td>
                    <td>{{ `${student.firstname} ${student.lastname}` }}</td>
                    <td>{{ student.email }}</td>
                    <td>{{ student.birthdate }}</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
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
              Subscribe a student
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
          <form v-on:submit.prevent="suscribe()">
            <div class="modal-body">
              <div class="form-row">
                <div class="form-group col-md-6">
                  <input
                    type="hidden"
                    v-model="course_student.course_id"
                    class="form-control"
                    id="course_id"
                  />
                </div>
                <div class="form-group col-md-12">
                  <label for="students">Select a student</label>
                  <select
                    v-model="course_student.student_id"
                    class="form-control"
                    id="student_id"
                  >
                    <option
                      v-for="student in students"
                      :value="student.id"
                      :key="student.id"
                    >
                      {{ student.firstname }} {{ student.lastname }}
                    </option>
                  </select>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button
                type="button"
                class="btn btn-secondary"
                v-on:click.prevent="hideModal"
              >
                Cancel
              </button>
              <button type="submit" class="btn btn-primary">Subscribe</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: ["alert", "courseid"],
  created() {
    this.getCourse();
  },
  data() {
    return {
      course: {
        name: "",
        schedule: "",
        start_date: "",
        end_date: "",
        created_at: "",
        students: {},
      },
      students: [],
      course_student: {
        course_id: "",
        student_id: "",
      },
    };
  },
  methods: {
    getCourse() {
      const url = `/courses/${this.courseid}`;
      axios
        .get(url)
        .then((response) => {
          this.course = response.data;
        })
        .catch((err) => {
          this.alert("Error receiving data.", "error");
          console.log(err);
        });
    },
    test(course) {
      //      this.alert(JSON.stringify(course), "success");
      this.alert(course.folders.length, "success");
    },
    getStudents() {
      const url = "/students";
      axios
        .get(url)
        .then((response) => {
          this.students = response.data;
        })
        .catch((err) => {
          this.alert("Failed to receive data.", "error");
          console.log(err);
        });
    },
    suscribe() {
      const suscription = this.course_student;

      axios
        .post("/courses/suscribe/" + this.course.id, suscription)
        .then((response) => {
          this.getCourse();
          this.alert("Subscription to a course was successful.", "success");
        })
        .catch((err) => {
          console.log(err);
          this.alert("Subscription to a course failed, maybe you are already subscribed!", "error");
        });
      this.hideModal();
    },
    showModal(course) {
      this.getStudents();
      this.course_student.course_id = course.id;
      $("#modalCourse").modal("show");
    },
    hideModal() {
      $("#modalCourse").modal("hide");
    },
  },
};
</script>