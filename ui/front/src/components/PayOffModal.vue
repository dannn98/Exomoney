<template>
    <div class="modal-mask" v-if="showModal" @close="showModal = false">
      <div class="modal-wrapper">
        <div class="modal-container">
            <form @submit.prevent="payOff">
                <input v-model="currentVal" type="number" step="0.01" placeholder="Wartość spłaty">
                <div class="buttons">
                    <button class="pointer">Spłać</button>
                    <button class="pointer" @click="$emit('close')">Anuluj</button>
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
        name: 'PayOffModal',
        components: {},
        data() {
            return {
                showModal: true,
                currentVal: Number(this.value)
            }
        },
        props: {
            uid: {required: true, type: String},
            value: {required: true, type: String}
        },
        methods: {
            payOff() {
                let data = new FormData()
                data.append('uid', this.uid)
                data.append('value', this.currentVal)

                customAxios.post(`/repayment/pay-off`, data, {headers: authHeader('multipart/form-data')})
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
        width: 320px;
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

    form > input[type='number'] {
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

    form > input[type='number']::placeholder {
        font-weight: 300;
        font-size: 14px;
        color: whitesmoke;
    }

    form > input[type='number']:focus {
        outline-style: solid;
        outline-width: 1px;
        outline-color: #39BB7A;
    }

    .buttons {
        width: 300px;
        display: flex;
        justify-content: space-between;
    }

    .buttons > button {
        width: 145px;
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
</style>