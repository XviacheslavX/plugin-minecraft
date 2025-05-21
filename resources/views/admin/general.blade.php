@extends('admin.layouts.admin')

@section('title', trans('centralcorp::messages.title'))

@section('page-title', trans('centralcorp::messages.title'))

@if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    
@section('content')
    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="{{ route('centralcorp.admin.general.update') }}" method="POST">
                @csrf

                @foreach (['mods_enabled', 'file_verification', 'embedded_java', 'email_verified', 'role_display', 'money_display'] as $option)
                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <label for="{{ $option }}" class="form-label">{{ trans("centralcorp::messages.$option") }}</label>
                            <input type="hidden" name="{{ $option }}" value="0">
                            <input type="checkbox" id="{{ $option }}" name="{{ $option }}" class="form-check-input" value="1" {{ old($option, $options[$option] ?? 0) ? 'checked' : '' }}>
                        </div>
                    </div>
                @endforeach

                <div class="mb-3">
                    <label for="game_folder_name" class="form-label">{{ trans('centralcorp::messages.game_folder_name') }}</label>
                    <input type="text" class="form-control" id="game_folder_name" name="game_folder_name" placeholder="{{ trans('centralcorp::messages.game_folder_name_placeholder') }}" value="{{ old('game_folder_name', $options['game_folder_name'] ?? '') }}">
                </div>

                <div class="mb-3">
                    <label for="min_ram" class="form-label">{{ trans('centralcorp::messages.default_min_ram') }}</label>
                    <input type="number" class="form-control" id="default_min_ram" name="min_ram" placeholder="2048" value="{{ old('min_ram', $options['min_ram'] ?? 2048) }}" min="512" max="65536">
                </div>

                <div class="mb-3">
                    <label for="max_ram" class="form-label">{{ trans('centralcorp::messages.default_max_ram') }}</label>
                    <input type="number" class="form-control" id="default_max_ram" name="max_ram" placeholder="4096" value="{{ old('max_ram', $options['max_ram'] ?? 4096) }}" min="512" max="65536">
                </div>

                <button type="submit" class="btn btn-primary">{{ trans('centralcorp::messages.update') }}</button>
            </form>
        </div>
    </div>
@endsection
