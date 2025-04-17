<template>
    <DefaultLayout>
        <div class="w-100" style="max-width: 400px; margin: auto">
            <h2 class="mb-4">Login</h2>

            <form @submit.prevent="handleLogin">
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input
                        v-model="form.email"
                        type="email"
                        class="form-control bg-dark text-light border-secondary"
                        required
                    />
                </div>

                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input
                        v-model="form.password"
                        type="password"
                        class="form-control bg-dark text-light border-secondary"
                        required
                    />
                </div>

                <button class="btn btn-primary w-100" :disabled="loading">
                    {{ loading ? "Logging in..." : "Login" }}
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

const detectDeviceName = (): string => {
    const ua = navigator.userAgent.toLowerCase();

    if (ua.includes("iphone")) return "iphone";
    if (ua.includes("ipad")) return "ipad";
    if (ua.includes("android") && ua.includes("mobile")) return "android phone";
    if (ua.includes("android")) return "android tablet";

    if (ua.includes("windows nt")) return "windows desktop";
    if (ua.includes("macintosh") || ua.includes("mac os")) return "mac desktop";
    if (ua.includes("linux")) return "linux desktop";

    return "unknown device";
};

const form = ref({
    email: "",
    password: "",
    device_name: detectDeviceName(),
});

const loading = ref(false);
const error = ref("");

const handleLogin = async () => {
    error.value = "";
    loading.value = true;

    try {
        const response = await api.post("/token", form.value);
        const token = response.data.token;

        localStorage.setItem("auth_token", token);

        window.location.href = "/";
    } catch (err: any) {
        error.value = err.response?.data?.message || "Login failed";
    } finally {
        loading.value = false;
    }
};
</script>
