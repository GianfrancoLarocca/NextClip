<template>
    <DefaultLayout>
        <div class="w-100" style="max-width: 400px; margin: auto">
            <h2 class="mb-4">Login</h2>
            <form>
                <div class="mb-3">
                    <label for="email" class="form-label">Email address</label>
                    <input
                        type="email"
                        class="form-control bg-dark text-light border-secondary"
                        id="email"
                    />
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input
                        type="password"
                        class="form-control bg-dark text-light border-secondary"
                        id="password"
                    />
                </div>
                <button type="submit" class="btn btn-primary w-100">
                    Login
                </button>
            </form>
        </div>
    </DefaultLayout>
</template>

<script setup lang="ts">
import DefaultLayout from "@/layouts/DefaultLayout.vue";
</script>
