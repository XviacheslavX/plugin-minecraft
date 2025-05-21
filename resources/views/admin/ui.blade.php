@extends('admin.layouts.admin')

@section('title', trans('centralcorp::messages.ui_title'))

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
                <form action="{{ route('centralcorp.admin.ui.update') }}" method="POST">
                    @csrf
                    <div class="form-check form-switch">
                        <input type="hidden" name="alert_activation" value="0">
                        <label for="alert_activation" class="form-label">{{ trans('centralcorp::messages.alert_activation') }}</label>
                        <input type="checkbox" id="alert_activation" name="alert_activation" class="form-check-input" value="1" {{ $options['alert_activation'] ?? false ? 'checked' : '' }}>
                    </div>
                    <div class="form-check form-switch">
                        <input type="hidden" name="alert_scroll" value="0">
                        <label for="alert_scroll" class="form-label">{{ trans('centralcorp::messages.alert_scroll') }}</label>
                        <input type="checkbox" id="alert_scroll" name="alert_scroll" class="form-check-input" value="1" {{ $options['alert_scroll'] ?? false ? 'checked' : '' }}>
                    </div>
                    <div class="mb-3">
                        <label for="alert_msg" class="form-label">{{ trans('centralcorp::messages.alert_msg') }}</label>
                        <input type="text" class="form-control" id="alert_msg" name="alert_msg" value="{{ $options['alert_msg'] ?? '' }}">
                    </div>
                    <div class="form-check form-switch">
                        <input type="hidden" name="video_activation" value="0">
                        <label for="video_activation" class="form-label">{{ trans('centralcorp::messages.video_activation') }}</label>
                        <input type="checkbox" id="video_activation" name="video_activation" class="form-check-input" value="1" {{ $options['video_activation'] ?? false ? 'checked' : '' }}>
                    </div>
                    <div class="mb-3">
                        <label for="video_url" class="form-label">{{ trans('centralcorp::messages.video_url') }}</label>
                        <input type="text" class="form-control" id="video_url" name="video_url" value="{{ $options['video_url'] ?? '' }}">
                    </div>
                    <div class="mb-3">
                        <label for="splash" class="form-label">{{ trans('centralcorp::messages.splash') }}</label>
                        <input type="text" class="form-control" id="splash" name="splash" value="{{ $options['splash'] ?? '' }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="splash_author" class="form-label">{{ trans('centralcorp::messages.splash_author') }}</label>
                        <input type="text" class="form-control" id="splash_author" name="splash_author" value="{{ $options['splash_author'] ?? '' }}" required>
                    </div>
                    <button type="submit" class="btn btn-primary">{{ trans('centralcorp::messages.save') }}</button>
                </form>
            </div>
        </div>
    </div>
@endsection
