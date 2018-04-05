<template>
    <div>
        <div>
            <div class="activity-info-body">
                <div class="form-group">
                    <div class="activity-info-row">
                        <div class="block_title">
                            <label for="originalDeliveryDate">Original Delivery Date</label>
                        </div>
                        <div class="block_content">
                            <Date-picker
                                    id="originalDeliveryDate"
                                    :value="originalDeliveryDate"
                                    format="dd/MM/yyyy" type="date"
                                    :disabled="true"
                                    readonly
                            >
                            </Date-picker>
                        </div>
                    </div>
                    <div class="activity-info-row">
                        <div class="block_title"><label for="currentDeliveryDate">Current Delivery Date</label></div>
                        <div class="block_content">
                            <Date-picker
                                    id="currentDeliveryDate"
                                    :value="currentDeliveryDate"
                                    format="dd/MM/yyyy" type="date"
                                    :disabled="actionType=='view'"
                                    :readonly="actionType=='view'"
                                    @on-change="onChangeDeliveryDate"
                                    @on-clear="onClearDeliverDate"
                            >
                            </Date-picker>
                        </div>
                    </div>
                    <div class="activity-info-row">
                        <div class="block_title"><label for="status">Status</label></div>
                        <div class="block_content">
                            <Select :filterable="actionType == 'update'"
                                    :clearable="actionType == 'update'"
                                    v-model="status"
                                    @on-change="onChangeStatus"
                                    :disabled="actionType=='view'"
                                    placeholder="Please select a status..."
                                    style="width:180px"
                            >
                                <Option v-for="item in statusOptions" :value="item.value" :key="item" :label="item.label">{{ item.label }}</Option>
                            </Select>
                        </div>
                    </div>
                    <div class="activity-info-row">
                        <div><label for="Notes">Notes</label>
                            <button type="button" class="btn btn-warning btn-xs"
                                    @click="onOpenCPMActivityNotesHistoryModal">
                                <i class="glyphicon glyphicon-list-alt"></i> Notes History
                            </button>
                        </div>
                        <div>
                            <a><strong>{{ updatedInfo }}</strong></a>
                            <div v-if="lastDowellNotes" class="notes" v-html="lastDowellNotes"></div>
                            <div>
                                <Input v-model="dowellNotes"
                                       type="textarea" :autosize="{minRows: 5,maxRows: 8}"
                                       placeholder="Please input the notes...">
                                </Input>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            itemData: null,
            order: null,
            actionType: '',
        },
        data () {
            return {
                lastDowellNotes: '',
                dowellNotes: '',
                updatedInfo: '',
                originalDeliveryDate: '',
                currentDeliveryDate: '',
                newDeliveryDate: '',
                statusOptions: [],
                status: '',
            }
        },
        created() {
            console.log('/resources/assets/js/components/booking/customerservice/orderactivity/activityinfo/ActivityBodyInfo.vue- CSActivityInfoBody Component created.');
            // need to change the date string type to date type
            // datepicker only accept date type
            // otherwise it will cause issues on IE11
            if (this.itemData && this.itemData.end_date)
            {
                this.originalDeliveryDate = this.itemData.end_date;
            }
            else if (this.itemData && this.itemData.start_date)
            {
                this.originalDeliveryDate = this.itemData.start_date + this.itemData.duration;
            }
            else
                this.originalDeliveryDate = '';

            this.currentDeliveryDate =  (this.itemData && this.itemData.delivery_date)? this.itemData.delivery_date: '';
            this.newDeliveryDate = this.currentDeliveryDate;
            this.originalDeliveryDate = this.getFormatDate(this.formatDate(this.originalDeliveryDate, 'DD/MM/YYYY'));
            this.currentDeliveryDate = this.getFormatDate(this.formatDate(this.currentDeliveryDate, 'DD/MM/YYYY'));

            // get the latest customer notes
            this.getLatestActivityNotesRequest();

            this.$store.dispatch('getActivityStatuses')
                .then((response) => {
                    console.log('/resources/assets/js/components/booking/customerservice/orderactivity/activityinfo/ActivityBodyInfo.vue-getActivityStatuses response=', response);
                    this.statusOptions = response.body.statuses;
                    let status = this.getStatusLabel(this.itemData.status_id);
                    console.log('/resources/assets/js/components/booking/customerservice/orderactivity/activityinfo/ActivityBodyInfo.vue-getActivityStatuses status=', status);
                    this.status = status;
                })
                .catch((error) => {
                        console.log('/resources/assets/js/components/booking/customerservice/orderactivity/activityinfo/ActivityBodyInfo.vue-getActivityStatuses error=', error);
                });
        },
        components: {
        },
        mounted() {
            console.log('/resources/assets/js/components/booking/customerservice/orderactivity/activityinfo/ActivityBodyInfo.vue-CSActivityInfoBody Component mounted.')
        },
        methods:
        {
            onOpenCPMActivityNotesHistoryModal() {
                console.log('/resources/assets/js/components/booking/customerservice/orderactivity/activityinfo/ActivityBodyInfo.vue-onOpenCPMActivityNotesHistoryModal');
                this.$emit('onOpenCPMActivityNotesHistoryModal', this.itemData);
            },
            getStatusLabel(statusId) {
                console.log('/resources/assets/js/components/booking/customerservice/orderactivity/activityinfo/ActivityBodyInfo.vue-getStatusLabel statusId=', statusId);
                let selectedValue = '';
                this.statusOptions.forEach( (item) => {
                    console.log('/resources/assets/js/components/booking/customerservice/orderactivity/activityinfo/ActivityBodyInfo.vue-getStatusLabel item=', item);
                    if (parseInt(item.value) == parseInt(statusId))
                    {
                        selectedValue = item.value;
                    }
                });
                return selectedValue;
            },
            onChangeStatus(selVal) {
                console.log('/resources/assets/js/components/booking/customerservice/orderactivity/activityinfo/ActivityBodyInfo.vue-onChangeStatus value=', selVal);
            },
            onChangeDeliveryDate(value) {
                console.log('/resources/assets/js/components/booking/customerservice/orderactivity/activityinfo/ActivityBodyInfo.vue-onChangeDeliveryDate value=', value);
                this.newDeliveryDate = value;
            },
            onClearDeliverDate(value) {
                console.log('/resources/assets/js/components/booking/customerservice/orderactivity/activityinfo/ActivityBodyInfo.vue-onClearDeliverDate value=', value);
                this.newDeliveryDate = value;
            },
            getLatestActivityNotesRequest() {
                let payload = {
                    orderId: this.itemData.order_id,
                    quoteId: this.itemData.quote_id,
                    locationId: this.itemData.location_id,
                    activityId: this.itemData.activity_id,
                    type: 'DOWELL_NOTES',
                };
                this.$store.dispatch('getLatestActivityNotesRequest', payload)
                    .then((response) => {
                        console.log('/resources/assets/js/components/booking/customerservice/orderactivity/activityinfo/ActivityBodyInfo.vue-getLatestNotesRequest response=', response);
                        let notes = response.data;
                        if (!this.isEmpty(notes)) {
                            this.updatedInfo = 'Last update by '+ notes.created_by.name
                                + ' on ' + notes.updated_at;
                            this.lastDowellNotes = notes.comment;
                        }
                        else
                        {
                            this.updatedInfo = '';
                            this.lastDowellNotes = '';
                        }
                    })
                    .catch((error) => {
                        console.log('/resources/assets/js/components/booking/customerservice/orderactivity/activityinfo/ActivityBodyInfo.vue-getLatestNotesRequest error=', error);
                    });
            },
        }
    }
</script>

<style scoped>
    .block_title {
        width: 50%;
        text-align: left;
        display: inline-block;
    }
    .block_content {
        display: inline-block;
    }
    .activity-info-row {
        width: 100%;
        margin-top: 10px;
        margin-bottom: 10px;
    }
    .notes {
        height: 80px;
        overflow-y: scroll;
    }
</style>
