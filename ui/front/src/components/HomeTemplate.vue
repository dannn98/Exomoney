<template>
    <Header></Header>
    <div class='main'>
        <div class='main-content'>
            <slot></slot>
        </div>
    </div>
</template>

<script>
import Header from '@/components/Header'

export default {
    name: 'HomeTemplate',
    components: {Header}
}
</script>

<style scoped>
    .main {
        margin: 0;
        padding: 0;
        width: 100%;
        min-height: calc(100vh - 80px);

        display: flex;
        justify-content: center;

        background-color: #68868C;
    }

    .main-content {
        width: 1056px;
        min-height: calc(100vh - 80px);

        background-color: rgba(0, 0, 0, 0.1);
    }
</style>