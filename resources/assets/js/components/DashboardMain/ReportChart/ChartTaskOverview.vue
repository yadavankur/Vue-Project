<template>
    <div class="task-overview-root">
        <div class="Chart">
            <task-overview-line-chart></task-overview-line-chart>
        </div>
    </div>
</template>

<script>
    import TaskOverviewLineChart from '../../../store/modules/TaskOverviewLineChart'
    export default {
        components: {
            'task-overview-line-chart': TaskOverviewLineChart,
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
</style>