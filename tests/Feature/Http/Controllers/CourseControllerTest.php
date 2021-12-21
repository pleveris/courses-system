<?php

namespace Tests\Feature\Http\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\User;
use App\Course;
use App\Student;

class CourseControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * testListingCourses
     *
     * @return void
     */
    public function testListingCourses()
    {
        $user = factory(User::class)->create();
        $courses = factory(Course::class, 5)->create();

        $response = $this->actingAs($user)->get(route('courses.index'));

        $response->assertOk();
        $response->assertJsonStructure([
            '*' => [
                'id',
                'name',
                'schedule',
                'start_date',
                'end_date',
                'created_at',
                'updated_at',
                'students_count'
            ]
        ]);
    }

    /**
     * test_store
     *
     * @return void
     */
    public function testStoreNewCourse()
    {
        $user = factory(User::class)->create();
        $name = $this->faker->sentence;
        $schedule = $this->faker->time;
        $start_date = $this->faker->date;
        $end_date = $this->faker->date;

        $response = $this->actingAs($user)->post(route('courses.store'), [
            "name" => $name,
            "schedule" => $schedule,
            "start_date" => $start_date,
            "end_date" => $end_date,
        ]);

        $response->assertCreated();
        $this->assertDatabaseHas('courses', ['name' => $name]);
    }

    /**
     * testShowCourse
     *
     * @return void
     */
    public function testShowCourse()
    {
        $user = factory(User::class)->create();
        $course = factory(Course::class)->create();

        $response = $this->actingAs($user)->get(route('courses.show', $course->id));

        $response->assertStatus(200)
            ->assertJson(['name' => $course->name])
            ->assertJson(['schedule' => $course->schedule]);
    }

    /**
     * testUpdateCourse
     *
     * @return void
     */
    public function testUpdateCourse()
    {
        $user = factory(User::class)->create();
        $course = factory(Course::class)->create();
        $data = [
            "name" => $this->faker->sentence,
            "schedule" => $this->faker->time,
            "start_date" => $this->faker->date,
            "end_date" => $this->faker->date,
        ];

        $response = $this->actingAs($user)->put(route('courses.update', $course->id), $data);

        $response->assertStatus(200)->assertJson($data);
        $this->assertDatabaseHas('courses', $data);
    }

    /**
     * testDeleteCourse
     *
     * @return void
     */
    public function testDeleteCourse()
    {
        $user = factory(User::class)->create();
        $course = factory(Course::class)->create();

        $response = $this->actingAs($user)->delete(route('courses.destroy', $course->id));

        $response->assertStatus(204);
        $this->assertDatabaseMissing('courses', ['id' => $course->id]);
    }

    /**
     * testSuscribeStudentToCourse
     *
     * @return void
     */
    public function testSuscribeStudentToCourse()
    {
        $user = factory(User::class)->create();
        $course = factory(Course::class)->create();
        $student = factory(Student::class)->create();

        $response = $this->actingAs($user)->post(route('courses.suscribe', $course->id), [
            'student_id' => $student->id
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('course_student', [
            'course_id' => $course->id,
            'student_id' => $student->id
        ]);
    }

    /**
     * testUnsuscribeStudentFromCourse
     *
     * @return void
     */
    public function testUnsuscribeStudentFromCourse()
    {
        $user = factory(User::class)->create();
        $course = factory(Course::class)->create();
        $student = factory(Student::class)->create();

        $response = $this->actingAs($user)->post(route('courses.unsuscribe', $course->id), [
            'student_id' => $student->id
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseMissing('course_student', [
            'course_id' => $course->id,
            'student_id' => $student->id
        ]);
    }

    /**
     * testIfGuestsAreAuthorized
     *
     * @return void
     */
    public function testUnauthorizeGuestsAreRedirectToLogin()
    {
        $this->get(route('courses.index'))->assertRedirect(route('login'));
        $this->post(route('courses.store'), [])->assertRedirect(route('login'));
        $this->get(route('courses.show', 101))->assertRedirect(route('login'));
        $this->put(route('courses.update', 101), [])->assertRedirect(route('login'));
        $this->delete(route('courses.destroy', 101))->assertRedirect(route('login'));
        $this->post(route('courses.suscribe', 101), [])->assertRedirect(route('login'));
        $this->post(route('courses.unsuscribe', 101), [])->assertRedirect(route('login'));
    }
}
