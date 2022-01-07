<template>
    <div class='header'>
        <div class='header-content'>
            <div class='logo-content'>
                <img src='@/../public/img/logo-white.svg'>
                <h2>Exomoney</h2>
            </div>
            <nav>
                <button class='pointer' @click="handleLogout">Wyloguj się</button>
                <router-link to="/login" class="nav-elem">O nas</router-link>
                <!-- <router-link to="#" class="nav-elem">Profil użytkownika</router-link> -->
                <router-link to="/" class="nav-elem">Strona Główna</router-link>
            </nav>
        </div>
    </div>
    <div class="under-header">
    </div>
</template>

<script>
export default {
    name: 'Header',
    components: {},
    methods: {
        handleLogout() {
            localStorage.removeItem('access_token');
            this.$router.push('login')
        }
    }
}
</script>

<style scoped>
    .header {
        width: 100%;
        height: 80px;

        position: fixed;
        top: 0px;
        left: 0px;
        background-color: #224957;
        /* background-color: rgba(0, 0, 0, 0.4); */
    }

    .under-header {
        width: 100%;
        height: 80px;
        background-color: #68868C;
    }

    .header-content {
        margin: 0 auto;
        width: 1056px;
        height: 100%;

        display: flex;
        /* background-color: royalblue; */
    }

    .logo-content {
        height: 100%;
        width: 40%;

        display: flex;
        align-items: center;
        /* background-color: chartreuse; */
    }

    .logo-content > img {
        height: 80%;
        margin-right: 11px;
    }

    .logo-content > h2 {
        font-size: 32px;
        font-weight: 400;
        color: white;
    }

    nav {
        height: 100%;
        width: 60%;

        display: flex;
        flex-direction: row-reverse;
        align-items: center;
        /* background-color: turquoise; */
    }

    nav > button {
        /* width: 300px; */
        height: 45px;
        margin-left: 30px;
        padding: 0 15px 0 15px;

        border: 0;
        border-radius: 10px;
        background: #39BB7A;

        font-weight: 300;
        font-size: 14px;
        color: white;
    }

    nav > button:hover {
        color: #39BB7A;
        background-color: white;
        outline-style: solid;
        outline-width: 1px;
        outline-color: #39BB7A;
    }

    .nav-elem {
        height: 100%;
        padding: 0 12px 0 12px;
        
        display: flex;
        align-items: center;

        font-size: 18px;
        font-weight: 100;
        
        color: white;
        text-decoration: none;
    }

    .nav-elem:hover {
        color: #39BB7A;
        background-color: rgba(0, 0, 0, 0.1);
    }
</style>