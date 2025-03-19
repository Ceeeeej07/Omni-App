<div>
    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="flex items-center justify-between p-6">
                    <h2 class="text-xl font-semibold leading-tight text-gray-800">
                        Inbox
                    </h2>
                    <a href="{{ route('compose') }}" class="px-4 py-2 font-medium text-gray-600 bg-gray-200 rounded hover:bg-gray-300 hover:text-gray-800">
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
                            @forelse($emails as $email)
                            <tr class="border-b hover:bg-gray-100">
                                <td class="p-2">
                                    {{ $email->sender_email ?? 'Unknown Sender' }}
                                </td>
                                <td class="p-2">
                                    <a href="#" class="text-blue-500">
                                        {{ $email->subject ?? 'No Subject' }}
                                    </a>
                                </td>
                                <td class="p-2">{{ $email->created_at ? $email->created_at->format('d M Y') : 'Unknown Date' }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="p-4 text-center text-gray-500">
                                    No received emails found.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
