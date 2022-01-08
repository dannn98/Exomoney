<template>
    <form @submit.prevent="handleLogin">
        <p>Zaloguj się i rozliczaj się wydajnie!</p>
        <div style="margin-bottom: 0px;">
        <p class='message' v-for="message in messages" v-bind:key="message">{{message.message}}</p>
        </div>
        <input v-model="user.email" type="text" placeholder="Email">
        <input v-model="user.password" type="password" placeholder="Hasło">
        <p>Nie masz jeszcze konta? Kliknij <router-link class='tutaj' to="/register">tutaj</router-link> i dołącz do nas!</p>
        <button class='pointer'>Zaloguj się</button>
    </form>
</template>

<script>
import User from '@/models/user'
import {customAxios} from '@/services/axios.service'

export default {
    name: 'LoginForm',
    components: {},
    data() {
        return {
            user: new User('', '', ''),
            messages: []
        }
    },
    methods: {
        handleLogin() {
            this.messages = []

            const data = {
                email: this.user.email,
                password: this.user.password
            }

            console.log(data)
            customAxios.post('/auth/login', data)
            .then(Response => {
                localStorage.setItem('access_token', Response.data.access_token)
                this.$router.push('/')
            })
            .catch((error) => {
                if (error.response.status == 400) {
                    this.messages[0] = {message: 'Podano zły login lub hasło'}
                    console.log(error)
                }
            })
        }
    }
}
</script>

<style scoped>
    form {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;

        font-weight: 300;
        font-size: 14px;
        color: white;
    }

    form > * {
        margin-bottom: 11px;
    }

    form > input {
        width: 280px;
        height: 45px;
        padding-left: 20px;

        border: 0;
        border-radius: 10px;
        background: #224957;

        font-weight: 300;
        font-size: 14px;
        color: white;
    }

    form > input::placeholder {
        font-weight: 300;
        font-size: 14px;
        color: whitesmoke;
    }

    form > input:focus {
        outline-style: solid;
        outline-width: 1px;
        outline-color: #39BB7A;
    }

    form > button {
        width: 300px;
        height: 45px;

        border: 0;
        border-radius: 10px;
        background: #39BB7A;

        font-weight: 300;
        font-size: 14px;
        color: white;
    }

    form > button:hover {
        outline-style: solid;
        outline-width: 1px;
        outline-color: rgb(22, 75, 47);
    }

    .tutaj {
        color: #39BB7A;
    }

    .message {
        margin-bottom: 0px;
    }

    .message:last-child {
        margin-bottom: 11px;
    }
</style>