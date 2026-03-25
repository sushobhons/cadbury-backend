<x-admin-layout>
    <x-slot:heading>Photo Uploads</x-slot:heading>

    <div class="bg-white">
        <div class="mx-auto max-w-7xl py-12 px-6 sm:px-6 lg:px-8">
            <h2 class="text-base/7 font-semibold text-gray-900 mb-4">Kiosk Upload List</h2>

            <table class="table table-bordered w-100" id="photo-uploads-table" style="width:100%">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Phone</th>
                    <th>Theme</th>
                    <th>Image</th>
                    <th>Link</th>
                    <th>Uploaded At</th>
                </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>

    @push('styles')
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
        <link href="https://cdn.datatables.net/responsive/2.3.0/css/responsive.dataTables.min.css" rel="stylesheet">
    @endpush

    @push('scripts')
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/responsive/2.3.0/js/dataTables.responsive.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

        <script>
            $(document).ready(function () {
                $('#photo-uploads-table').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: "{{ route('admin.photo-uploads.data') }}",
                    columns: [
                        {data: 'id', name: 'id'},
                        {data: 'phone', name: 'phone'},
                        {data: 'theme_name', name: 'theme_id', orderable: false},
                        {data: 'image_preview', name: 'image_preview', orderable: false, searchable: false},
                        {data: 'image_link', name: 'image_link', orderable: false, searchable: false},
                        {data: 'created_at', name: 'created_at'}
                    ],
                    lengthMenu: [25, 50, 100],
                    pageLength: 25,
                    responsive: true
                });
            });
        </script>
    @endpush
</x-admin-layout>

