<x-admin-layout>
    <x-slot:heading>Votes</x-slot:heading>

    <div class="bg-white">
        <div class="mx-auto max-w-7xl py-6 px-6 sm:px-6 lg:px-8">
            <h2 class="text-base/7 font-semibold text-gray-900 mb-4">Vote Count by Shop</h2>

            <div class="overflow-hidden rounded-lg border border-gray-200 mb-8">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Shop</th>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Shop (Bangla)</th>
                        <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Total Votes</th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                    @foreach($shopVoteCounts as $shop)
                        <tr>
                            <td class="px-4 py-3 text-sm text-gray-900">{{ $shop['shop_name'] }}</td>
                            <td class="px-4 py-3 text-sm text-gray-700">{{ $shop['shop_name_bn'] }}</td>
                            <td class="px-4 py-3 text-sm font-semibold text-indigo-700">{{ $shop['total_votes'] }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            <h2 class="text-base/7 font-semibold text-gray-900 mb-4">Vote List</h2>
            <table class="table table-bordered w-100" id="votes-table" style="width:100%">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Phone</th>
                    <th>Shop ID</th>
                    <th>Shop Name</th>
                    <th>Voted At</th>
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
                $('#votes-table').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: "{{ route('admin.votes.data') }}",
                    columns: [
                        {data: 'id', name: 'id'},
                        {data: 'phone', name: 'phone'},
                        {data: 'shop_id', name: 'shop_id'},
                        {data: 'shop_name', name: 'shop_name'},
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

