<div>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Chatting with {{ $user->name }}
            </h2>
            <a href="{{ route('chat-app') }}" class="hover:bg-gray-200 text-gray-600 hover:text-gray-800 font-medium py-2 px-4 rounded">
                Back
            </a>
        </div>
    </x-slot>

    <div class="flex items-center justify-center py-12 bg-gray-100">
        <div class="w-full max-w-4xl px-6 mx-auto">
            <div class="bg-white shadow-lg rounded-2xl p-6 flex flex-col h-[500px]">
                <div class="flex flex-col flex-1 p-4 overflow-y-auto rounded-md bg-gray-50" style="height: 400px;">
                    <div class="flex flex-col space-y-2">
                        {{-- @foreach ($messages as $message)
                        @if ($message['sender'] != auth()->user()->name)

                        <div class="flex justify-start">
                            <p class="max-w-xs px-4 py-2 text-gray-800 bg-gray-200 rounded-lg">
                                {{ $message['message'] }}
                        </p>
                    </div>
                    @else

                    <div class="flex justify-end">
                        <p class="max-w-xs px-4 py-2 text-white bg-blue-500 rounded-lg">
                            {{ $message['message'] }}
                        </p>
                    </div>

                    @endif
                    @endforeach --}}
                </div>
            </div>


            <form wire:submit="sendMessage()">
                <div class="flex items-center gap-2 p-4 bg-white border-t">
                    <input type="text" wire:model="message" placeholder="Type a message..." class="w-full p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" />
                    <button class="px-4 py-2 text-white transition bg-blue-500 rounded-lg hover:bg-blue-600">
                        Send
                    </button>
            </form>
        </div>
    </div>
</div>
</div>

</div>
