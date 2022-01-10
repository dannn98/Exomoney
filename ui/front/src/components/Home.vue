<template>
    <div class='home-main'>
        <div class='left-content'>
            <h2>Stwórz zespół</h2>
            <form class='create-team-form' @submit.prevent="handleCreateTeam">
                <p>Podaj nazwę zespołu, opcjonalnie wybierz awatar.</p>
                <input v-model="team.name" type="text" placeholder="Nazwa zespołu">
                <input @change="uploadFile" type="file" accept="image/png, image/jpeg">
                <button class='pointer'>Stwórz</button>
            </form>
            <form class='join-team-form' @submit.prevent="handleJoinTeam">
                <h2>Dołącz do zespołu</h2>
                <p>Podaj kod dostępu. Poproś o kod właściciela zespołu.</p>
                <input v-model="code" type="password" placeholder="Kod dostępu">
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
import {customAxios, NProgress} from '@/services/axios.service'
import authHeader from '@/services/auth-header'

export default {
    name: 'Home',
    components: {TeamListElem},
    data() {
        return {
            teams: [],
            team: {
                name: '',
                avatar_file: null
            },
            code: "",
        }
    },
    created() {
        this.fetchTeams();
    },
    methods: {
        fetchTeams() {
            customAxios.get('/user/teams', {headers: {'Authorization': authHeader()}})
            .then(Response => {
                this.teams = Response.data.data.sort((a,b) => (a.id > b.id) ? 1 : ((b.id > a.id) ? -1 : 0))
                console.log(this.teams)
            })
            .catch((error) => {
                NProgress.done()
                console.log(error)
            })
        },
        uploadFile(event) {
            this.team.avatar_file = event.target.files[0]
        },
        handleCreateTeam() {
            let data = new FormData()
            data.append('name', this.team.name);
            if(this.team.avatar_file !== null) {
                data.append('avatar_file', this.team.avatar_file);
            }
            
            customAxios.post('/team', data, {headers: {'Authorization': authHeader(), 'Content-Type': 'multipart/form-data'}})
            .then(Response => {
                this.$router.push(`/team/${Response.data.data}`)
            })
            .catch((error) => {
                NProgress.done()
                console.log(error)
            })
            //TODO: catch
        },
        handleJoinTeam() {
            const data = {
                code: this.code
            }

            customAxios.post('/team/join', data, {headers: {'Authorization': authHeader()}})
            .then(Response => {
                this.$router.push(`/team/${Response.data.data}`)
            })
            .catch((error) => {
                NProgress.done()
                console.log(error)
            })
            //TODO: catch
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
        justify-content: center;
        background-color: rgba(0,0,0,0.1);
    }

    .right-content > h2 {
        /* margin-top: 30px; */
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

    input[type= 'text'], input[type= 'password'] {
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

    input[type= 'text']::placeholder, input[type= 'password']::placeholder {
        font-weight: 300;
        font-size: 14px;
        color: whitesmoke;
    }

    input[type= 'text']:focus, input[type= 'password']:focus {
        outline-style: solid;
        outline-width: 1px;
        outline-color: #39BB7A;
    }

    input[type= 'file'] {
        width: 300px;
        height: 46px;

        padding-top: 1px;
        padding-left: 1px;

        font-weight: 300;
        font-size: 11px;
        color: white;
    }

    input[type= 'file']::-webkit-file-upload-button, input[type= 'file']::file-selector-button {
        height: 45px;
        padding: 0 20px 0 20px;
        margin-right: 20px;
        cursor: pointer;

        border: 0;
        border-radius: 10px;
        background: #39BB7A;

        font-family: 'Lexend Deca';
        font-weight: 300;
        font-size: 14px;
        color: white;
    }

    input[type= 'file']::-webkit-file-upload-button:hover {
        outline-width: 1px;
        outline-style: solid;
        outline-color: rgb(22, 75, 47);
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

    .left-content p {
        font-weight: 300;
        font-size: 12px;
        color: white;
    }
</style>