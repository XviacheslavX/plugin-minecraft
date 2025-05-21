@extends('admin.layouts.admin')

@section('title', trans('centralcorp::messages.whitelist_title'))

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
                <form action="{{ route('centralcorp.admin.whitelist.store') }}" method="POST">
                    @csrf
                    <div class="form-check form-switch">
                        <input type="hidden" name="whitelist" value="0">
                        <label for="whitelist" class="form-label">{{ trans('centralcorp::messages.whitelist_enable') }}</label>
                        <input type="checkbox" id="whitelist" name="whitelist" class="form-check-input" value="1" {{ $whitelistEnabled ? 'checked' : '' }}>
                    </div>

                    <div class="mb-3">
                        <label for="whitelist_users" class="form-label">{{ trans('centralcorp::messages.whitelist_add_users') }}</label>
                        <select class="form-select" id="whitelist_users" name="whitelist_users[]" multiple>
                            @foreach($allUsers as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="whitelist_roles" class="form-label">{{ trans('centralcorp::messages.whitelist_add_roles') }}</label>
                        <select class="form-select" id="whitelist_roles" name="whitelist_roles[]" multiple>
                            @foreach($allRoles as $role)
                                <option value="{{ $role->id }}">{{ $role->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary">{{ trans('centralcorp::messages.save') }}</button>
                </form>
            </div>
        </div>

        <h2 class="mt-5">{{ trans('centralcorp::messages.whitelist_users') }}</h2>
        <div class="card shadow mb-4">
            <div class="card-body">
                <ul class="list-group mb-4">
                    @foreach($whitelistedUsers as $user)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span class="me-2">{{ $user->users }}</span>
                            <form action="{{ route('centralcorp.admin.whitelist.destroyUser', $user->id) }}" method="POST" class="ms-2">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">{{ trans('centralcorp::messages.remove') }}</button>
                            </form>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>

        <h2 class="mt-5">{{ trans('centralcorp::messages.whitelist_roles') }}</h2>
        <div class="card shadow mb-4">
            <div class="card-body">
                <ul class="list-group">
                    @foreach($whitelistedRoles as $role)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span class="me-2">{{ $role->role }}</span>
                            <form action="{{ route('centralcorp.admin.whitelist.destroyRole', $role->id) }}" method="POST" class="ms-2">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">{{ trans('centralcorp::messages.remove') }}</button>
                            </form>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
@endsection
