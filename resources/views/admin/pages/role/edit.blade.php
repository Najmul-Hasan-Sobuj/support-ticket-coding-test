<x-app-layout>
    <div class="grid place-items-center my-10">
        <div
            class="w-full p-4 bg-white border border-gray-200 rounded-lg shadow sm:p-6 md:p-8  dark:border-gray-700">
            <div class="grid grid-cols-3 gap-4">
                <div class="">Role Edit</div>
                <div></div>
                <div class="justify-self-end">
                    <a href="{{ route('role.index') }}"
                        class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">Back</a>
                </div>
            </div>
        </div>
    </div>
    <div class="permissions-container">
            <form method="POST" action="{{ route('role.update', $role->id) }}"
                class="bg-white shadow-xl border rounded px-8 pt-6 pb-8 mb-4 grid gap-4 min-w-lg">
                @csrf
                @method('PUT')
                <!-- Role name input -->
                <div>
                    <x-input-label for="name" :value="__(' Name ')" />
                    <x-text-input id="name" class="block mt-1 w-full" type="text" name="name"
                        :value="$role->name" required autofocus autocomplete="name" />
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>
                <!-- Group permissions -->
                @foreach ($groups as $group)
                    <div class="bg-gray-100 p-4 rounded-lg">
                        <div class="flex items-center mb-5">
                            <input type="checkbox" id="selectAllGroupPermissions_{{ $loop->iteration }}"
                                class="mr-2 form-checkbox h-4 w-4 text-indigo-600 transition duration-150 ease-in-out select-all-group-permissions">
                            <label for="selectAllGroupPermissions_{{ $loop->iteration }}"
                                class="text-sm font-medium text-gray-700">
                                <h2 class="text-lg font-semibold mb-2">{{ $group->group_name }}</h2>
                            </label>
                        </div>
                        <div class="grid md:grid-cols-3 grid-cols-1 gap-4">
                            @foreach ($permissions as $permission)
                                @if ($permission->group_name == $group->group_name)
                                    <div class="flex items-center">
                                        <input type="checkbox" id="permission-{{ $permission->id }}"
                                            name="permissions[]" value="{{ $permission->name }}"
                                            class="mr-2 form-checkbox h-4 w-4 text-indigo-600 transition duration-150 ease-in-out permission-checkbox"
                                            data-group="{{ $loop->parent->index }}"
                                            {{ $role->permissions->contains($permission) ? 'checked' : '' }}>
                                        <label for="permission-{{ $permission->id }}"
                                            class="text-sm font-medium text-gray-700">{{ $permission->name }}</label>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endforeach
                <!-- Select All checkbox for all permissions -->
                <div class="flex items-center">
                    <input type="checkbox" id="selectAllPermissions"
                        class="mr-2 form-checkbox h-4 w-4 text-indigo-600 transition duration-150 ease-in-out">
                    <label for="selectAllPermissions" class="text-sm font-medium text-gray-700">Select All</label>
                </div>
                <!-- Submit button -->
                <div class="flex items-center justify-center">
                    <button
                        class="btn bg-[#5d77ed] text-white w-full font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
                        type="submit">Submit</button>
                </div>
            </form>
        </div>
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Select All checkbox for group permissions
                const selectAllGroupPermissions = document.querySelectorAll('.select-all-group-permissions');
                const permissionCheckboxes = document.querySelectorAll('.permission-checkbox');
                const selectAllPermissions = document.getElementById('selectAllPermissions');

                selectAllGroupPermissions.forEach(function(selectAllCheckbox, index) {
                    selectAllCheckbox.addEventListener('change', function() {
                        const groupPermissionCheckboxes = document.querySelectorAll(
                            '.permission-checkbox[data-group="' + index + '"]');
                        groupPermissionCheckboxes.forEach(function(checkbox) {
                            checkbox.checked = selectAllCheckbox.checked;
                        });
                        updateSelectAllPermissionsState();
                    });
                });

                selectAllPermissions.addEventListener('change', function() {
                    permissionCheckboxes.forEach(function(checkbox) {
                        checkbox.checked = selectAllPermissions.checked;
                    });
                    updateSelectAllGroupPermissionsState(selectAllPermissions.checked);
                });

                function updateSelectAllPermissionsState() {
                    const allPermissionsChecked = Array.from(permissionCheckboxes).every(function(checkbox) {
                        return checkbox.checked;
                    });
                    selectAllPermissions.checked = allPermissionsChecked;
                }

                function updateSelectAllGroupPermissionsState(checked) {
                    selectAllGroupPermissions.forEach(function(selectAllCheckbox) {
                        selectAllCheckbox.checked = checked;
                    });
                }

                // Check initial states after page load
                updateSelectAllPermissionsState();
                updateSelectAllGroupPermissionsState(selectAllPermissions.checked);
            });
        </script>
    @endpush
</x-admin-app-layout>
