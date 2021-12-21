
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
user id: {{auth()->user()->id }}
            <course-folders-component 
                v-bind:alert="alert"
                courseid="{{ $courseId }}"
                userid="{{ auth()->user()->id }}"
            >
            <course-folders-component />
        </div>
    </div>
</div>
@endsection
