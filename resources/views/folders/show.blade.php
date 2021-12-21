
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <show-folder-component 
                v-bind:alert="alert"
                folderid="{{ $folderId }}"
            >
            <show-folder-component />
        </div>
    </div>
</div>
@endsection
