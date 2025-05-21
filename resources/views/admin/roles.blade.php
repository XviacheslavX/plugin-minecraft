@extends('admin.layouts.admin')

@section('title', trans('centralcorp::messages.roles_title'))

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
                <h2 class="text-3xl font-bold mb-4">{{ trans('centralcorp::messages.roles_settings') }}</h2>
                <form method="post" action="{{ route('centralcorp.admin.roles.update') }}" enctype="multipart/form-data" id="roles-form">
                    @csrf
                    <div class="mb-3">
                        <label for="role_select" class="form-label">{{ trans('centralcorp::messages.select_role') }}</label>
                        <select id="role_select" name="role_select" class="form-select">
                            <option value="">{{ trans('centralcorp::messages.select_role_placeholder') }}</option>
                            @foreach ($roles as $role)
                                <option value="{{ $role->id }}" {{ old('role_select') == $role->id ? 'selected' : '' }}>
                                    {{ $role->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div id="role_settings" class="mt-4" style="display: none;">
                        <h4 id="role-name"></h4>
                        <div class="mb-2">
                            <img id="role-icon" src="" alt="" class="rounded mb-2 hidden" style="height: 512px; width: 1024px; object-fit: cover;">
                        </div>
                        <label id="role-background-label" class="form-label" for="role_background">{{ trans('centralcorp::messages.choose_new_background') }}</label>
                        <input type="file" id="role_background" name="" accept="image/*" class="form-control">
                    </div>

                    <button type="submit" class="btn btn-primary mt-3">
                        <i class="bi bi-save"></i> {{ trans('centralcorp::messages.save') }}
                    </button>
                </form>
            </div>
        </div>
    <script>
        document.getElementById('role_select').addEventListener('change', function() {
            const selectedRoleId = this.value;
            const roleSettingsDiv = document.getElementById('role_settings');
            const roleName = document.getElementById('role-name');
            const roleIcon = document.getElementById('role-icon');
            const roleBackgroundInput = document.getElementById('role_background');

            if (selectedRoleId) {
                roleSettingsDiv.style.display = 'block';
                roleName.textContent = this.options[this.selectedIndex].text;

                roleBackgroundInput.name = `role${selectedRoleId}_background`;

                const backgroundUrl = @json($roles->pluck('role_background', 'id'));

                if (backgroundUrl[selectedRoleId]) {
                    roleIcon.src = backgroundUrl[selectedRoleId];
                    roleIcon.classList.remove('hidden');
                } else {
                    roleIcon.src = '';
                    roleIcon.classList.add('hidden');
                }
            } else {
                roleSettingsDiv.style.display = 'none';
                roleName.textContent = '';
                roleIcon.src = '';
                roleIcon.classList.add('hidden');
                roleBackgroundInput.name = '';
            }
        });

        document.getElementById('role_select').dispatchEvent(new Event('change'));
    </script>
@endsection
