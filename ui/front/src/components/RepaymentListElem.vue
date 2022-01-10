<template>
    <div class='repayment-list-elem pointer'>
        <p>{{target.nickname}} ({{value}})</p>
    </div>
</template>

<script>
export default {
    name: 'RepaymentListElem',
    components: {},
    data() {

    },
    props: {
        uid: {required: true, type: String},
        user: {required: true, type: Object},
        target: {required: true, type: Object},
        value: {required: true, type: String},
    }
}
</script>

<style scoped>
    .repayment-list-elem {
        width: 100%;
        height: 35px;

        margin-bottom: 3px;
        display: flex;
        align-items: center;
        justify-content: center;

        font-size: 13px;
        font-weight: 100;

        color: whitesmoke;

        background-color: rgba(0, 0, 0, 0.15);
    }
</style>