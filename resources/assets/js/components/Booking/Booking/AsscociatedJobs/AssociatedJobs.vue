<template>
    <div class="associated-jobs-root table-responsive">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <a class="accordion-toggle" data-toggle="collapse"  href="#associatedjobs">
                    Associated Orders
                </a>
            </div>
            <div id="associatedjobs" class="panel-collapse collapse in table-responsive">
                <associated-jobs-list-table @onSearch="onSearch"></associated-jobs-list-table>
            </div>
        </div>
    </div>
</template>

<script>
    import AssociatedJobsListTable from './AssociatedJobsListTable.vue'
    export default 
    {   data () {     return {      }      },
        created() {     console.log('AssociatedJobs Component created.')   },
        components: {      'associated-jobs-list-table' : AssociatedJobsListTable,    },
        mounted() {
            console.log('AssociatedJobs Component mounted.')
        },
        methods:
        {
            onSearch(payload) {
                console.log('AssociatedJobs -> onSearch orderId=', payload.orderNo);
                console.log('AssociatedJobs -> onSearch location=', payload.location);
                this.$emit('onSearch', payload);
            }
        }
    }
</script>

<style scoped>
    .associated-jobs-root {
        margin: 5px;
    }
    .panel > .table-responsive {
        margin: 5px;
    }
</style>