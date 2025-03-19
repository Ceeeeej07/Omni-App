<div class="p-6 bg-white rounded-lg shadow-md">

    <!-- Message Sending Form -->
    <div class="mb-6">
        <h2 class="mb-4 text-2xl font-bold">Send SMS</h2>
        <form wire:submit.prevent="sendMessage">
            <div class="mb-4">
                <label for="phoneNumber" class="block text-sm font-medium text-gray-700">Phone Number</label>
                <input type="text" id="phoneNumber" wire:model="phoneNumber" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="+12345678901">
                @error('phoneNumber') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
            </div>

            <div class="mb-4">
                <label for="messageBody" class="block text-sm font-medium text-gray-700">Message</label>
                <textarea id="messageBody" wire:model="messageBody" rows="4" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="Type your message here..."></textarea>
                @error('messageBody') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
            </div>

            <button type="submit" class="inline-flex justify-center px-4 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Send Message
            </button>
        </form>

        @if ($success)
        <div class="relative px-4 py-3 mt-4 text-green-700 bg-green-100 border border-green-400 rounded" role="alert">
            <strong class="font-bold">Success!</strong>
            <span class="block sm:inline">{{ $success }}</span>
        </div>
        @endif

        @if ($error)
        <div class="relative px-4 py-3 mt-4 text-red-700 bg-red-100 border border-red-400 rounded" role="alert">
            <strong class="font-bold">Error!</strong>
            <span class="block sm:inline">{{ $error }}</span>
        </div>
        @endif
    </div>

    <!-- Message History -->
    <div>
        <h2 class="mb-4 text-2xl font-bold">Message History</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Direction</th>
                        <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">From</th>
                        <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">To</th>
                        <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Message</th>
                        <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Status</th>
                        <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Date</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($messages as $message)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium 
                                {{ $message['direction'] === 'inbound' ? 'text-green-600' : 'text-blue-600' }}">
                            {{ ucfirst($message['direction']) }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">{{ $message['from'] }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">{{ $message['to'] }}</td>
                        <td class="max-w-xs px-6 py-4 text-sm text-gray-500 truncate">{{ $message['body'] }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">{{ ucfirst($message['status']) }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                            {{ \Carbon\Carbon::parse($message['created_at'])->format('M d, Y H:i') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-sm text-center text-gray-500 whitespace-nowrap">No messages found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <button wire:click="loadMessages" class="inline-flex items-center px-4 py-2 mt-4 text-sm font-medium text-indigo-700 bg-indigo-100 border border-transparent rounded-md hover:bg-indigo-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            Refresh Messages
        </button>
    </div>
</div>
