
import { Line, mixins } from 'vue-chartjs'

export default Line.extend({
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
                        }
                    }]
                },
                legend: {
                    display: true
                },
                responsive: true,
                maintainAspectRatio: false
            },
            chartData: ''
        }
    },
    created () {
        this.fillData()
    },

    mounted () {
        this.renderChart(this.chartData, this.options);
        // setInterval(() => {
        //     this.fillData()
        // }, 5000)
    },

    methods: {
        fillData () {
            this.chartData = {
                labels: [
                    '1', '2', '3', '4',
                    '5', '6', '7', '8',
                    '9', '10', '11', '12',
                ],
                datasets: [
                    {
                        label: 'Weekly',
                        borderColor: "rgba(35, 198, 200, 0.3)",
                        backgroundColor: "rgba(255,255,255, 0.1)",
                        data: [
                            this.getRandomInt(), this.getRandomInt(), this.getRandomInt(), this.getRandomInt(),
                            this.getRandomInt(), this.getRandomInt(), this.getRandomInt(), this.getRandomInt(),
                            this.getRandomInt(), this.getRandomInt(), this.getRandomInt(), this.getRandomInt()
                        ]
                    },
                    {
                        label: 'Monthly',
                        borderColor: "rgba(0,255,0,0.3)",
                        backgroundColor: "rgba(255,255,255, 0.1)",
                        data: [
                            this.getRandomInt(), this.getRandomInt(), this.getRandomInt(), this.getRandomInt(),
                            this.getRandomInt(), this.getRandomInt(), this.getRandomInt(), this.getRandomInt(),
                            this.getRandomInt(), this.getRandomInt(), this.getRandomInt(), this.getRandomInt()
                        ]
                    }
                ]
            }
        },
        getRandomInt () {
            return Math.floor(Math.random() * (50 - 5 + 1)) + 5
        }
    }
})