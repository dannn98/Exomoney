<template>
    <p>Team o id: {{id}}</p>
</template>

<script>
    import {customAxios, NProgress} from '@/services/axios.service'
    import authHeader from '@/services/auth-header'

    export default {
    name: 'Team',
    components: {},
    data() {
        return {
            id: this.$route.params.id
        }
    },
    created() {
        this.fetchTeam();
    },
    methods: {
        async fetchTeam() {
            customAxios.get(`/team/${this.id}`, {headers: {'Authorization': authHeader()}})
            .then(Response => {
                console.log(Response.data.data)
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

</style>