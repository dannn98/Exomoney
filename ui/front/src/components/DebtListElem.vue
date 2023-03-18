<template>
    <div class='debt-list-elem pointer'>
        <table>
            <tr>
                <!-- <td class='debt-id'>
                    {{id}}
                </td> -->
                <td class='debt-title'>
                    {{title}}
                </td>
                <td class='debt-debtor'>
                    {{debtor.nickname}}
                </td>
                <td class='debt-creditor'>
                    {{creditor.nickname}}
                </td>
                <td class='debt-value'>
                    {{value}}
                </td>
                <td class='debt-date'>
                    {{createdAt.slice(0, 10)}}
                </td>
            </tr>
        </table>
    </div>
</template>

<script>
export default {
    name: 'DebtListElem',
    components: {},
    data() {

    },
    props: {
        id: {required: true, type: Number},
        title: {required: true, type: String},
        debtor: {required: true, type: Object},
        creditor: {required: true, type: Object},
        value: {required: true, type: String},
        createdAt: {required: true, type: String}
    }
}
</script>

<style scoped>
    .debt-list-elem {
        width: 100%;
        height: 35px;

        margin-bottom: 3px;
        display: flex;
        align-items: center;

        background-color: rgba(0, 0, 0, 0.15);
    }

    .debt-list-elem:last-child {
        margin-bottom: 0px;
    }

    table { 
        width: 100%;
        height: 100%;
    }

    tr {
        width: 100%;
        height: 100%;
        /* background-color: thistle; */
    }

    td {
        height: 100%;

        font-size: 13px;
        font-weight: 100;

        color: whitesmoke;
        text-align: center;

        /* outline: 1px solid black; */

        /* background-color: tomato; */
    }

    .debt-title {
        width: 25%;
    }

    .debt-debtor {
        width: 25%;

        /* color: rgb(107, 0, 0); */
    }

    .debt-creditor {
        width: 25%;
    }

    .debt-value {
        width: 10%;
    }

    .debt-date {
        width: 15%;
    }
</style>