<template>
    <LoginTemplate>
        <LoginForm></LoginForm>
    </LoginTemplate>
</template>

<script>
import LoginTemplate from '@/components/LoginTemplate'
import LoginForm from '@/components/LoginForm'

export default {
    name: 'LoginView',
    components: {LoginTemplate, LoginForm}
}
</script>

<style scoped>

</style>