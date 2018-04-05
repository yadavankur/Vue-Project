<template>
    <div class="task-report-doughnut-root">
        <div class="Chart">
            <task-report-doughnut-chart></task-report-doughnut-chart>
        </div>
    </div>
</template>

<script>
    import TaskReportDoughnutChart from '../../../store/modules/TaskReportDoughnutChart'
    export default {
        components: {
            'task-report-doughnut-chart': TaskReportDoughnutChart,
        },
        data () {
            return {
                dataPoints: null
            }
        },
        mounted () {
        },
        methods: {
        }
    }
</script>

<style scoped>
    .Chart {
        /*padding: 20px;*/
        /*box-shadow: 0px 0px 20px 2px rgba(0, 0, 0, .4);*/
        /*border-radius: 20px;*/
        /*margin: 50px 0;*/
    }
</style>