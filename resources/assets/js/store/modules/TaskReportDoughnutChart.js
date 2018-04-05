
import { Doughnut, mixins } from 'vue-chartjs'

export default Doughnut.extend({
    mixins: [mixins.reactiveData],
    data () {
        return {
            chartData: '',
            labels: ['Completed', 'Overdue', 'InProgress', 'Waiting'],
            taskData: [],
        }
    },
    created () {
        this.taskOverviewDoughnutchart();
    },
    mounted () {
        this.renderChart(this.chartData, {responsive: true, maintainAspectRatio: false})
        // setInterval(() => {
        //     this.fillData()
        // }, 5000)

    },
    methods: {
        taskOverviewDoughnutchart() {
            console.log('TaskOverviewDoughnutChart taskOverviewDoughnutchart!');
            // get count of unread notifications and overdue task
            this.$store.dispatch('taskOverviewDoughnutchart')
                .then((response) => {
                    console.log('TaskOverviewDoughnutChart taskOverviewDoughnutchart response=', response);
                    this.taskData = response.data.taskData;
                    this.fillData();
                })
                .catch((error) => {
                    console.error('TaskOverviewDoughnutChart taskOverviewDoughnutchart error=', error);
                });
        },
        fillData () {
            this.chartData = {
                labels: this.labels,
                datasets: [
                    {
                        backgroundColor: [
                            '#41B883',
                            '#DD1B16',
                            '#00D800',
                            '#00D8FF',
                        ],
                        data: this.taskData,
                    }
                ]
            }
        },
    }
})