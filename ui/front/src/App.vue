<template>
  <main>
    <router-view/>
  </main>
</template>

<style>
@import url('https://fonts.googleapis.com/css2?family=Lexend+Deca:wght@200;300;400&display=swap');

#app {
  font-family: Avenir, Helvetica, Arial, sans-serif;
  /* -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-align: center;
  color: #2c3e50; */
}

* {
  margin: 0;
  padding: 0;

  font-family: 'Lexend Deca';
}

main {
  margin: 0 auto;
  width: 100vw;
  height: 100vh;
  max-width: 100%;
}

.pointer {
  cursor: pointer;
}

.message {
    font-size: 12px;
    font-weight: 400;
    color: rgb(190, 0, 0);
}

/* Chrome, Safari, Edge, Opera */
input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}

/* Firefox */
input[type=number] {
  -moz-appearance: textfield;
}
/* #nav {
  padding: 30px;
}

#nav a {
  font-weight: bold;
  color: #2c3e50;
}

#nav a.router-link-exact-active {
  color: #42b983;
} */
</style>
