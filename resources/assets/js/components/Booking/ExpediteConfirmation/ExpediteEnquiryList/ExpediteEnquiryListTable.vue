<template>
    <div>
        <expedite-enquiry-modal
                :isShowExpediteEnquiry="isShowExpediteEnquiry"
                :expediteType="expediteType"
                :order="selectedOrder? selectedOrder : null"
                @onCloseExpediteEnquiryModal="onCloseExpediteEnquiryModal"
        >
        </expedite-enquiry-modal>
        <expedite-enquiry-history-modal
                :expediteType="expediteType"
                :orderId="selectedOrder? selectedOrder.UDF1: ''"
                :quoteId="selectedOrder? selectedOrder.quote_id: ''"
                :location="selectedOrder? selectedOrder.QUOTE_NUM_PREF: ''"
                :isShowExpediteEnquiryHistory="isShowExpediteEnquiryHistory"
                @onCloseExpediteEnquiryHistoryModal="onCloseExpediteEnquiryHistoryModal"
        >
        </expedite-enquiry-history-modal>
        <dowell-notes-preparation-modal
                :isShowDowellNotes="isShowDowellNotes"
                :orderId="selectedOrder? selectedOrder.UDF1: ''"
                :quoteId="selectedOrder? selectedOrder.quote_id: ''"
                :location="selectedOrder? selectedOrder.QUOTE_NUM_PREF: ''"
                @onCloseDowellNotesModal="onCloseDowellNotesModal"
                @onRefreshNotes="onRefreshNotes"
        >
        </dowell-notes-preparation-modal>
        <dowell-notes-history-modal
                :isShowDowellNotesHistory="isShowDowellNotesHistory"
                :orderId="selectedOrder? selectedOrder.UDF1: ''"
                :quoteId="selectedOrder? selectedOrder.quote_id: ''"
                :location="selectedOrder? selectedOrder.QUOTE_NUM_PREF: ''"
                @onCloseDowellNotesHistoryModal="onCloseDowellNotesHistoryModal"
        >
        </dowell-notes-history-modal>
        <div>
            <table class="table table-hover table-striped table-responsive table-bordered table-condensed">
                <tbody>
                <tr>
                    <td class="col-title">Customer Requested Date
                    </td>
                    <td class="customer-request-date" colspan="4">
                        <div>
                            <span class="block">
                                <Date-picker :value="bookingInfo.customerDate"
                                             format="dd/MM/yyyy" type="date"
                                             disabled readonly
                                             placeholder="Please select a date..."
                                             style="width: 200px">
                                </Date-picker>
                            </span>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="col-title">System Generated Date
                    </td>
                    <td colspan="4">
                        <span class="block">
                            <Date-picker :value="bookingInfo.systemDate"
                                         format="dd/MM/yyyy" type="date"
                                         disabled
                                         readonly
                                         style="width: 200px">
                            </Date-picker>
                        </span>
                    </td>
                </tr>
                <tr>
                    <td>Agreed Delivery Date
                    </td>
                    <td class="agreed-delivery-date" colspan="4">
                        <div>
                            <span class="block">
                                <Date-picker :value="bookingInfo.agreedDate"
                                             format="dd/MM/yyyy" type="date"
                                             disabled readonly
                                             placeholder="Please select a date..."
                                             style="width: 200px"></Date-picker>
                             </span>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        Order Expedite?
                    </td>
                    <td >
                        <button type="button" class="btn btn-success btn-sm"
                                @click="onOpenExpediteEnquiryModal('EXPEDITE_FEEDBACK')">
                            <i class="glyphicon glyphicon-envelope"></i> Send Feedback
                        </button>
                        <button type="button" class="btn btn-warning btn-sm"
                                @click="onOpenExpediteEnquiryHistoryModal('EXPEDITE_FEEDBACK')"
                        ><i class="glyphicon glyphicon-list-alt"></i> Feedback History
                        </button>
                    </td>
                    <td>
                        <i-switch size="large" v-model="bookingInfo.isEarlierPossible">
                            <span slot="open">YES</span>
                            <span slot="close">NO</span>
                        </i-switch>
                    </td>
                    <td colspan="2">
                        <div>
                            <span class="block">
                                <Date-picker :value="expediteDate"
                                             format="dd/MM/yyyy" type="date"
                                             :disabled="!bookingInfo.isEarlierPossible"
                                             :readonly="!bookingInfo.isEarlierPossible"
                                             placeholder="Please select a date..."
                                             @on-change="onChangeExpediteDate"
                                             style="width: 200px">
                                </Date-picker>
                            </span>
                            <span class="block">
                                <button type="button" class="btn btn-danger btn-sm"
                                        :disabled="!bookingInfo.isEarlierPossible"
                                        @click="onSaveExpediteDate">
                                    <i class="glyphicon glyphicon-save"></i> Book
                                </button>
                            </span>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="col-title">Dowell Notes
                    </td>
                    <td colspan="4">
                        <button type="button" class="btn btn-success btn-sm"
                                @click="onOpenDowellNotesModal"
                        ><i class="glyphicon glyphicon-comment"></i> New Notes
                        </button>
                        <button type="button" class="btn btn-warning btn-sm"
                                @click="onOpenDowellNotesHistoryModal"
                        ><i class="glyphicon glyphicon-list-alt"></i> Notes History
                        </button>
                        <br><br>
                        <a><strong>{{ updatedInfo }}</strong></a>
                        <br>
                        <div class="notes" v-html="dowellNotes"></div>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
    import ExpediteEnquiryModal from '../../Booking/ExpediteEnquiry/ExpediteEnquiryModal.vue'
    import ExpediteHistoryModal from '../../Booking/ExpediteEnquiry/ExpediteHistoryModal.vue'
    import DowellNotesPreparationModal from '../../Booking/DowellNotes/DowellNotesPreparationModal.vue'
    import DowellNotesHistoryModal from '../../Booking/DowellNotes/DowellNotesHistoryModal.vue'

    export default {
        props: {
            selectedOrder: {
                type: Object,
                default: null,
            }
        },
        data () {
            return {
                customerDate: '',
                systemDate: '',
                agreedDate: '',
                expediteDate: '',
                isEarlierPossible: false,
                dowellNotes: '',
                updatedInfo: '',
                isShowExpediteEnquiry: false,
                isShowExpediteEnquiryHistory: false,
                isShowDowellNotesHistory: false,
                isShowDowellNotes: false,
                expediteType: '',
                bookingInfo: {
                    customerDate: '',
                    systemDate: '',
                    agreedDate: '',
                    expediteDate: '',
                    isEarlierPossible: false,
                },
            }
        },
        created() {
            console.log('ExpediteEnquiryListTable Component created.')
            // get the latest customer notes
            this.getLatestNotesRequest();
            // get booking confirmation
            this.getBookingInformation();
        },
        components: {
            'expedite-enquiry-modal': ExpediteEnquiryModal,
            'expedite-enquiry-history-modal': ExpediteHistoryModal,
            'dowell-notes-preparation-modal': DowellNotesPreparationModal,
            'dowell-notes-history-modal': DowellNotesHistoryModal,
        },
        mounted() {
            console.log('ExpediteEnquiryListTable Component mounted.')
        },
        methods: {
            getLatestNotesRequest() {
                let payload = {
                    quoteId: (this.selectedOrder? this.selectedOrder.quote_id: ''),
                    location: (this.selectedOrder? this.selectedOrder.QUOTE_NUM_PREF: ''),
                    orderId: (this.selectedOrder? this.selectedOrder.UDF1: ''),
                    type: 'DOWELL_NOTES',
                };
                this.$store.dispatch('getLatestNotesRequest', payload)
                    .then((response) => {
                        console.log('getLatestNotesRequest response=', response);
                        let notes = response.data;
                        if (!this.isEmpty(notes)) {
                            this.updatedInfo = 'Last update by '+ notes.created_by.name
                                + ' on ' + notes.updated_at;
                            this.dowellNotes = notes.comments;
                        }
                        else
                        {
                            this.updatedInfo = '';
                            this.dowellNotes = '';
                        }
                    })
                    .catch((error) => {
                        console.log('getLatestNotesRequest error=', error);
                    });
            },
            getBookingInformation() {
                let payload = {
                    orderId: (this.selectedOrder? this.selectedOrder.UDF1: ''),
                    quoteId: (this.selectedOrder? this.selectedOrder.quote_id: ''),
                    location: (this.selectedOrder? this.selectedOrder.QUOTE_NUM_PREF: ''),
                };
                this.$store.dispatch('getBookingInformation', payload)
                    .then((response) => {
                        console.log('getBookingInformation response=', response);
                        let bookingInformation = response.body;
                        this.bookingInfo = {
                            customerDate : this.getFormatDate(this.formatDate(bookingInformation.requested_date, 'DD/MM/YYYY')),
                            systemDate : this.getFormatDate(this.formatDate(bookingInformation.sys_generated_date,'DD/MM/YYYY')),
                            agreedDate : this.getFormatDate(this.formatDate(bookingInformation.agreed_date,'DD/MM/YYYY')),
                            expediteDate : this.getFormatDate(this.formatDate(bookingInformation.expedite_date,'DD/MM/YYYY')),
                            isEarlierPossible : !!parseInt(bookingInformation.can_expedite),
                        };
                        this.customerDate = this.formatDate(bookingInformation.requested_date, 'DD/MM/YYYY');
                        this.systemDate = this.formatDate(bookingInformation.sys_generated_date,'DD/MM/YYYY');
                        this.agreedDate = this.formatDate(bookingInformation.agreed_date,'DD/MM/YYYY');
                        this.expediteDate = this.getFormatDate(this.formatDate(bookingInformation.expedite_date,'DD/MM/YYYY'));
                    })
                    .catch((error) => {
                        console.log('getBookingInformation error=', error);
                    });
            },
            onOpenExpediteEnquiryModal(value) {
                console.log('onOpenExpediteEnquiryModal value=', value);
                this.expediteType = value;
                this.isShowExpediteEnquiry = true;
            },
            onOpenExpediteEnquiryHistoryModal(value) {
                console.log('onOpenExpediteEnquiryHistoryModal value=', value);
                this.expediteType = value;
                this.isShowExpediteEnquiryHistory = true;
            },
            onOpenDowellNotesModal() {
                console.log('onOpenDowellNotesModal');
                this.isShowDowellNotes = true;
            },
            onOpenDowellNotesHistoryModal() {
                console.log('onOpenDowellNotesHistoryModal');
                this.isShowDowellNotesHistory = true;
            },
            onCloseExpediteEnquiryModal() {
                console.log('onCloseExpediteEnquiryModal');
                this.isShowExpediteEnquiry = false;
            },
            onCloseExpediteEnquiryHistoryModal() {
                console.log('onCloseExpediteEnquiryHistoryModal');
                this.isShowExpediteEnquiryHistory = false;
            },
            onCloseDowellNotesHistoryModal() {
                console.log('onCloseDowellNotesHistoryModal');
                this.isShowDowellNotesHistory = false;
            },
            onCloseDowellNotesModal() {
                console.log('onCloseDowellNotesModal');
                this.isShowDowellNotes = false;
            },
            onChangeExpediteDate(value) {
                console.log('onChangeCustomerDate value=', value);
                this.bookingInfo.expediteDate = value;
            },
            onSaveExpediteDate() {
                console.log('onSaveAgreedRequestedDate');
                if (this.isEmpty(this.bookingInfo.expediteDate))
                {
                    this.$store.dispatch('showErrorNotification', 'Please input the expedited delivery date!');
                    return;
                }
                let payload = {
                    quoteId: (this.selectedOrder? this.selectedOrder.quote_id: ''),
                    location: (this.selectedOrder? this.selectedOrder.QUOTE_NUM_PREF: ''),
                    orderId: (this.selectedOrder? this.selectedOrder.UDF1: ''),
                    type: 'EXPEDITE',
                    deliveryDate: this.bookingInfo.expediteDate,
                };
                this.$store.dispatch('updateDeliveryDateRequest', payload)
                    .then((response) => {
                        console.log('updateDeliveryDateRequest response=', response);
                        this.getBookingInformation();
                    })
                    .catch((error) => {
                        console.log('updateDeliveryDateRequest error=', error);
                    });
            },
            onRefreshNotes() {
                this.getLatestNotesRequest();
            },
        },
        directives: {

        },
        filters: {

        },
        mixins: {

        }
    }
</script>

<style scoped>
    .block {
        display: inline-block;
    }
    .notes {
        height: 200px;
        overflow-y: scroll;
    }
</style>
