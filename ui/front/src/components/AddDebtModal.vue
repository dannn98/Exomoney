<template>
    <div class="modal-mask" v-if="showModal" @close="showModal = false">
      <div class="modal-wrapper">
        <div class="modal-container">
            <form @submit.prevent="addDebt">
                <p class='message' v-for="message in messages" v-bind:key="message">{{message}}</p>
                <input v-model=this.data.title type="text" placeholder="Podaj tytuł długu">
                <div class="member-list-debts">
                    <div v-for="member in members" v-bind:key="member">
                        <table class="turbokoks">
                            <tr>
                                <td class="td-left">
                                    <input type="checkbox" v-model=checked[member.id] :id="'member'+member.id">
                                    <label :for="'member'+member.id">{{member.nickname}}</label>
                                </td>
                                <td class="td-right">
                                    <input v-model=debts[member.id] :disabled="checked[member.id] ? false : true" type="number" step="0.01" placeholder="Wartość">
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="buttons">
                    <button class="b-add pointer" @click="addDebts">Dodaj</button>
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
        name: 'AddDebtModal',
        components: {},
        data() {
            return {
                showModal: true,
                members: this.memberList,
                checked: [],
                debts: [],
                debtList: [],
                data: {
                    title: null,
                    team_id: this.id,
                    debts: null
                },
                messages: []
            }
        },
        props: {
            id: {required: true, type: Number},
            memberList: {required: true, type: Array}
        },
        methods: {
            addDebts() {
                this.debtList = []
                this.messages = []

                for (let i = 0; i < this.members.length; i++) {
                    let memId = this.members[i].id
                    
                    if (this.checked[memId]) {
                        if (this.debts[memId] !== '' && this.debts[memId] > 0) {
                            this.debtList[memId] = this.debts[memId]
                        }
                    }
                }

                this.data.debts = this.debtList.reduce((a, v, i) => ({ ...a, [i]: v}), {}) 

                if (!this.data.title) {
                    this.messages[0] = 'Podaj tytuł długu'
                    
                }
                else if (this.debtList.length == 0) {
                    this.messages[0] = 'Wybierz użytkownika i podaj wartość długu dla niego'
                }
                else {
                    customAxios.post('/debt', this.data, {headers: authHeader()})
                    .then(Response => {
                        console.log(Response.data.data)
                        this.$router.go()
                    })
                    .catch(error => {
                        NProgress.done()
                        console.log(error)
                    })
                }
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