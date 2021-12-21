<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CourseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|max:64',
            'schedule' => 'required',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
        ];
    }

    /**
     * messages
     *
     * @return void
     */
    public function messages()
    {
        return [
            'name.required'         => 'El campo nombre es requerido',
            'schedule.required'     => 'El campo horario es requerido',
            'start_date.required'   => 'El campo fecha inicio es requerido',
            'start_date.required'   => 'El campo fecha final es requerido',
        ];
    }
}
