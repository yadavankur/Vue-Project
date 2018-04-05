<template>
    <div class="cs-associated-jobs-root table-responsive">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <a class="accordion-toggle" data-toggle="collapse"  href="#associatedjobs">
                    Associated Jobs
                </a>
            </div>
            <div id="associatedjobs" class="panel-collapse collapse in table-responsive">
                <cs-associated-jobs-list-table></cs-associated-jobs-list-table>
            </div>
        </div>
    </div>
</template>

<script>
    import CSAssociatedJobsListTable from './AssociatedJobsInfo/AssociatedJobsListTable.vue'
    export default 
    {   data () {   return {     }     },
        created() {  console.log('/components/booking/customerservice/AssociatedJObsInfo.vue-CSAssociatedJobs Component created.')    },
        components: {   'cs-associated-jobs-list-table' : CSAssociatedJobsListTable,     },
        mounted() {  console.log('/components/booking/customerservice/AssociatedJObsInfo.vue-CSAssociatedJobs Component mounted.')     },
        methods:  {   }
    }
</script>

<style scoped>
    /*.panel-primary > .panel-heading {*/
        /*color: white;*/
        /*background-color: #0a5b9e;*/
        /*border-color: #0a5b9e;*/
        /*height: 40px;*/
    /*}*/
    /*.panel-heading a {*/
        /*color: white;*/
    /*}*/
    /*.panel-heading .accordion-toggle:after {*/
        /*!* symbol for "opening" panels *!*/
        /*font-family: 'Glyphicons Halflings';*/
        /*content: "\e114";*/
        /*float: right;*/
        /*color: white;*/
    /*}*/
    /*.panel-heading .accordion-toggle.collapsed:after {*/
        /*!* symbol for "collapsed" panels *!*/
        /*content: "\e080";*/
    /*}*/

</style>