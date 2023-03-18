<template>
    <router-link class="router-link-elem" :to="{ name: 'Team', params: { id: id }}">
        <div class='team-list-elem pointer'>
            <img :src="avatar_url">
            <p>{{name}}</p>
        </div>
    </router-link>
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

    .router-link-elem {
        text-decoration: none;
    }

    .router-link-elem:last-child .team-list-elem {
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