<template>
    <div>Not used any more</div>
    <div id="root-task-list">
        <div class="app-row" >
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <a class="accordion-toggle" data-toggle="collapse"  href="#task-section">
                        All my tasks
                    </a>
                </div>
                <div id="task-section" class="panel-collapse collapse in ">
                    <div class="row-content">
                        <task-list-table></task-list-table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import Vue from 'vue';
    import { mapGetters, mapState, mapActions} from 'vuex'
    import TaskListTable from './TaskList/TaskListTable.vue'

    export default {
        computed: {
            ...mapGetters({
            }),
            ...mapState({
                user: state => state.authUser,
            }),
        },
        created() {
            console.log('/resources/assets/js/components/DashboardMain/TaskList.vue-TaskList vue created!');
        },
        data () {
            return {}
        },
        components: {
            'task-list-table': TaskListTable,
        },
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