<template>
    <div class='home-main'>
        <div class='left-content'>
            <h2>Stwórz zespół</h2>
            <button class='pointer'>Stwórz</button>
            <form>
                <h2>Dołącz do zespołu</h2>
                <input type="password" placeholder="Kod dostępu">
                <button class='pointer'>Dołącz</button>
            </form>
        </div>
        <div class='right-content'>
            <h2>Lista zespołów do których należysz:</h2>
            <div class='team-list'>
                <TeamListElem v-for="team in teams" v-bind:key="team"
                    :id="team.id"
                    :name="team.name"
                    :avatar_url="team.avatarUrl"
                >
                </TeamListElem>
            </div>
        </div>
    </div>
</template>

<script>
import TeamListElem from '@/components/TeamListElem'

export default {
    name: 'Home',
    components: {TeamListElem},
    data() {
        return {
            teams: []
        }
    },
    created() {
        this.fetchTeams();
    },
    methods: {
        fetchTeams() {
            this.teams[0] = {id: 12, name: 'Zespół testowy 1', avatarUrl: 'http://localhost:8081/uploads/team/avatars/default.png'}
            this.teams[1] = {id: 13, name: 'Dawidoland', avatarUrl: 'default.png'}
            this.teams[2] = {id: 14, name: 'Nooby Cinkrofa', avatarUrl: 'default.png'}
            // this.teams[3] = {id: 14, name: 'Nooby Cinkrofa', avatarUrl: 'default.png'}
            // this.teams[4] = {id: 14, name: 'Nooby Cinkrofa', avatarUrl: 'default.png'}
            // this.teams[5] = {id: 14, name: 'Nooby Cinkrofa', avatarUrl: 'default.png'}
            // this.teams[6] = {id: 14, name: 'Nooby Cinkrofa', avatarUrl: 'default.png'}
            // this.teams[7] = {id: 14, name: 'Nooby Cinkrofa', avatarUrl: 'default.png'}
            // this.teams[8] = {id: 14, name: 'Nooby Cinkrofa', avatarUrl: 'default.png'}
            // this.teams[9] = {id: 14, name: 'Nooby Cinkrofa', avatarUrl: 'default.png'}
        }
    }
}
</script>

<style scoped>
    .home-main {
        width: 100%;
        min-height: 100%;
        display: flex;
    }

    .left-content {
        width: 50%;
        height: calc(100vh - 80px);

        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        /* background-color: tomato; */
    }

    .right-content {
        width: 50%;
        height: calc(100vh - 80px);

        display: flex;
        flex-direction: column;
        align-items: center;
        background-color: rgba(0,0,0,0.1);
    }

    .right-content > h2 {
        margin-top: 30px;
        color: white;
        font-weight: 300;
    }

    .left-content h2 {
        /* font-size: 42px; */
        font-weight: 300;
        color: white;
    }

    .left-content > form {
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .left-content button {
        width: 300px;
        height: 45px;

        margin-bottom: 30px;

        border: 0;
        border-radius: 10px;
        background: #39BB7A;

        font-weight: 300;
        font-size: 16px;
        color: white;
    }

    .left-content button:hover {
        outline-style: solid;
        outline-width: 1px;
        outline-color: rgb(22, 75, 47);
    }

    form > input {
        width: 280px;
        height: 45px;
        padding-left: 20px;

        border: 0;
        border-radius: 10px;
        background: #224957;
        /* background-color: rgba(0,0,0,0.4); */

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

    .left-content * {
        margin-bottom: 11px;
    }

    .team-list {
        width: 80%;
        height: 564px;
        margin-top: 11px;

        overflow-y: auto;

        background-color: rgba(0,0,0,0.1);
    }
</style>