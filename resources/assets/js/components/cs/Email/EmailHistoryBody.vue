<template>
    <div>
        <div class="email-history-body">
            <email-history-body-list-table></email-history-body-list-table>
        </div>
    </div>
</template>

<script>
    import EmailHistoryBodyListTable from './EmailHistoryBodyListTable.vue'
    export default {
        data () {
            return {
            }
        },
        created() {
            console.log('EmailHistoryBody Component created.')
        },
        components: {
            'email-history-body-list-table': EmailHistoryBodyListTable,
        },
        mounted() {
            console.log('EmailHistoryBody Component mounted.')
        },
        methods:
        {

        }
    }
</script>

<style scoped>

</style>
