<template>
    <div class="team-main">
        <div class="left-content">
            <div class='team-header'>
                <div class='team-header-content'>
                    <img :src="team.avatarUrl" class="loading">
                    <div class="team-name loading">
                        <h2>{{team.name}}</h2>
                        <p>Założyciel - {{team.owner.nickname}} ({{team.owner.email}})</p>
                    </div> 
                </div>
            </div>
            <div class='team-debts'>
                <div class='debt-list'>
                    <DebtListElem
                        :id="0"
                        :title="'Tytuł długu'"
                        :debtor="{nickname: 'Kredytobiorca'}"
                        :creditor="{nickname: 'Kredytodawca'}"
                        :value="'Wartość'"
                        :createdAt="'Data'"
                    >
                    </DebtListElem>
                    <DebtListElem v-for="debt in debts" v-bind:key="debt"
                        :id="debt.id"
                        :title="debt.title"
                        :debtor="debt.debtor"
                        :creditor="debt.creditor"
                        :value="debt.value"
                        :createdAt="debt.createdAt"
                    >
                    </DebtListElem>
                </div>
            </div>
        </div>
        <div class="right-content">
            <div class="control-panel">
                <div class="control-panel-content">

                </div>
            </div>
            <div class="team-repayments">
                <div class="repayments-list">
                    <div class="repayment-header r-credit">Kredyty</div>
                    <RepaymentListElem class="r-credit" v-for="credit in repayments.credits" v-bind:key="credit"
                        :uid="credit.uid"
                        :user="credit.creditor"
                        :target="credit.debtor"
                        :value="credit.value"
                    >
                    </RepaymentListElem>
                    <div class="repayment-header r-debt">Długi</div>
                    <RepaymentListElem class="r-debt" v-for="rdebt in repayments.debts" v-bind:key="rdebt"
                        :uid="rdebt.uid"
                        :user="rdebt.debtor"
                        :target="rdebt.creditor"
                        :value="rdebt.value"
                    >
                    </RepaymentListElem>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import DebtListElem from '@/components/DebtListElem'
    import RepaymentListElem from  '@/components/RepaymentListElem'
    import {customAxios, NProgress} from '@/services/axios.service'
    import authHeader from '@/services/auth-header'

    export default {
    name: 'Team',
    components: {DebtListElem, RepaymentListElem},
    data() {
        return {
            id: this.$route.params.id,
            team: {
                id: null,
                owner: {
                    id: null,
                    email: '',
                    nickname: ''
                },
                name: '',
                avatarUrl: ''
            },
            debts: [],
            repayments: {
                debts: [],
                credits: []
            }
        }
    },
    created() {
        this.fetchTeam()
        this.fetchDebts()
        this.fetchRepayments()
    },
    methods: {
        async fetchTeam() {
            customAxios.get(`/team/${this.id}`, {headers: {'Authorization': authHeader()}})
            .then(Response => {
                this.team = Response.data.data
                var d = document.getElementsByClassName("loading")
                d[0].style.visibility = "visible"; 
                d[1].style.visibility = "visible"; 
                console.log(this.team)
            })
            .catch(error => {
                NProgress.done()
                console.log(error)
            })
        },
        async fetchDebts() {
            customAxios.get(`/team/${this.id}/debts`, {headers: {'Authorization': authHeader()}})
            .then(Response => {
                this.debts = Response.data.data
                console.log(Response.data.data)
            })
            .catch(error => {
                NProgress.done()
                console.log(error)
            })
        },
        async fetchRepayments() {
            customAxios.get(`/user/team/${this.id}/repayments`, {headers: {'Authorization': authHeader()}})
            .then(Response => {
                this.repayments.debts = Response.data.data.debts
                this.repayments.credits = Response.data.data.credits
                console.log(Response.data.data)
            })
            .catch(error => {
                NProgress.done()
                console.log(error)
            })
        }
    }
}
</script>

<style scoped>
    .team-main {
        width: 100%;
        min-height: calc(100vh - 80px);

        display: flex;
    }

    .left-content {
        width: 70%;
        min-height: calc(100vh - 80px);

        /* background-color: purple; */
    }

    .right-content {
        width: 30%;
        min-height: calc(100vh - 80px);
    }

    .team-header {
        width: 100%;
        height: 150px;

        display: flex;
        justify-content: center;
        align-items: center;
    }

    .team-header-content {
        width: calc(100% - 60px);
        height: 128px;

        display: flex;
        align-items: center;

        background-color: rgba(0, 0, 0, 0.25);
    }

    .team-header-content > img {
        width: 128px;
        height: 128px;

        outline: 1px solid #224957;
    }

    .team-name {
        margin-left: 30px;
    }

    .team-name > h2 {
        font-size: 42px;
        font-weight: 300;
        color: white;
    }

    .team-name > p {
        margin-left: 5px;
        font-size: 13px;
        font-weight: 200;
        color: white;
    }

    .team-debts {
        width: 100%;
        min-height: calc(100vh - 230px);

        display: flex;
        justify-content: center;
        align-items: flex-start;

        /* background-color: wheat; */
    }

    .debt-list {
        width: calc(100% - 60px);
        min-height: calc(100vh - 241px);

        background-color: rgba(0, 0, 0, 0.1);
    }

    .control-panel {
        width: 100%;
        height: 150px;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .control-panel-content {
        width: calc(100% - 30px);
        height: 128px;
        background-color: rgba(0, 0, 0, 0.25);
    }

    .team-repayments {
        width: 100%;
        min-height: calc(100vh - 230px);

        /* background-color: yellowgreen; */
    }

    .repayments-list {
        width: calc(100% - 30px);
        min-height: calc(100vh - 241px);

        background-color: rgba(0, 0, 0, 0.1);
    }

    .repayment-header {
        width: 100%;
        height: 35px;

        margin-bottom: 3px;

        font-size: 14px;
        font-weight: 300;

        display: flex;
        justify-content: center;
        align-items: center;

        background-color: rgba(0, 0, 0, 0.15);
    }

    .r-credit {
        color: #39BB7A;
    }

    .r-debt {
        color: #b64d4d;
    }

    .loading {
        visibility: hidden;
    }
</style>