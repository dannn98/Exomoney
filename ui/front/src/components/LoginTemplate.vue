<template>
    <div class='main'>
        <img src="@/../public/img/logo-white.svg">
        <h2>Exomoney</h2>
        <div class='login-form'>
            <slot></slot>
        </div>
    </div>
</template>

<script>
export default {
    name: 'LoginTemplate',
    components: {}
}
</script>

<style scoped>
    .main {
        width: 100vw;
        height: 100vh;
        background-color: #68868C;

        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }

    .main > img {
        width: 150px;
    }

    .main > h2 {
        font-weight: 400;
        font-size: 64px;
        color: white;
    }
</style>