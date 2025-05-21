@extends('admin.layouts.admin')

@section('title', trans('centralcorp::messages.server_title'))

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
                <form action="{{ route('centralcorp.admin.server.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="server_id" class="form-label">{{ trans('centralcorp::messages.server_name') }}</label>
                        <select class="form-control" id="server_id" name="server_id" required>
                            @foreach($servers as $server)
                                <option value="{{ $server->id }}" {{ $currentServer && $server->id == $currentServer->id ? 'selected' : '' }}>
                                    {{ $server->name }} ({{ $server->address }}:{{ $server->port }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="icon" class="form-label">{{ trans('centralcorp::messages.server_icon') }}</label>
                        <input type="file" class="form-control" id="icon" name="icon" accept="image/*">
                    </div>
                    @if($serverOptions && $serverOptions->icon)
                        <div class="mb-3">
                            <label class="form-label">{{ trans('centralcorp::messages.current_icon') }}</label>
                            <img src="{{ url($serverOptions->icon) }}" alt="Icône du serveur" class="img-thumbnail" style="max-width: 100px; max-height: 100px;">
                        </div>
                    @endif
                    <button type="submit" class="btn btn-primary">{{ trans('centralcorp::messages.save') }}</button>
                </form>
            </div>
        </div>
    </div>
@endsection
