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
                                <th class="p-2 text-left">Subject</th>
                                <th class="p-2 text-left">Body</th>
                                <th class="p-2 text-left">Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($emails as $email)
                            <tr class="border-b">
                                <td class="p-2">{{ $email->subject }}</td>
                                <td class="p-2">{{ $email->body }}</td>
                                <td class="p-2">{{ $email->created_at->format('d M Y') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
