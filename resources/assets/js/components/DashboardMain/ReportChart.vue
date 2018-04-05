<template>
    <div>Not used any more</div>
    <div id="root-report-chart">
        <div class="app-row" >
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <a class="accordion-toggle" data-toggle="collapse"  href="#report-chart-section">
                        Task Reports
                    </a>
                </div>
                <div id="report-chart-section" class="panel-collapse collapse in ">
                    <div class="row-content">
                        <task-report-doughnut-chart></task-report-doughnut-chart>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import Vue from 'vue';
    import { mapGetters, mapState, mapActions} from 'vuex'
    import TaskReportDoughnutChart from './ReportChart/TaskReportDoughnutChart.vue'

    export default {
        computed: {
            ...mapGetters({
            }),
            ...mapState({
                user: state => state.authUser,
            }),
        },
        created() {
            console.log('/resources/assets/js/components/DashboardMain/ReportChart.vue-TaskReportDoughnutChart vue created!');
        },
        data () {
            return {}
        },
        components: {
            'task-report-doughnut-chart': TaskReportDoughnutChart,
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
    /*.row-content {*/
        /*padding: 10px 10px 30px 10px;*/
    /*}*/
</style>