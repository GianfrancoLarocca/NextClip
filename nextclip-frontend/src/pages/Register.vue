<template>
    <DefaultLayout>
        <div class="w-100" style="max-width: 400px; margin: auto">
            <h2 class="mb-4">Register</h2>
            <form>
                <div class="mb-3">
                    <label class="form-label">Name</label>
                    <input
                        type="text"
                        class="form-control bg-dark text-light border-secondary"
                    />
                </div>
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input
                        type="email"
                        class="form-control bg-dark text-light border-secondary"
                    />
                </div>
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input
                        type="password"
                        class="form-control bg-dark text-light border-secondary"
                    />
                </div>
                <button type="submit" class="btn btn-success w-100">
                    Register
                </button>
            </form>
        </div>
    </DefaultLayout>
</template>

<script setup lang="ts">
import DefaultLayout from "@/layouts/DefaultLayout.vue";
</script>
