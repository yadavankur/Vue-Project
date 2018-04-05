
import { Bar, mixins } from 'vue-chartjs'

export default Bar.extend({
    mixins: [mixins.reactiveData],
    data () {
        return {
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        },
                        gridLines: {
                            lineWidth: 1,
                            display: true
                        }
                    }],
                    xAxes: [ {
                        gridLines: {
                            lineWidth: 1,
                            display: true
                        },
                        barPercentage: 1,
                        categoryPercentage: 0.5,
                    }]
                },
                legend: {
                    display: true
                },
                responsive: true,
                maintainAspectRatio: false
            },
            chartData: '',
            labels: [],
            completedTasks: [],
            overDueTasks: [],
            inProgressTasks: [],
            waitingTasks: [],
            timer: null,
        }
    },
    created () {
        //this.fillData()
        this.taskOverviewBarchart();
    },
    beforeDestroy() {
        console.log('TaskOverviewBarChart beforeDestroy!');
        //this.cancelAutoUpdateChart();
    },
    mounted () {
        this.renderChart(this.chartData, this.options);
        // this.timer = setInterval(() => {
        //     this.taskOverviewBarchart()
        // }, 5000)
    },
    methods: {
        cancelAutoUpdateChart() {
            console.log('TaskOverviewBarChart cancelAutoUpdateChart!');
            clearInterval(this.timer);
        },
        taskOverviewBarchart() {
            console.log('TaskOverviewBarChart taskOverviewBarchart!');
            // get count of unread notifications and overdue task
            this.$store.dispatch('taskOverviewBarchart')
                .then((response) => {
                    console.log('TaskOverviewBarChart taskOverviewBarchart response=', response);
                    this.labels = response.data.labels;
                    this.completedTasks = response.data.completedTasks;
                    this.overDueTasks = response.data.overDueTasks;
                    this.inProgressTasks = response.data.inProgressTasks;
                    this.waitingTasks = response.data.waitingTasks;
                    this.fillData();
                })
                .catch((error) => {
                    console.error('TaskOverviewBarChart taskOverviewBarchart error=', error);
                });
        },
        fillData () {
            this.chartData = {
                labels: this.labels,
                datasets: [
                    {
                        label: 'Completed',
                        backgroundColor:  '#41B883',
                        data: this.completedTasks,
                    },
                    {
                        label: 'Overdue',
                        backgroundColor:  '#DD1B16',
                        data: this.overDueTasks,
                    },
                    {
                        label: 'InProgress',
                        backgroundColor:  '#00D800',
                        data: this.inProgressTasks,
                    },
                    {
                        label: 'Waiting',
                        backgroundColor:  '#00D8FF',
                        data: this.waitingTasks,
                    },
                ]
            }
        },
    }
})