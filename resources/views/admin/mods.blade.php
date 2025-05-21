@extends('admin.layouts.admin')

@section('title', trans('centralcorp::messages.mods_title'))

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
                <div class="mb-3">
                    <label for="optionalMods" class="form-label">{{ trans('centralcorp::messages.select_optional_mod') }}</label>
                    <select id="optionalMods" name="selectedMod" class="form-select" onchange="handleSelectChange()">
                        <option value="">{{ trans('centralcorp::messages.select_mod') }}</option>
                        @foreach ($optionalMods as $mod)
                            <option value="{{ $mod->id }}" {{ old('selectedMod', $selectedModId) == $mod->id ? 'selected' : '' }}>
                                {{ $mod->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div id="modDetails" class="{{ $selectedModId ? '' : 'hidden' }}">
                    <h3 class="text-2xl font-semibold mb-3">{{ trans('centralcorp::messages.mod_details') }}</h3>
                    <form method="post" action="{{ route('centralcorp.admin.mods.updateOptional') }}" enctype="multipart/form-data" id="modForm" onsubmit="return validateForm()">
                        @csrf
                        <input type="hidden" name="mod_id" id="mod_id" value="{{ $selectedModId ?? '' }}">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-2">
                            <div>
                                <label class="form-label">{{ trans('centralcorp::messages.mod_file') }}</label>
                                <input type="text" id="mod_file" class="form-control" readonly>
                            </div>
                            <div>
                                <label class="form-label">{{ trans('centralcorp::messages.mod_name') }}</label>
                                <input type="text" name="optional_name" id="optional_name" class="form-control">
                            </div>
                        </div>
                        <div class="mb-2">
                            <label class="form-label">{{ trans('centralcorp::messages.description') }}</label>
                            <textarea name="optional_description" id="optional_description" class="form-control"></textarea>
                        </div>
                        <div class="mb-2">
                            <label class="form-label">{{ trans('centralcorp::messages.current_image') }}</label>
                            <img id="current_image" src="" alt="" class="rounded mb-2 hidden" style="height: 64px; width: 64px; object-fit: cover;">
                        </div>
                        <div class="mb-2">
                            <label class="form-label">{{ trans('centralcorp::messages.new_image') }}</label>
                            <input type="file" name="optional_image" accept="image/jpeg,image/png,image/gif" class="form-control">
                        </div>
                        <div class="form-check form-switch">
                            <input type="checkbox" name="optional_recommended" value="1" id="optional_recommended" class="form-check-input">
                            <label class="form-check-label">{{ trans('centralcorp::messages.recommended') }}</label>
                        </div>
                        <div class="d-flex gap-1">
                            <button type="submit" class="btn btn-success rounded">{{ trans('centralcorp::messages.update') }}</button>
                            <button type="button" id="deleteBtn" class="btn btn-danger rounded" onclick="deleteMod()">{{ trans('centralcorp::messages.delete') }}</button>
                        </div>
                    </form>
                </div>

                <h3 class="text-2xl font-semibold mt-4">{{ trans('centralcorp::messages.available_mods') }}</h3>
                <ul class="list-group">
                    @foreach ($modsData as $index => $mod)
                        @if (!in_array($mod['file'], $optionalMods->pluck('file')->toArray()))
                            <li class="list-group-item d-flex justify-content-between align-items-center mb-2">
                                <form method="post" action="{{ route('centralcorp.admin.mods.addOptional') }}" class="d-flex align-items-center" enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="file" value="{{ $mod['file'] }}">
                                    <input type="hidden" name="name" value="{{ $mod['name'] }}">
                                    <input type="hidden" name="description" value="{{ $mod['description'] }}">
                                    <input type="hidden" name="icon" value="{{ $mod['icon'] }}">
                                    <span class="me-2">{{ $mod['name'] }}</span>
                                    <button type="submit" class="btn btn-primary rounded">{{ trans('centralcorp::messages.add_as_optional_mod') }}</button>
                                </form>
                            </li>
                        @endif
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        const optionalModsSelect = document.getElementById('optionalMods');
        const modDetails = document.getElementById('modDetails');
        const modIdInput = document.getElementById('mod_id');
        const fileInput = document.getElementById('mod_file');
        const nameInput = document.getElementById('optional_name');
        const descriptionInput = document.getElementById('optional_description');
        const currentImage = document.getElementById('current_image');
        const recommendedCheckbox = document.getElementById('optional_recommended');

        function handleSelectChange() {
            const selectedModId = optionalModsSelect.value;

            if (selectedModId) {
                fetch(`mods/${selectedModId}`)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('{{ trans('centralcorp::messages.fetch_error') }}');
                        }
                        return response.json();
                    })
                    .then(data => {
                        modIdInput.value = data.id;
                        fileInput.value = data.file;
                        nameInput.value = data.name;
                        descriptionInput.value = data.description;
                        currentImage.src = '{{ url('storage') }}/' + data.icon;
                        currentImage.classList.remove('hidden');
                        recommendedCheckbox.checked = data.recommended;

                        modDetails.classList.remove('hidden');
                    })
                    .catch(error => {
                        console.error('{{ trans('centralcorp::messages.fetch_error') }}:', error);
                    });
            } else {
                modIdInput.value = '';
                fileInput.value = '';
                nameInput.value = '';
                descriptionInput.value = '';
                currentImage.src = '';
                currentImage.classList.add('hidden');
                recommendedCheckbox.checked = false;
                modDetails.classList.add('hidden');
            }
        }

        function validateForm() {
            const selectedModId = optionalModsSelect.value;
            if (!selectedModId) {
                Swal.fire('{{ trans('centralcorp::messages.error') }}', '{{ trans('centralcorp::messages.select_mod_to_edit') }}', 'error');
                return false;
            }
            return true;
        }

        function deleteMod() {
            const selectedModId = optionalModsSelect.value;
            if (!selectedModId) {
                Swal.fire('{{ trans('centralcorp::messages.error') }}', '{{ trans('centralcorp::messages.select_mod_to_delete') }}', 'error');
                return;
            }

            Swal.fire({
                title: '{{ trans('centralcorp::messages.are_you_sure') }}',
                text: "{{ trans('centralcorp::messages.action_cannot_be_undone') }}",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: '{{ trans('centralcorp::messages.yes_delete') }}',
                cancelButtonText: '{{ trans('centralcorp::messages.cancel') }}'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/admin/centralcorp/mods/delete/${selectedModId}`;

                    const csrfInput = document.createElement('input');
                    csrfInput.type = 'hidden';
                    csrfInput.name = '_token';
                    csrfInput.value = '{{ csrf_token() }}';

                    form.appendChild(csrfInput);
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }
    </script>
@endsection
