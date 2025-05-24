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
                    <div id="servers-container">
                        @foreach ($servers as $index => $server)
                            <div class="server-block mb-4 border p-3">
                                <input type="hidden" name="servers[{{ $index }}][server_id]"
                                    value="{{ $server->id }}">
                                <h5>{{ $server->name }} ({{ $server->address }}:{{ $server->port }})</h5>

                                <div class="mb-3">
                                    <label for="icon-{{ $index }}" class="form-label">{{trans("centralcorp::messages.server_icon")}}</label>
                                    <input type="file" class="form-control" name="servers[{{ $index }}][icon]"
                                        id="icon-{{ $index }}">
                                </div>

                                @if (isset($serverOptions[$server->id]) && $serverOptions[$server->id]->icon)
                                    <img src="{{ url($serverOptions[$server->id]->icon) }}" class="img-thumbnail"
                                        style="max-width: 100px;">
                                @endif
                            </div>
                        @endforeach
                    </div>
                    <button type="submit" class="btn btn-primary">Зберегти</button>
                </form>
            </div>
        </div>
    </div>
@endsection
