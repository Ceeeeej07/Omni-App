<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Mail box
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 flex justify-between items-center">
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                        Inbox
                    </h2>
                    <a href="#" class="bg-gray-200 hover:bg-gray-300 text-gray-600 hover:text-gray-800 font-medium py-2 px-4 rounded">
                        <i class="fas fa-plus"></i> Compose
                    </a>
                </div>
                <div class="p-4 bg-white rounded-lg shadow">
                    <table class="w-full border">
                        <thead>
                            <tr class="border-b">
                                <th class="p-2 text-left">From</th>
                                <th class="p-2 text-left">Subject</th>
                                <th class="p-2 text-left">Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- @foreach($emails as $email)
                            <tr class="border-b">
                                <td class="p-2">{{ $email->recipient_email }}</td>
                            <td class="p-2">
                                <a href="{{ route('email.view', $email->id) }}" class="text-blue-500">
                                    {{ $email->subject }}
                                </a>
                            </td>
                            <td class="p-2">{{ $email->created_at->format('d M Y') }}</td>
                            </tr>
                            @endforeach --}}
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
