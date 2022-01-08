<template>
    <p>Team o id: {{id}}</p>
</template>

<script>
export default {
    name: 'Team',
    components: {},
    data() {
        return {
            id: this.$route.params.id
        }
    }
}
</script>

<style scoped>

</style>