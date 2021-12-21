<?php

namespace Tests\Feature\Http\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\User;
use App\Student;

class StudentControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * testListingStudents
     *
     * @return void
     */
    public function testListingStudents()
    {
        $user = factory(User::class)->create();
        $students = factory(Student::class, 5)->create();

        $response = $this->actingAs($user)->get(route('students.index'));

        $response->assertOk();
        $response->assertJsonStructure([
            '*' => [
                'id',
                'firstname',
                'lastname',
                'email',
                'birthdate',
                'created_at',
                'updated_at',
                'courses_count'
            ]
        ]);
    }

    /**
     * test_store
     *
     * @return void
     */
    public function testStoreNewStudent()
    {
        $user = factory(User::class)->create();
        $firstname = $this->faker->firstName;
        $lastname = $this->faker->lastName;
        $email = $this->faker->safeEmail;
        $birthdate = $this->faker->date;

        $response = $this->actingAs($user)->post(route('students.store'), [
            "firstname" => $firstname,
            "lastname" => $lastname,
            "email" => $email,
            "birthdate" => $birthdate
        ]);

        $response->assertCreated();
        $this->assertDatabaseHas('students', ['email' => $email]);
    }

    /**
     * testStudentValidateDuplicateEmail
     *
     * @return void
     */
    public function StoreStudentValidateDuplicateEmail()
    {
        $user = factory(User::class)->create();
        $student = factory(Student::class)->create();

        $response = $this->actingAs($user)->post(route('students.store'), [
            "firstname" => 'Jhon',
            "lastname" => 'Doe',
            "email" => $student->email,
            "birthdate" => date('Y-m-d')
        ]);

        $response->dump();
        $response->assertJsonValidationErrors('email');
    }

    /**
     * testShowStudent
     *
     * @return void
     */
    public function testShowStudent()
    {
        $user = factory(User::class)->create();
        $student = factory(Student::class)->create();

        $response = $this->actingAs($user)->get(route('students.show', $student->id));

        $response->assertStatus(200)
            ->assertJson(['firstname' => $student->firstname])
            ->assertJson(['lastname' => $student->lastname]);
    }

    /**
     * testUpdateStudent
     *
     * @return void
     */
    public function testUpdateStudent()
    {
        $user = factory(User::class)->create();
        $student = factory(Student::class)->create();
        $data = [
            'firstname' => $this->faker->firstName,
            'lastname' => $this->faker->lastName,
            'email' => $student->email,
            'birthdate' => $student->birthdate,
        ];

        $response = $this->actingAs($user)->put(route('students.update', $student->id), $data);

        $response->assertStatus(200)
            ->assertJson($data);
        $this->assertDatabaseHas('students', $data);
    }

    /**
     * testDeleteStudent
     *
     * @return void
     */
    public function testDeleteStudent()
    {
        $user = factory(User::class)->create();
        $student = factory(Student::class)->create();

        $response = $this->actingAs($user)->delete(route('students.destroy', $student->id));

        $response->assertStatus(204);
        $this->assertDatabaseMissing('students', ['id' => $student->id]);
    }

    /**
     * testIfGuestsAreAuthorized
     *
     * @return void
     */
    public function testUnauthorizeGuestsAreRedirectToLogin()
    {
        $this->get(route('students.index'))->assertRedirect(route('login'));
        $this->post(route('students.store'), [])->assertRedirect(route('login'));
        $this->get(route('students.show', 101))->assertRedirect(route('login'));
        $this->put(route('students.update', 101), [])->assertRedirect(route('login'));
        $this->delete(route('students.destroy', 101))->assertRedirect(route('login'));
    }
}
