<x-admin-layout>
    <x-slot:heading>Users</x-slot:heading>
    <div class="bg-white">
        <div class="mx-auto max-w-7xl py-12 px-6 sm:px-6 lg:px-8">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="text-base/7 font-semibold text-gray-900">User List</h2>
                <div class="mt-6 flex items-center justify-end gap-x-6">
                    <button id="download-xlsx"
                            class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-xs hover:bg-indigo-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                        Download as Excel
                    </button>
                </div>
            </div>
            <table class="table table-bordered w-100" id="users-table" style="width:100%">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Phone Number</th>
                    <th>Email</th>
                    <th>Instagram</th>
                </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
    @push('styles')
        <!-- DataTables CSS -->
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
        <!-- DataTables Responsive CSS -->
        <link href="https://cdn.datatables.net/responsive/2.3.0/css/responsive.dataTables.min.css" rel="stylesheet">
    @endpush

    @push('scripts')
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
        <!-- DataTables JS -->
        <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
        <!-- DataTables Responsive JS -->
        <script src="https://cdn.datatables.net/responsive/2.3.0/js/dataTables.responsive.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
        <script>
            $(document).ready(function () {
                // Initialize DataTable
                const table = $('#users-table').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: "{{ route('admin.users.data') }}", // Fetch users via this route
                    columns: [
                        {data: 'id', name: 'id'},
                        {data: 'phone_number', name: 'phone_number'},
                        {data: 'email', name: 'email'},
                        {data: 'name', name: 'name'},
                        {data: 'instagram_id', name: 'instagram', orderable: false},
                    ],
                    lengthMenu: [25, 50, 100],
                    pageLength: 25,
                    responsive: true
                });

                // Export data to Excel
                $('#download-xlsx').on('click', function () {
                    // Fetch all data from the server
                    $.ajax({
                        url: "{{ route('admin.users.export') }}", // Backend route for export
                        method: "GET",
                        success: function (data) {
                            // Prepare worksheet data
                            const worksheetData = [["ID", "Phone Number", "Email", "Name", "Instagram"]];
                            data.forEach(item => {
                                worksheetData.push([
                                    item.id,
                                    item.phone_number,
                                    item.email,
                                    item.name,
                                    item.instagram_id,
                                ]);
                            });

                            // Create worksheet and workbook
                            const worksheet = XLSX.utils.aoa_to_sheet(worksheetData);
                            const workbook = XLSX.utils.book_new();
                            XLSX.utils.book_append_sheet(workbook, worksheet, "Users");

                            // Export to XLSX
                            XLSX.writeFile(workbook, "users_list.xlsx");
                        },
                        error: function () {
                            alert("Failed to download data. Please try again.");
                        }
                    });
                });
            });
        </script>
    @endpush
</x-admin-layout>



