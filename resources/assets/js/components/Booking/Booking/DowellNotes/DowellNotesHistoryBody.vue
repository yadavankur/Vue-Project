<template>
    <div>
        <div class="dowell-notes-history-body">
            <dowell-notes-history-body-list-table
                    :quoteId="quoteId"
                    :location="location"
                    :orderId="orderId"></dowell-notes-history-body-list-table>
        </div>
    </div>
</template>

<script>
    import DowellNotesHistoryBodyListTable from './DowellNotesHistoryBodyListTable.vue'
    export default {
        props: {
            orderId: '',
            quoteId: '',
            location: '',
        },
        data () {
            return {
            }
        },
        created() {
            console.log('DowellNotesHistoryBody Component created.')
        },
        components: {
            'dowell-notes-history-body-list-table': DowellNotesHistoryBodyListTable,
        },
        mounted() {
            console.log('DowellNotesHistoryBody Component mounted.')
        },
        methods:
        {

        }
    }
</script>

<style scoped>

</style>
