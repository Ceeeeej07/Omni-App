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
                        @foreach ($messages as $message)
                        @if ($message['sender'] != auth()->user()->name)
                        <!-- Receiver's Message -->
                        <div class="flex items-start gap-3 justify-start">
                            <img src="/img/acfa576b3c99ac03e9d6c048e57eb5b9.jpg" alt="Sender" class="w-8 h-8 rounded-full">
                            <div class="bg-gray-200 text-gray-800 p-3 rounded-lg max-w-xs">
                                @if (!empty($message['message']))
                                <p>{{ $message['message'] }}</p>
                                @endif

                                @if (!empty($message['attachment']))
                                <div class="mt-2">
                                    @php
                                    $filePath = $message['attachment'];
                                    $fileName = basename($filePath); // Extract file name
                                    @endphp

                                    @if (preg_match('/\.(jpg|jpeg|png|gif|webp)$/i', $filePath))
                                    <!-- Display the image & make it clickable for download -->
                                    <a href="{{ $filePath }}" download="{{ $fileName }}" target="_blank">
                                        <img src="{{ $filePath }}" alt="Attachment" class="w-32 h-32 rounded-lg shadow">
                                    </a>
                                    @else
                                    <!-- Show a clickable link for other file types -->
                                    <a href="{{ $filePath }}" download="{{ $fileName }}" target="_blank" class="text-blue-500 underline flex items-center">
                                        ({{ $fileName }})
                                    </a>
                                    @endif
                                </div>
                                @endif

                            </div>
                        </div>
                        @else
                        <!-- Sender's Message -->
                        <div class="flex items-start gap-3 justify-end">
                            <div class="bg-blue-500 text-white p-3 rounded-lg max-w-xs">
                                @if (!empty($message['message']))
                                <p>{{ $message['message'] }}</p>
                                @endif

                                @if (!empty($message['attachment']))
                                <div class="mt-2">
                                    @php
                                    $filePath = $message['attachment'];
                                    $fileName = basename($filePath); // Extract file name
                                    @endphp

                                    @if (preg_match('/\.(jpg|jpeg|png|gif|webp)$/i', $filePath))
                                    <!-- Display the image & make it clickable for download -->
                                    <a href="{{ $filePath }}" download="{{ $fileName }}" target="_blank">
                                        <img src="{{ $filePath }}" alt="Attachment" class="w-32 h-32 rounded-lg shadow">
                                    </a>
                                    @else
                                    <!-- Show a clickable link for other file types -->
                                    <a href="{{ $filePath }}" download="{{ $fileName }}" target="_blank" class="text-black underline flex items-center">
                                        ({{ $fileName }})
                                    </a>
                                    @endif
                                </div>
                                @endif

                            </div>
                        </div>
                        @endif
                        @endforeach
                    </div>
                </div>

                <form wire:submit.prevent="sendMessage()" enctype="multipart/form-data">
                    <div class="flex items-center gap-2 p-4 bg-white border-t">
                        <input type="text" wire:model="message" placeholder="Type a message..." class="w-full p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" />
                        <!-- File Upload Input -->
                        <input type="file" wire:model="attachment" class="w-auto p-1 border rounded-lg focus:outline-none" />
                        <button class="px-4 py-2 text-white transition bg-blue-500 rounded-lg hover:bg-blue-600">
                            Send
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
