<template>
    <div class="login">
        <h2>Войти</h2>
        <form @submit.prevent="login">
            <div>
                <label for="email">Email:</label>
                <input type="email" name="email" v-model="email" />
            </div>
            <div>
                <label for="password">Пароль:</label>
                <input type="password" name="password" v-model="password" />
            </div>
            <button type="submit">Войти</button>
        </form>
    </div>
</template>

<script setup>
import { ref } from "vue";
import { useRouter } from "vue-router";
import authService from "../services/authService";

// Filled in the credentials for easy authentication while testing
const email = ref("tianna.turner@example.com");
const password = ref("password");
const error = ref("");
const router = useRouter();

const login = async () => {
    try {
        await authService.login(email.value, password.value);
        router.push("/purchases");
    } catch (err) {
        error.value = "Invalid email or password";
    }
};
</script>
