<template>
    <LoginTemplate>
        <RegisterForm></RegisterForm>
    </LoginTemplate>
</template>

<script>
import LoginTemplate from '@/components/LoginTemplate'
import RegisterForm from '@/components/RegisterForm'

export default {
    name: 'RegisterView',
    components: {LoginTemplate, RegisterForm}
}
</script>

<style scoped>

</style>