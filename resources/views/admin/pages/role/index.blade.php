<x-app-layout>
    <div class="p-10">
        <div class="flex justify-between items-center mb-4">
            <div class="text-3xl font-semibold text-gray-500">Role Table</div>
            <div>
                <a class="bg-[#36568b] hover:bg-[#556e9c] text-white font-bold py-1 px-3 rounded-lg"
                   href="{{ route('role.create') }}">Create</a>
            </div>
        </div>

        <div class="overflow-y-auto max-w-full">
            <table id="role-table" class="display">
                <thead>
                    <tr>
                        <th>Sl</th>
                        <th>Name</th>
                        <th>Permission Name</th>
                        <th>Actions</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    @push('scripts')
        <script>
            $(document).ready(function() {
                $('#role-table').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: '{{ route('role.index') }}',
                    columns: [
                        { data: 'DT_RowIndex', name: 'DT_RowIndex' },
                        { data: 'name', name: 'name' },
                        {
                            data: 'permissions',
                            name: 'permissions',
                            render: function(data, type, row) {
                                return data.map(function(permission) {
                                    return '<span class="inline-block bg-gray-200 text-gray-700 text-sm font-semibold px-2 py-1 rounded-full mr-1">' + 
                                        permission.name + '</span>';
                                }).join('');
                            }
                        },
                        {
                            data: 'action',
                            name: 'action',
                            orderable: false,
                            searchable: false
                        }
                    ]
                });
            });
        </script>
    @endpush
</x-admin-app-layout>
