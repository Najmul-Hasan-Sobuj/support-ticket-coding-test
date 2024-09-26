<x-app-layout>
    <div class="p-10">
        <div class="flex justify-between items-center">
            <div class="text-3xl font-semibold text-gray-500">User Table</div>
            <div>
                <a href="{{ route('user.create') }}" class="bg-[#36568b] hover:bg-[#556e9c] text-white font-bold py-1 px-3 rounded-lg">Create</a>
            </div>
        </div>

        <div class="overflow-y-auto max-w-full">
            <table id="user-table" class="display">
                <thead>
                    <tr>
                        <th>Sl</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Roles</th>
                        <th>Actions</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    @push('scripts')
        <script>
            $(document).ready(function() {
                $('#user-table').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: '{{ route('user.index') }}',
                    columns: [
                        { data: 'DT_RowIndex', name: 'DT_RowIndex' },
                        { data: 'name', name: 'name' },
                        { data: 'email', name: 'email' },
                        { data: 'roles', name: 'roles', orderable: false, searchable: false },
                        { data: 'action', name: 'action', orderable: false, searchable: false }
                    ]
                });
            });
        </script>
    @endpush
</x-admin-app-layout>
