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

<form method="POST" action="{{ route('centralcorp.admin.loader.update') }}">
    @csrf

    @foreach ($servers as $server)
        @php $opts = $options[$server->id] ?? []; @endphp

        <div class="card mb-4 server-settings shadow" data-server-id="{{ $server->id }}">
            <div class="card-header">
                <strong>{{ $server->name }}</strong>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label for="minecraft_version_{{ $server->id }}" class="form-label">{{ trans('centralcorp::messages.minecraft_version') }}</label>
                    <input type="text" class="form-control" id="minecraft_version_{{ $server->id }}" name="servers[{{ $server->id }}][minecraft_version]" placeholder="{{ trans('centralcorp::messages.minecraft_version_placeholder') }}" value="{{ old("servers.$server->id.minecraft_version", $opts['minecraft_version'] ?? '') }}">
                </div>

                <div class="mb-3">
                    <div class="form-check form-switch">
                        <label for="loader_activation_{{ $server->id }}" class="form-label">{{ trans('centralcorp::messages.loader_activation') }}</label>
                        <input type="hidden" name="servers[{{ $server->id }}][loader_activation]" value="0">
                        <input type="checkbox" id="loader_activation_{{ $server->id }}" name="servers[{{ $server->id }}][loader_activation]" class="form-check-input" value="1" {{ (old("servers.$server->id.loader_activation", $opts['loader_activation'] ?? false)) ? 'checked' : '' }}>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="loader_type_{{ $server->id }}" class="form-label">{{ trans('centralcorp::messages.loader_type') }}</label>
                    <select class="form-select" id="loader_type_{{ $server->id }}" name="servers[{{ $server->id }}][loader_type]">
                        <option value="forge" {{ (old("servers.$server->id.loader_type", $opts['loader_type'] ?? '') === 'forge') ? 'selected' : '' }}>Forge</option>
                        <option value="fabric" {{ (old("servers.$server->id.loader_type", $opts['loader_type'] ?? '') === 'fabric') ? 'selected' : '' }}>Fabric</option>
                        <option value="legacyfabric" {{ (old("servers.$server->id.loader_type", $opts['loader_type'] ?? '') === 'legacyfabric') ? 'selected' : '' }}>LegacyFabric</option>
                        <option value="neoForge" {{ (old("servers.$server->id.loader_type", $opts['loader_type'] ?? '') === 'neoForge') ? 'selected' : '' }}>NeoForge</option>
                        <option value="quilt" {{ (old("servers.$server->id.loader_type", $opts['loader_type'] ?? '') === 'quilt') ? 'selected' : '' }}>Quilt</option>
                    </select>
                </div>

                <div class="mb-3" id="forge-version-container-{{ $server->id }}" style="display:none;">
                    <label for="loader_forge_version_{{ $server->id }}" class="form-label">{{ trans('centralcorp::messages.loader_forge_version') }}</label>
                    <select class="form-select" id="loader_forge_version_{{ $server->id }}" name="servers[{{ $server->id }}][loader_forge_version]">
                        @if(isset($opts['loader_forge_version']))
                            <option value="{{ $opts['loader_forge_version'] }}" selected>{{ $opts['loader_forge_version'] }}</option>
                        @endif
                    </select>
                </div>

                <div class="mb-3" id="fabric-version-container-{{ $server->id }}" style="display:none;">
                    <label for="loader_fabric_version_{{ $server->id }}" class="form-label">{{ trans('centralcorp::messages.loader_fabric_version') }}</label>
                    <select class="form-select" id="loader_fabric_version_{{ $server->id }}" name="servers[{{ $server->id }}][loader_fabric_version]">
                        @if(isset($opts['loader_fabric_version']))
                            <option value="{{ $opts['loader_fabric_version'] }}" selected>{{ $opts['loader_fabric_version'] }}</option>
                        @endif
                    </select>
                </div>

                <div class="mb-3" id="manual-build-version-container-{{ $server->id }}" style="display:none;">
                    <label for="loader_build_version_{{ $server->id }}" class="form-label">{{ trans('centralcorp::messages.loader_build_version') }}</label>
                    <input type="text" class="form-control" id="loader_build_version_{{ $server->id }}" name="servers[{{ $server->id }}][loader_build_version]" value="{{ old("servers.$server->id.loader_build_version", $opts['loader_build_version'] ?? '') }}">
                </div>
            </div>
        </div>
    @endforeach

    <button type="submit" class="btn btn-primary">{{ trans('centralcorp::messages.save') }}</button>
</form>

<script>
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.server-settings').forEach(serverCard => {
        const serverId = serverCard.getAttribute('data-server-id');

        const loaderTypeSelect = document.getElementById(`loader_type_${serverId}`);
        const mcVersionInput = document.getElementById(`minecraft_version_${serverId}`);
        const loaderForgeVersionSelect = document.getElementById(`loader_forge_version_${serverId}`);
        const loaderFabricVersionSelect = document.getElementById(`loader_fabric_version_${serverId}`);
        const forgeVersionContainer = document.getElementById(`forge-version-container-${serverId}`);
        const fabricVersionContainer = document.getElementById(`fabric-version-container-${serverId}`);
        const manualBuildVersionContainer = document.getElementById(`manual-build-version-container-${serverId}`);
        const manualBuildVersionInput = document.getElementById(`loader_build_version_${serverId}`);

        function toggleFields() {
            const selectedType = loaderTypeSelect.value;

            forgeVersionContainer.style.display = (selectedType === 'forge') ? 'block' : 'none';
            fabricVersionContainer.style.display = (selectedType === 'fabric') ? 'block' : 'none';
            manualBuildVersionContainer.style.display = (['legacyfabric', 'neoForge', 'quilt'].includes(selectedType)) ? 'block' : 'none';

            loaderForgeVersionSelect.disabled = (selectedType !== 'forge');
            loaderFabricVersionSelect.disabled = (selectedType !== 'fabric');
            manualBuildVersionInput.disabled = !(['legacyfabric', 'neoForge', 'quilt'].includes(selectedType));
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

        function fetchForgeBuildVersions(mcVersion) {
            const apiUrl = `/admin/centralcorp/loader/builds?loader=forge&mc_version=${encodeURIComponent(mcVersion)}`;
            fetch(apiUrl)
                .then(response => response.json())
                .then(data => {
                    updateLoaderBuildVersions(data.builds || [], loaderForgeVersionSelect);
                })
                .catch(() => {
                    console.error('Error fetching Forge builds');
                });
        }

        function fetchFabricVersions() {
            const apiUrl = `/admin/centralcorp/loader/fabric-versions`;
            fetch(apiUrl)
                .then(response => response.json())
                .then(data => {
                    const versions = (data.versions || []).map(v => v.version);
                    updateLoaderBuildVersions(versions, loaderFabricVersionSelect);
                })
                .catch(() => {
                    console.error('Error fetching Fabric versions');
                });
        }

        loaderTypeSelect.addEventListener('change', () => {
            toggleFields();
            if (loaderTypeSelect.value === 'forge') {
                fetchForgeBuildVersions(mcVersionInput.value);
            } else if (loaderTypeSelect.value === 'fabric') {
                fetchFabricVersions();
            }
        });

        mcVersionInput.addEventListener('change', () => {
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
});
</script>
@endsection