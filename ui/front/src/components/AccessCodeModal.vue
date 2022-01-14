<template>
    <div class="modal-mask" v-if="showModal" @close="showModal = false">
      <div class="modal-wrapper">
        <div class="modal-container">
            <form @submit.prevent="generateAccessCode">
                <p class='message' v-for="message in messages" v-bind:key="message">{{message}}</p>
                <input type="text" disabled v-model="this.accessCode" placeholder="Pobieranie kodu...">
                <div class="buttons">
                    <button class="b-add pointer">Generuj</button>
                    <button class="b-cancel pointer" @click="$emit('close')">Anuluj</button>
                </div>
            </form>
        </div>
      </div>
    </div>
</template>

<script>
    import {customAxios, NProgress} from '@/services/axios.service'
    import authHeader from '@/services/auth-header'

    export default {
        name: 'AccessCodeModal',
        components: {},
        data() {
            return {
                showModal: true,
                messages: [],
                accessCode: null,
                isOwner: false
            }
        },
        props: {
            id: {required: true, type: Number}
        },
        created() {
            this.fetchAccessCode()
        },
        methods: {
            fetchAccessCode() {
                this.messages = []
                customAxios.get(`/team-access-code/team/${this.id}`, {headers: authHeader()})
                .then(Response => {
                    this.accessCode = Response.data.data
                    this.isOwner = true
                    console.log(Response.data.data)
                })
                .catch(error => {
                    NProgress.done()
                    console.log(error.response)
                    if (error.response.status == 403) {
                        this.messages[0] = "Nie masz uprawnień do wyświetlenia i generowania kodu"
                        this.accessCode = ' '
                    }
                    console.log(error)
                })
            },
            generateAccessCode() {

                if (!this.isOwner) return

                const data = {
                    team_id: this.id
                }

                customAxios.post('/team-access-code', data, {headers: authHeader()})
                .then(Response => {
                    this.accessCode = Response.data.data.team_access_code
                    console.log(Response.data.data.team_access_code)
                })
                .catch(error => {
                    NProgress.done()
                    console.log(error)
                })
            }
        }
    }
</script>

<style scoped>
    .modal-mask {
        position: fixed;
        z-index: 9998;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        display: table;
    }

    .modal-wrapper {
        display: table-cell;
        vertical-align: middle;
    }

    .modal-container {
        width: 400px;
        margin: 0px auto;
        /* padding: 20px 30px; */
        background-color: #68868C;
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.33);
    }

    .modal-container > form {
        display: flex;
        flex-direction: column;
        padding: 20px 10px 20px 10px;
        /* margin: 10px 0 10px 0; */
    }

    form > * {
        margin-bottom: 10px;
    }

    form > *:last-child{
        margin-bottom: 0px;
    }    

    form > input[type='text'] {
        width: 360px;
        height: 45px;
        padding-left: 20px;

        border: 0;
        border-radius: 10px;
        background: #224957;

        font-weight: 300;
        font-size: 14px;
        color: white;
    }

    form > input[type='text']::placeholder {
        font-weight: 300;
        font-size: 14px;
        color: whitesmoke;
        font-style: italic;
    }

    form > input[type='text']:focus {
        outline-style: solid;
        outline-width: 1px;
        outline-color: #39BB7A;
    }

    .buttons {
        width: 380px;
        display: flex;
        justify-content: space-between;
    }

    .b-add {
        width: calc(60% - 20px);
    }

    .b-cancel {
        width: calc(40% + 10px);
    }

    .buttons > button {
        height: 45px;

        border: 0;
        border-radius: 10px;
        background: #39BB7A;

        font-weight: 300;
        font-size: 14px;
        color: white;
    }

    .buttons > button:hover {
        outline-style: solid;
        outline-width: 1px;
        outline-color: rgb(22, 75, 47);
    }

    .modal-enter-active,
    .modal-leave-active {
        transition: opacity 0.5s ease;
    }

    .modal-enter-from,
    .modal-leave-to {
        opacity: 0; 
    }

    .member-list-debts {
        max-height: 265px;
        overflow: auto;
    }

    .member-list-debts > div {
        margin-bottom: 10px;
    }

    .member-list-debts > div:last-child {
        margin-bottom: 0px;
    }

    .turbokoks {
        border-spacing: 0px;
        width: 100%;
        height: 45px;
    }

    .turbokoks > tr {
        width: 100%;
        height: 45px;
    }

    .td-left {
        width: calc(100% - 10px);
        height: 45px;
        display: flex;
        align-items: center;

        border: 0;
        border-radius: 10px;
        background: #224957;
    }

    .td-left > input[type="checkbox"] {
        width: 20px;
        height: 20px;
        margin-left: 20px;
    }

    .td-left > label {
        margin-left: 10px;
        font-weight: 300;
        font-size: 14px;
        color: whitesmoke;
    }

    .td-right {
        width: 40%;
        height: 45px;
    }

    .td-right > input {
        width: calc(100% - 20px);
        height: 45px;
        padding-left: 20px;

        border: 0;
        border-radius: 10px;
        background: #224957;

        font-weight: 300;
        font-size: 14px;
        color: whitesmoke;
    }

    .td-right > input:disabled {
        color: rgb(138, 138, 138);
        background-color: #22495773;
    }

    .td-right > input::placeholder {
        font-weight: 300;
        font-size: 14px;
        color: whitesmoke;
    }

    .td-right > input:focus {
        outline-style: solid;
        outline-width: 1px;
        outline-color: #39BB7A;
    }
</style>