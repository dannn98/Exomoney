<template>
    <form @submit.prevent="handleRegister">
        <p>Dołącz do nas i wejdź w świat optymalizacji!</p>
        <div style="margin-bottom: 0px;">
        <p class='message' v-for="message in messages" v-bind:key="message">{{message.message}}</p>
        </div>
        <input v-model="this.user.nickname" type="text" placeholder="Nickname">
        <input v-model="this.user.email" type="text" placeholder="Email">
        <input v-model="this.user.password" type="password" placeholder="Hasło">
        <input v-model="this.repeat_password" type="password" placeholder="Powtórz hasło">
        <p>Masz już konto? Kliknij <router-link class='tutaj' to="/login">tutaj</router-link> i zaloguj się!</p>
        <button class='pointer'>Zarejestruj się</button>
    </form>
</template>

<script>
import User from '@/models/user'
import {customAxios, NProgress} from '@/services/axios.service'

export default {
    name: 'RegisterForm',
    components: {},
    data() {
        return {
            user: new User('', '', ''),
            repeat_password: '',
            messages: []
        }
    },
    methods: {
        handleRegister() {
            this.messages = []
            const data = {
                nickname: this.user.nickname,
                email: this.user.email,
                password: this.user.password
            }

            if (this.user.password !== this.repeat_password) {
                this.messages[0] = {message: "Hasła nie są takie same"}
            }
            else {
                customAxios.post('/user', data)
                .then(() => {
                    this.$router.push('/login')
                })
                .catch((error) => {
                    if (error.response.status == 422) {
                        NProgress.done()
                        let response_data = error.response.data.data

                        for (const [key, value] of Object.entries(response_data)) {
                            this.messages.push({message: `${key}: ${value}`})
                        }
                    }

                })
            }
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