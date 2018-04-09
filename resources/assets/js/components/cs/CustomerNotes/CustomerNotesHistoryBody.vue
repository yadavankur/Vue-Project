<template>
    <div>
        <div class="customer-notes-history-body">
            <customer-notes-history-body-list-table></customer-notes-history-body-list-table>
        </div>
    </div>
</template>

<script>
    import CustomerNotesHistoryBodyListTable from './CustomerNotesHistoryBodyListTable.vue'

    export default 
    {   data () {         return {         }        },
        created() {      console.log('/cs/comments/historybody--created')        },
        components: { 'customer-notes-history-body-list-table': CustomerNotesHistoryBodyListTable,        },
        mounted() {    console.log('/cs/comments/-- Component mounted.')          },
        methods:         {        }
    }
</script>

<style scoped>

</style>
