<template>
    <div class="row">
        <div class="col-xl-4 col-md-4 mb-4" v-for="course in courses" :key="course.id">
            <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                <div class="col mr-2">
                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1"> 
                        <a v-bind:href="'/get-courses/' + course.id"> {{ course.name }}  </a>
                    </div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ course.total }}</div>
                </div>
                <div class="col-auto">
                    <i class="fas fa-calendar fa-2x text-gray-300"></i>
                </div>
                </div>
            </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        created () {
            this.courses = this.getCourses();
        },
        data () {
            return {
                courses: []
            }
        },
        methods: {
            getCourses () {
                const url = '/courses/stats';
                axios.get(url)
                .then(response => {
                    this.courses = response.data;
                });
            },
        }
    }
</script>