<div class="mx-auto mt-8 bg-white shadow-sm sm:rounded-lg max-w-7xl sm:px-6 lg:px-8">
    <div class="p-6">

        <div class="flex justify-between">
            <h2 class="mb-4 text-lg font-bold">Compose Email</h2>

            <a href="{{ route('mailbox') }}" class="px-4 py-2 font-medium text-gray-600 rounded hover:bg-gray-200 hover:text-gray-800">
                Back
            </a>
        </div>

        <form wire:submit.prevent="sendEmail" class="mt-4">
            <input type="email" wire:model="recipient_email" placeholder="Recipient Email" class="w-full p-2 mb-2 border rounded">
            <input type="text" wire:model="subject" placeholder="Subject" class="w-full p-2 mb-2 border rounded">
            <textarea wire:model="body" placeholder="Message" class="w-full h-32 p-2 mb-2 border rounded"></textarea>
            <input type="file" wire:model="attachment" class="w-full p-2 mb-2 border rounded" id="attachmentInput">
            <button type="submit" class="px-4 py-2 text-white bg-blue-500 rounded hover:bg-blue-600">
                Send Email
            </button>
        </form>

        <script>
            Livewire.on('emailSent', () => {
                document.getElementById('attachmentInput').value = ''; // 🔥 Force file input reset
            });

        </script>

    </div>
</div>

@livewireScripts
