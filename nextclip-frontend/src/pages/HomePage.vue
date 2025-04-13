<template>
  <div class="p-6 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
    <VideoCard v-for="video in videos" :key="video.id" :video="video" />
  </div>
</template>

<script setup lang="ts">
import { onMounted, ref } from 'vue'
import http from '@/api/http'
import VideoCard from '@/components/VideoCard.vue'
import type { Video } from '@/models/Video'

const videos = ref<Video[]>([])

onMounted(async () => {
  const response = await http.get('/videos')
  videos.value = response.data.data
})
</script>
