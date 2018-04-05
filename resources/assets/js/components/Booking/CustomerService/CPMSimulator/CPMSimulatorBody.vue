<template>
    <div>
        <div>
            <table class="table table-condensed table-responsive">
                <thead>
                    <tr>
                        <th>Start Date</th>
                        <th style="text-align: center;">Total Days Needed</th>
                        <th>Forecast Finish Date</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="col-lg-4 col-md-4 col-sm-1 col-xs-1">
                            <Date-picker
                                         :value="baseStartDate"
                                         format="dd/MM/yyyy" type="date"
                                         :clearable="false"
                                         :options="weekends"
                                         style="width: 180px"
                                         @on-change="onChangeBaseStartDate">
                            </Date-picker>
                        </td>
                        <td class="col-lg-4 col-md-4 col-sm-1 col-xs-1" style="text-align: center;">{{ cpmInfo && cpmInfo.totalDays ? cpmInfo.totalDays : '' }}</td>
                        <td class="col-lg-4 col-md-4 col-sm-1 col-xs-1">{{ finishDate }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div>
            <table class="table table-condensed table-responsive table-striped">
                <thead>
                <tr>
                    <th>IsCritical?</th>
                    <th>Activity</th>
                    <th>Duration </th>
                    <th>Start Date</th>
                    <th>Finish Date</th>
                    <th>EST</th>
                    <th>EFT</th>
                    <th>Latest Start Date</th>
                    <th>Latest Finish Date</th>
                    <th>Slack Time</th>
                </tr>
                </thead>
                <tbody>
                <tr
                        v-for="(item, index) in orderActivities"
                        :key ="item"
                        v-if="item"
                >
                    <td align="center"><Icon v-if="item.isCritical" type="checkmark-circled" size="20"></Icon></td>
                    <td class="activity-name">{{ item.name }}</td>
                    <td class="duration">
                        <Input-number v-model="item.duration" :step="0.1" :min="0" @on-change="onChangeDuration(item, ...arguments)"></Input-number>
                    </td>
                    <td class="date">
                        <!--<Date-picker :value="getFormatDate(item.start_date, 'YYYY-MM-DD')"-->
                                     <!--format="dd/MM/yyyy" type="date"-->
                                     <!--:clearable="false" disabled readonly-->
                                     <!--@on-change="onChangeStartDate(item, ...arguments)">-->
                        <!--</Date-picker>-->
                        {{ formatDate(item.start_date, 'DD/MM/YYYY') }}
                    </td>
                    <td class="date">
                        <!--<Date-picker :value="getFormatDate(item.end_date, 'YYYY-MM-DD')"-->
                                     <!--format="dd/MM/yyyy" type="date"-->
                                     <!--:clearable="false" disabled readonly-->
                                     <!--@on-change="onChangeEndDate(item, ...arguments)">-->
                        <!--</Date-picker>-->
                        {{ formatDate(item.end_date, 'DD/MM/YYYY') }}
                    </td>
                    <td>{{ item.est }}</td>
                    <td>{{ item.eet }}</td>
                    <!--<td>{{ getLatestStartDate(item) }}</td>-->
                    <!--<td>{{ getLatestFinishDate(item)  }}</td>-->
                    <td>{{ formatDate(item.lst_date, 'DD/MM/YYYY') }}</td>
                    <td>{{ formatDate(item.let_date, 'DD/MM/YYYY')  }}</td>
                    <td>{{ item.float }}</td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
    import Vue from 'vue';
    import { mapGetters, mapState, mapActions} from 'vuex'

    export default {
        props: {
            quoteId: '',
            locationId: '',
            orderId: '',
            serviceId: '',
        },
        computed: {
            ...mapState({
                user: state => state.authUser,
            }),
        },
        data () {
            return {
                baseStartDate: '',
                updatedStartDate: '',
                orderActivities: [],
                cpmInfo: null,
                finishDate: '',
                weekends: {
                    disabledDate (date) {
                        const disabledDay = date.getDay();
                        //return ((disabledDay === 0) || (disabledDay === 6) || (date && date.valueOf() < Date.now() - 86400000));
                        return ((disabledDay === 0) || (disabledDay === 6));
                    }
                }
            }
        },
        created() {
            console.log('CPMSimulatorBody Component created.');
            this.simulateCPM();
        },
        components: {
        },
        mounted() {
            console.log('CPMSimulatorBody Component mounted.')
        },
        methods: {
            getLatestStartDate(item) {
                let startDate = this.getFormatDate(item.start_date, 'YYYY-MM-DD');
                let slackDuration = parseInt(item.float);
                let latestStartDate = startDate.add(slackDuration, 'd');
                return this.formatDate(latestStartDate, 'DD/MM/YYYY');
            },
            getLatestFinishDate(item) {
                let finishDate = this.getFormatDate(item.end_date, 'YYYY-MM-DD');
                let slackDuration = parseInt(item.float);
                let latestFinishDate = finishDate.add(slackDuration, 'd');
                return this.formatDate(latestFinishDate, 'DD/MM/YYYY');
            },
            simulateCPM() {
                let payload = {
                    quoteId: this.quoteId,
                    locationId: this.locationId,
                    orderId: this.orderId,
                    serviceId: this.serviceId,
                    orderActivities: [],
                    baseStartDate: this.updatedStartDate,
                };
                this.$store.dispatch('simulateCPM', payload)
                    .then((response) => {
                        console.log('simulateCPM response=', response);
                        this.orderActivities = response.body.allActivities;
                        this.cpmInfo = response.body;
                        if (!this.isEmpty(this.cpmInfo.baseStartDate))
                        {
                            //this.baseStartDate = this.cpmInfo.baseStartDate.date;
                            this.baseStartDate = this.getFormatDate(this.cpmInfo.baseStartDate.date, 'YYYY-MM-DD')
                        }
                        else
                            this.baseStartDate = this.getCurrentDatetime();
                        this.updatedStartDate = this.baseStartDate.format('DD/MM/YYYY');
                        this.calculateFinishDate();
                        this.$emit('hasCPM', this.cpmInfo.numOfCpmActivity > 0);
                    })
                    .catch((error) => {
                        console.log('simulateCPM error=', error);
                    });
            },
            calculateFinishDate() {
                console.log('calculateFinishDate');
                if (!this.isEmpty(this.cpmInfo.forecastFinishDate))
                {
                    let finishDate = this.getFormatDate(this.cpmInfo.forecastFinishDate.date, 'YYYY-MM-DD');
                    this.finishDate = this.formatDate(finishDate, 'DD/MM/YYYY');
                }
            },
            onChangeBaseStartDate(value) {
                console.log('onChangeBaseStartDate value=', value);
                  this.updatedStartDate = value;
//                this.cpmInfo.baseStartDate = this.getFormatDate(value);
//                let totalDuration = parseInt(this.cpmInfo.totalDays);
//                let finishDate = this.getFormatDate(value).add(totalDuration - 1, 'd');
//                this.finishDate = this.formatDate(finishDate, 'DD/MM/YYYY');
            },
            onChangeStartDate(item, value) {
                console.log('onChangeStartDate item=', item);
                console.log('onChangeStartDate value=', value);
                item.start_date = this.getFormatDate(value);
                // recalculate the end_date,
                // assume duration is the same
                let duration = parseInt(item.duration);
                item.end_date = this.getFormatDate(value).add(duration, 'd');
            },
            onChangeEndDate(item, value) {
                console.log('onChangeEndDate item=', item);
                console.log('onChangeEndDate value=', value);
                item.end_date = this.getFormatDate(value);
                // recalculate the duration, assume start_date is the same
                let duration = item.end_date.diff(item.start_date, 'd');
                if (duration != 0)
                    item.duration = duration;
            },
            onChangeDuration(item, value) {
                console.log('onChangeDuration item=', item);
                console.log('onChangeDuration value=', value);
                let duration = parseInt(value);
                console.log('onChangeDuration duration=', duration);
                let startDate = this.getFormatDate(item.start_date, 'YYYY-MM-DD');
                console.log('onChangeDuration startDate=', startDate);
                item.end_date = startDate.add(duration, 'd');
                console.log('onChangeDuration item.end_date=', item.end_date);
            }
        }
    }
</script>

<style scoped>
    .activity-name {
        width: 200px;
    }
    .duration {
        width: 70px;
    }
    .date {
        width: 120px;
    }
    td {
        vertical-align: middle !important;
    }
</style>
