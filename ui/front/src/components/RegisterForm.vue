<template>
    <form>
        <p>Dołącz do nas i wejdź w świat optymalizacji!</p>
        <input type="text" placeholder="Nickname">
        <input type="text" placeholder="Email">
        <input type="password" placeholder="Hasło">
        <input type="password" placeholder="Powtórz hasło">
        <p>Masz już konto? Kliknij <router-link class='tutaj' to="/login">tutaj</router-link> i zaloguj się!</p>
        <button class='pointer'>Zarejestruj się</button>
    </form>
</template>

<script>
export default {
    name: 'RegisterForm',
    components: {}
}
</script>

<style scoped>
    form {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;

        font-weight: 300;
        font-size: 14px;
        color: white;
    }

    form > * {
        margin-bottom: 11px;
    }

    form > input {
        width: 280px;
        height: 45px;
        padding-left: 20px;

        border: 0;
        border-radius: 10px;
        background: #224957;

        font-weight: 300;
        font-size: 14px;
        color: white;
    }

    form > input::placeholder {
        font-weight: 300;
        font-size: 14px;
        color: whitesmoke;
    }

    form > input:focus {
        outline-style: solid;
        outline-width: 1px;
        outline-color: #39BB7A;
    }

    form > button {
        width: 300px;
        height: 45px;

        border: 0;
        border-radius: 10px;
        background: #39BB7A;

        font-weight: 300;
        font-size: 14px;
        color: white;
    }

    form > button:hover {
        outline-style: solid;
        outline-width: 1px;
        outline-color: rgb(22, 75, 47);
    }

    .tutaj {
        color: #39BB7A;
    }
</style>