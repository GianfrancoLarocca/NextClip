<template>
    <div class="bg-dark text-light min-vh-100">
        <Navbar />
        <main class="container py-5">
            <slot />
        </main>
    </div>
</template>

<script setup lang="ts">
import Navbar from "@/components/Navbar.vue";
</script>
