@props(['createdAt', 'createdBy', 'updatedAt', 'updatedBy'])

<div class="flex justify-between">
    <span class="font-semibold">Created At: </span>
    <span>{{ time_format($createdAt) ?? 'N/A' }}</span>
</div>

<div class="flex justify-between">
    <span class="font-semibold">Created By: </span>
    <span>{{ $createdBy ?? 'N/A' }}</span>
</div>

<div class="flex justify-between">
    <span class="font-semibold">Updated At: </span>
    <span>{{ time_format($updatedAt) ?? 'N/A' }}</span>
</div>

<div class="flex justify-between">
    <span class="font-semibold">Updated By: </span>
    <span>{{ $updatedBy ?? 'N/A' }}</span>
</div>