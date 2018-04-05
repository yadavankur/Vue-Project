<template>
    <div>
        <div class="cpm-activity-notes-history-body">
            <cpm-activity-notes-history-body-list-table
                    :quoteId="itemData? itemData.quote_id: ''"
                    :locationId="itemData? itemData.location_id: ''"
                    :activityId="itemData? itemData.activity_id: ''"
                    :orderId="itemData? itemData.order_id: ''">
            </cpm-activity-notes-history-body-list-table>
        </div>
    </div>
</template>

<script>
    import CPMActivityNotesHistoryBodyListTable from './CPMActivityNotesHistoryBodyListTable.vue'
    export default {
        props: {
            itemData: null,
        },
        data () {
            return {
            }
        },
        created() {
            console.log('CPMActivityNotesHistoryBody Component created.')
        },
        components: {
            'cpm-activity-notes-history-body-list-table': CPMActivityNotesHistoryBodyListTable,
        },
        mounted() {
            console.log('CPMActivityNotesHistoryBody Component mounted.')
        },
        methods: {
        }
    }
</script>

<style scoped>
</style>
