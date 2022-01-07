<template>
    <div class='team-list-elem pointer'>
        <img src='http://localhost:8081/uploads/team/avatars/default.png'>
        <p>{{name}}</p>
    </div>
</template>

<script>
export default {
    name: 'TeamListElem',
    components: {},
    data() {

    },
    props: {
        id: {required: true, type: Number},
        name: {required: true, type: String},
        avatar_url: {required: true, type: String}
    }
}
</script>

<style scoped>
    .team-list-elem {
        width: 100%;
        height: 60px;

        margin-bottom: 3px;
        display: flex;
        align-items: center;

        background-color: rgba(0, 0, 0, 0.3);
    }

    .team-list-elem:last-child {
        margin-bottom: 0px;
    }

    .team-list-elem > img {
        height: 100%;
    }

    .team-list-elem > p {
        font-size: 18px;
        font-weight: 200;

        margin-left: 11px;

        color: white;
    }

    .team-list-elem:hover * {
        color: #39BB7A;
    }
</style>