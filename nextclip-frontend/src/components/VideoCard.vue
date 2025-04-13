<template>
  <div class="border rounded p-4 shadow hover:shadow-lg transition">
    <img
      :src="video.thumbnail_url || '/placeholder.jpg'"
      class="w-full h-48 object-cover rounded"
    />
    <h2 class="text-lg font-semibold mt-2">{{ video.title }}</h2>
    <p class="text-sm text-gray-500">{{ video.channel.name }}</p>
    <p class="text-sm text-gray-400">{{ video.views }} visualizzazioni</p>
  </div>
</template>

<script setup lang="ts">
defineProps<{
  video: {
    id: number
    title: string
    thumbnail_url: string | null
    views: number
    channel: {
      name: string
    }
  }
}>()
</script>
