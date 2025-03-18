<p>{{ $body }}</p>

@if ($attachment)
<p>
    📎 <a href="{{ $attachment }}" target="_blank" download class="text-blue-500">
        Download Attachment
    </a>
</p>
@endif
