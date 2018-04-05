<template>
    <div>
        <div class="expedite-history-body">
            <expedite-history-body-list-table
                    :orderId="orderId"
                    :quoteId="quoteId"
                    :location="location"
                    :expediteType="expediteType"></expedite-history-body-list-table>
        </div>
    </div>
</template>

<script>
    import ExpediteHistoryBodyListTable from './ExpediteHistoryBodyListTable.vue'
    export default {
        props: {
            expediteType: '',
            orderId: '',
            quoteId: '',
            location: '',
        },
        data () {
            return {
            }
        },
        created() {
            console.log('ExpediteHistoryBody Component created.')
        },
        components: {
            'expedite-history-body-list-table': ExpediteHistoryBodyListTable,
        },
        mounted() {
            console.log('ExpediteHistoryBody Component mounted.')
        },
        methods:
        {

        }
    }
</script>

<style scoped>

</style>
