<template>
    <div>
        <div id="cpmSimulator">
            <custom-modal :value="isShowModal" @cancel="onClickCancel" large effect="fade" :backdrop="true">
                <div slot="modal-header" class="modal-header">
                    <h4 class="modal-title"> CPM Simulation</h4>
                </div>
                <div slot="modal-body" class="modal-body table-responsive">
                    <cpm-simulator-body v-if="isShowModal"
                                        :quoteId="quoteId"
                                        :locationId="locationId"
                                        :orderId="orderId"
                                        :serviceId="serviceId"
                                        @hasCPM="hasCPM"
                                        ref="cpmSimulatorBody">
                    </cpm-simulator-body>
                </div>
                <div slot="modal-footer" class="modal-footer">
                    <button type="button" class="btn btn-primary" @click="onClickCancel">Cancel</button>
                    <!--<button type="button" class="btn btn-warning" @click="onClickCreateCPM">-->
                        <!--{{ hasActivity? 'Re-Create CPM' :'Create CPM' }}-->
                    <!--</button>-->
                    <button v-if="hasActivity" type="button" class="btn btn-success" @click="onClickSimulate">Re-Calculate CPM</button>
                    <button v-if="hasActivity" type="button" class="btn btn-danger" @click="onClickUpdate" title="Update Date and Duration">Update</button>
                </div>
            </custom-modal>
        </div>
    </div>
</template>

<script>
    import Vue from 'vue';
    import { mapGetters, mapState, mapActions} from 'vuex'
    import modal from 'vue-strap/src/Modal'
    import CPMSimulatorBody from './CPMSimulatorBody.vue'

    export default {
        props: {
            isShowCPMSimulator: false,
            quoteId: '',
            locationId: '',
            orderId: '',
            serviceId: '',
        },
        data () {
            return {
                isVisible: false,
                orderActivities: [],
                hasActivity: false,
            }
        },
        computed: {
            ...mapState({
                user: state => state.authUser,
            }),
            isShowModal() {
                this.isVisible = this.isShowCPMSimulator;
                return this.isVisible;
            },
        },
        created() {
            console.log('CPMSimulatorMain Component created.');
        },
        components: {
            'cpm-simulator-body': CPMSimulatorBody,
            'custom-modal': modal,
        },
        mounted() {
            console.log('CPMSimulatorMain Component mounted.');
        },
        methods: {
            hasCPM(value) {
                console.log('hasCPM');
                this.hasActivity = value;
            },
            onClickCancel() {
                console.log('onClickCancel');
                this.$emit('onCloseCPMSimulatorModal');
            },
            onClickCreateCPM() {
                console.log('onClickCreateCPM');
                let title = 'You are about to delete all current activities and create new ones for this order!';
                if (!this.hasActivity)
                    title = 'You are about to create activities for this order!';
                this.$swal({
                    title: 'Are you sure?',
                    text: title,
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes',
                    cancelButtonText: 'cancel',
                    confirmButtonClass: 'btn btn-success btn-sm',
                    cancelButtonClass: 'btn btn-danger btn-sm',
                    allowOutsideClick: false
                })
                .then((success) => {
                    this.createCPM();
                })
                .catch((dismiss) => {});
            },
            onClickUpdate() {
                console.log('onClickUpdate');
                this.$swal({
                    title: 'Are you sure?',
                    text: 'You are about to update the duration and dates for this order!',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes',
                    cancelButtonText: 'cancel',
                    confirmButtonClass: 'btn btn-success btn-sm',
                    cancelButtonClass: 'btn btn-danger btn-sm',
                    allowOutsideClick: false
                })
                .then((success) => {
                    this.updateCPM();
                })
                .catch((dismiss) => {});
            },
            onClickSimulate() {
                console.log('onClickSimulate');
                // send request to
                // recalculate the critical path
                this.simulateCPM();
            },
            calculateFinishDate() {
                //let totalDuration = parseInt(this.$refs.cpmSimulatorBody.cpmInfo.totalDays);
                //let finishDate = this.getFormatDate(this.$refs.cpmSimulatorBody.cpmInfo.baseStartDate.date, 'YYYY-MM-DD').add(totalDuration - 1, 'd');
                let finishDate = this.getFormatDate(this.$refs.cpmSimulatorBody.cpmInfo.forecastFinishDate.date, 'YYYY-MM-DD');
                this.$refs.cpmSimulatorBody.finishDate = this.formatDate(finishDate, 'DD/MM/YYYY');
            },
            simulateCPM() {
                let payload = {
                    quoteId: this.quoteId,
                    locationId: this.locationId,
                    orderId: this.orderId,
                    serviceId: this.serviceId,
                    baseStartDate: this.$refs.cpmSimulatorBody.updatedStartDate,
                    orderActivities: this.$refs.cpmSimulatorBody.orderActivities,
                };
                this.$store.dispatch('reCalculateCPM', payload)
                    .then((response) => {
                        console.log('reCalculateCPM response=', response);
                        this.$refs.cpmSimulatorBody.orderActivities = response.body.allActivities;
                        this.$refs.cpmSimulatorBody.cpmInfo = response.body;
                        this.$refs.cpmSimulatorBody.calculateFinishDate();
                    })
                    .catch((error) => {
                        console.log('reCalculateCPM error=', error);
                    });
            },
            updateCPM() {
                let payload = {
                    quoteId: this.quoteId,
                    locationId: this.locationId,
                    orderId: this.orderId,
                    serviceId: this.serviceId,
                    orderActivities: this.$refs.cpmSimulatorBody.orderActivities,
                    baseStartDate: this.$refs.cpmSimulatorBody.updatedStartDate,
                };
                this.$store.dispatch('updateCPMRequest', payload)
                    .then((response) => {
                        console.log('updateCPMRequest response=', response);
                        this.$refs.cpmSimulatorBody.orderActivities = response.body.allActivities;
                        this.$refs.cpmSimulatorBody.cpmInfo = response.body;
                        this.$refs.cpmSimulatorBody.calculateFinishDate();
                        this.hasCPM(this.$refs.cpmSimulatorBody.cpmInfo.numOfCpmActivity > 0);
                    })
                    .catch((error) => {
                        console.log('updateCPMRequest error=', error);
                    });
            },
            createCPM() {
                let payload = {
                    quoteId: this.quoteId,
                    locationId: this.locationId,
                    orderId: this.orderId,
                    serviceId: this.serviceId,
                    baseStartDate: this.$refs.cpmSimulatorBody.updatedStartDate,
                    orderActivities: [],
                };
                this.$store.dispatch('createCPMRequest', payload)
                    .then((response) => {
                        console.log('createCPMRequest response=', response);
                        this.$refs.cpmSimulatorBody.orderActivities = response.body.allActivities;
                        this.$refs.cpmSimulatorBody.cpmInfo = response.body;
                        this.$refs.cpmSimulatorBody.calculateFinishDate();
                        this.hasCPM(this.$refs.cpmSimulatorBody.cpmInfo.numOfCpmActivity > 0);
                    })
                    .catch((error) => {
                        console.log('createCPMRequest error=', error);
                    });
            },
        }
    }
</script>

<style scoped>
    /*.modal-header {*/
        /*padding: 15px;*/
        /*border-bottom: 1px solid #e5e5e5;*/
        /*color: white !important;*/
        /*background-color: #0a5b9e !important;*/
        /*border-color: #0a5b9e;*/
        /*border-top-right-radius: 3px;*/
        /*border-top-left-radius: 3px;*/
    /*}*/
    .modal-body {
        min-height: 400px;
        max-height: 400px;
    }
</style>
