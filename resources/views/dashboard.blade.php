<x-admin-layout>
<x-slot:heading>Dashboard</x-slot:heading>
    <div class="bg-white py-24 sm:py-32">
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-16 text-center">
                <div class="mx-auto flex max-w-xs flex-col gap-y-4">
                    <dt class="text-base/7 text-gray-600">Users</dt>
                    <dd class="order-first text-5xl font-semibold tracking-tight text-gray-900 sm:text-9xl">
                        {{ $userCount }}
                    </dd>
                </div>
                <div class="mx-auto flex max-w-xs flex-col gap-y-4">
                    <dt class="text-base/7 text-gray-600">Photo Uploads</dt>
                    <dd class="order-first text-5xl font-semibold tracking-tight text-gray-900 sm:text-9xl">
                        {{ $photoUploadCount }}
                    </dd>
                </div>
            </dl>
        </div>
    </div>
</x-admin-layout>
