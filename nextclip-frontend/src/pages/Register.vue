<template>
    <DefaultLayout>
        <div class="w-100" style="max-width: 400px; margin: auto">
            <h2 class="mb-4">Register</h2>
            <form @submit.prevent="handleRegister">
                <div class="mb-3">
                    <label class="form-label">Name</label>
                    <input
                        v-model="form.name"
                        type="text"
                        class="form-control bg-dark text-light border-secondary"
                    />
                </div>

                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input
                        v-model="form.email"
                        type="email"
                        class="form-control bg-dark text-light border-secondary"
                    />
                </div>

                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input
                        v-model="form.password"
                        type="password"
                        class="form-control bg-dark text-light border-secondary"
                    />
                </div>

                <div class="mb-3">
                    <label class="form-label">Confirm Password</label>
                    <input
                        v-model="form.password_confirmation"
                        type="password"
                        class="form-control bg-dark text-light border-secondary"
                    />
                </div>

                <button class="btn btn-success w-100" :disabled="loading">
                    {{ loading ? "Registering..." : "Register" }}
                </button>

                <p class="mt-3 text-danger" v-if="error">{{ error }}</p>
            </form>
        </div>
    </DefaultLayout>
</template>

<script setup lang="ts">
import { ref } from "vue";
import api from "@/api";
import DefaultLayout from "@/layouts/DefaultLayout.vue";

const form = ref({
    name: "",
    email: "",
    password: "",
    password_confirmation: "",
});

const loading = ref(false);
const error = ref("");

const handleRegister = async () => {
    error.value = "";
    loading.value = true;
    try {
        await api.post("/register", form.value);
        window.location.href = "/login";
    } catch (err: any) {
        error.value = err.response?.data?.message || "Registration failed";
    } finally {
        loading.value = false;
    }
};
</script>
