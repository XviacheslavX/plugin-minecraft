@extends('admin.layouts.admin')

@section('title', trans('centralcorp::messages.loader_settings'))

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
            <form method="POST" action="{{ route('centralcorp.admin.loader.update') }}">
                @csrf

                <div class="mb-3">
                    <label for="minecraft_version" class="form-label">{{ trans('centralcorp::messages.minecraft_version') }}</label>
                    <input type="text" class="form-control" id="minecraft_version" name="minecraft_version" placeholder="{{ trans('centralcorp::messages.minecraft_version_placeholder') }}" value="{{ old('minecraft_version', $options['minecraft_version'] ?? '') }}">
                </div>

                <div class="mb-3">
                    <div class="form-check form-switch">
                        <label for="loader-activation" class="form-label">{{ trans('centralcorp::messages.loader_activation') }}</label>
                        <input type="hidden" name="loader_activation" value="0">
                        <input type="checkbox" id="loader-activation" name="loader_activation" class="form-check-input" value="1" {{ ($options['loader_activation'] ?? false) ? 'checked' : '' }}>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="loader-type" class="form-label">{{ trans('centralcorp::messages.loader_type') }}</label>
                    <select class="form-select" id="loader-type" name="loader_type">
                        <option value="forge" {{ ($options['loader_type'] ?? '') === 'forge' ? 'selected' : '' }}>Forge</option>
                        <option value="fabric" {{ ($options['loader_type'] ?? '') === 'fabric' ? 'selected' : '' }}>Fabric</option>
                        <option value="legacyfabric" {{ ($options['loader_type'] ?? '') === 'legacyfabric' ? 'selected' : '' }}>LegacyFabric</option>
                        <option value="neoForge" {{ ($options['loader_type'] ?? '') === 'neoForge' ? 'selected' : '' }}>NeoForge</option>
                        <option value="quilt" {{ ($options['loader_type'] ?? '') === 'quilt' ? 'selected' : '' }}>Quilt</option>
                    </select>
                </div>

                <div class="mb-3" id="forge-version-container" style="display:none;">
                    <label for="loader-forge-version" class="form-label">{{ trans('centralcorp::messages.loader_forge_version') }}</label>
                    <select class="form-select" id="loader-forge-version" name="loader_forge_version">
                        @if(isset($options['loader_forge_version']))
                            <option value="{{ $options['loader_forge_version'] }}" selected>{{ $options['loader_forge_version'] }}</option>
                        @endif
                    </select>
                </div>

                <div class="mb-3" id="fabric-version-container" style="display:none;">
                    <label for="loader-fabric-version" class="form-label">{{ trans('centralcorp::messages.loader_fabric_version') }}</label>
                    <select class="form-select" id="loader-fabric-version" name="loader_fabric_version">
                        @if(isset($options['loader_fabric_version']))
                            <option value="{{ $options['loader_fabric_version'] }}" selected>{{ $options['loader_fabric_version'] }}</option>
                        @endif
                    </select>
                </div>

                <div class="mb-3" id="manual-build-version-container" style="display:none;">
                    <label for="manual-build-version" class="form-label">{{ trans('centralcorp::messages.loader_build_version') }}</label>
                    <input type="text" class="form-control" id="manual-build-version" name="loader_build_version" value="{{ old('loader_build_version', $options['loader_build_version'] ?? '') }}">
                </div>
                <button type="submit" class="btn btn-primary">{{ trans('centralcorp::messages.save') }}</button>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const loaderTypeSelect = document.getElementById('loader-type');
            const mcVersionInput = document.getElementById('minecraft_version');
            const loaderForgeVersionSelect = document.getElementById('loader-forge-version');
            const loaderFabricVersionSelect = document.getElementById('loader-fabric-version');
            const forgeVersionContainer = document.getElementById('forge-version-container');
            const fabricVersionContainer = document.getElementById('fabric-version-container');
            const manualBuildVersionContainer = document.getElementById('manual-build-version-container');
            const manualBuildVersionInput = document.getElementById('manual-build-version');

            function toggleFields() {
                const selectedType = loaderTypeSelect.value;

                forgeVersionContainer.style.display = (selectedType === 'forge') ? 'block' : 'none';
                fabricVersionContainer.style.display = (selectedType === 'fabric') ? 'block' : 'none';
                manualBuildVersionContainer.style.display = (['legacyfabric', 'neoForge', 'quilt'].includes(selectedType)) ? 'block' : 'none';

                loaderForgeVersionSelect.disabled = (selectedType !== 'forge');
                loaderFabricVersionSelect.disabled = (selectedType !== 'fabric');
                manualBuildVersionInput.disabled = !(['legacyfabric', 'neoForge', 'quilt'].includes(selectedType));
            }

            function fetchForgeBuildVersions(mcVersion) {
                const apiUrl = `/admin/centralcorp/loader/builds?loader=forge&mc_version=${mcVersion}`;
                fetch(apiUrl)
                    .then(response => response.json())
                    .then(data => {
                        updateLoaderBuildVersions(data.builds, loaderForgeVersionSelect);
                    })
                    .catch(error => {
                        console.error('{{ trans('centralcorp::messages.fetch_error') }}:', error);
                    });
            }

            function fetchFabricVersions() {
                const apiUrl = `/admin/centralcorp/loader/fabric-versions`;
                fetch(apiUrl)
                    .then(response => response.json())
                    .then(data => {
                        updateLoaderBuildVersions(data.versions.map(v => v.version), loaderFabricVersionSelect);
                    })
                    .catch(error => {
                        console.error('{{ trans('centralcorp::messages.fetch_error') }}:', error);
                    });
            }

            function updateLoaderBuildVersions(builds, selectElement) {
                const currentVersion = selectElement.value;
                selectElement.innerHTML = '';

                if (currentVersion) {
                    const selectedOption = document.createElement('option');
                    selectedOption.value = currentVersion;
                    selectedOption.textContent = currentVersion + " ({{ trans('centralcorp::messages.current') }})";
                    selectedOption.selected = true;
                    selectElement.appendChild(selectedOption);
                }

                builds.forEach(build => {
                    if (build !== currentVersion) {
                        const option = document.createElement('option');
                        option.value = build;
                        option.textContent = build;
                        selectElement.appendChild(option);
                    }
                });
            }

            loaderTypeSelect.addEventListener('change', function() {
                toggleFields();
                const selectedType = loaderTypeSelect.value;
                if (selectedType === 'forge') {
                    fetchForgeBuildVersions(mcVersionInput.value);
                } else if (selectedType === 'fabric') {
                    fetchFabricVersions();
                }
            });

            mcVersionInput.addEventListener('change', function() {
                if (loaderTypeSelect.value === 'forge') {
                    fetchForgeBuildVersions(mcVersionInput.value);
                }
            });

            toggleFields();
            if (loaderTypeSelect.value === 'forge') {
                fetchForgeBuildVersions(mcVersionInput.value);
            } else if (loaderTypeSelect.value === 'fabric') {
                fetchFabricVersions();
            }
        });
    </script>
@endsection
