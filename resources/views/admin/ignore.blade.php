@extends('admin.layouts.admin')

@section('title', trans('centralcorp::messages.ignore_title'))

@section('content')

@if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    
    <div class="container-fluid p-0">
        <div class="card shadow mb-4">
            <div class="card-body">
                <form action="{{ route('centralcorp.admin.ignore.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="ignored_folders" class="form-label">{{ trans('centralcorp::messages.add_ignored_folders') }}</label>
                        <input type="text" class="form-control" id="ignored_folders" name="ignored_folders" placeholder="{{ trans('centralcorp::messages.ignored_folders_placeholder') }}">
                    </div>

                    <button type="submit" class="btn btn-primary">{{ trans('centralcorp::messages.save') }}</button>
                </form>
            </div>
        </div>

        <h2 class="mt-5">{{ trans('centralcorp::messages.ignored_folders_title') }}</h2>
        <div class="card shadow mb-4">
            <div class="card-body">
                <ul class="list-group mb-4">
                    @foreach($folders as $folder)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span class="me-2">{{ $folder->folder_name }}</span>
                            <form action="{{ route('centralcorp.admin.ignore.destroyFolder', $folder->id) }}" method="POST" class="ms-2">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">{{ trans('centralcorp::messages.delete') }}</button>
                            </form>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
@endsection
