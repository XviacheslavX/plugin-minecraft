@extends('admin.layouts.admin')

@section('title', trans('centralcorp::messages.rpc_title'))

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
    
    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="{{ route('centralcorp.admin.rpc.update') }}" method="POST">
                @csrf
                <div class="form-body">
                    <div class="form-check form-switch">
                        <input type="hidden" name="rpc_activation" value="0">
                        <label for="rpc_activation" class="form-label">{{ trans('centralcorp::messages.rpc_enabled') }}</label>
                        <input type="checkbox" id="rpc_activation" name="rpc_activation" class="form-check-input" value="1" {{ $options['rpc_activation'] ?? false ? 'checked' : '' }}>
                    </div>

                    @foreach ([
                        'rpc_id', 'rpc_details', 'rpc_state', 
                        'rpc_large_image', 'rpc_large_text', 
                        'rpc_small_image', 'rpc_small_text', 
                        'rpc_button1', 'rpc_button1_url', 
                        'rpc_button2', 'rpc_button2_url'
                    ] as $field)
                        <div class="mb-3">
                            <label for="{{ $field }}" class="form-label">{{ trans("centralcorp::messages.$field") }}</label>
                            <input type="{{ str_contains($field, 'url') ? 'url' : 'text' }}" class="form-control" id="{{ $field }}" name="{{ $field }}" value="{{ $options[$field] ?? '' }}">
                        </div>
                    @endforeach

                    <button type="submit" class="btn btn-primary">{{ trans('centralcorp::messages.save') }}</button>
                </div>
            </form>
        </div>
    </div>
@endsection
