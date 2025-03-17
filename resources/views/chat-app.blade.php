<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Chat-App
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                        CONTACTS
                    </h2>
                    @foreach ($users as $user)
                    <div class="flex items-center justify-between mt-4">
                        <a class="flex items-center" href="{{ route('chat', $user->id) }}">
                            <div class="flex-shrink-0">
                                <img class="h-12 w-12 rounded-full" src="\img\acfa576b3c99ac03e9d6c048e57eb5b9.jpg" alt="Sample">
                            </div>
                            <div class="ms-4">
                                <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                <div class="text-sm text-gray-500">{{ $user->email }}</div>
                            </div>
                        </a>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
