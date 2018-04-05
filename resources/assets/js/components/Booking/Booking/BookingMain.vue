<template>
    <div id="root-booking-main-info">
        <div class="app-row">
            <system-generated-date-modal
                    :isShow="isShowSystemGeneratedDate"
                    :order="selectedOrder? selectedOrder : null"
                    @onCloseSystemGeneratedDateModal="onCloseSystemGeneratedDateModal"
            >
            </system-generated-date-modal>
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
                    :quoteId="selectedOrder? selectedOrder.QUOTE_ID: ''"
                    :location="selectedOrder? selectedOrder.QUOTE_NUM_PREF: ''"
                    :isShowExpediteEnquiryHistory="isShowExpediteEnquiryHistory"
                    @onCloseExpediteEnquiryHistoryModal="onCloseExpediteEnquiryHistoryModal"
            >
            </expedite-enquiry-history-modal>
            <dowell-notes-preparation-modal
                    :isShowDowellNotes="isShowDowellNotes"
                    :orderId="selectedOrder? selectedOrder.UDF1: ''"
                    :quoteId="selectedOrder? selectedOrder.QUOTE_ID: ''"
                    :location="selectedOrder? selectedOrder.QUOTE_NUM_PREF: ''"
                    @onCloseDowellNotesModal="onCloseDowellNotesModal"
                    @onRefreshNotes="onRefreshNotes"
            >
            </dowell-notes-preparation-modal>
            <dowell-notes-history-modal
                    :isShowDowellNotesHistory="isShowDowellNotesHistory"
                    :orderId="selectedOrder? selectedOrder.UDF1: ''"
                    :quoteId="selectedOrder? selectedOrder.QUOTE_ID: ''"
                    :location="selectedOrder? selectedOrder.QUOTE_NUM_PREF: ''"
                    @onCloseDowellNotesHistoryModal="onCloseDowellNotesHistoryModal"
            >
            </dowell-notes-history-modal>
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <a class="accordion-toggle" data-toggle="collapse"  href="#bookingmain">
                        Booking Confirmation
                    </a>
                </div>
                <div id="bookingmain" class="panel-collapse collapse in table-responsive">
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
                                                         placeholder="Please select a date..."
                                                         @on-change="onChangeCustomerDate"
                                                         style="width: 200px">
                                            </Date-picker>
                                        </span>
                                        <span class="block">
                                            <button type="button" class="btn btn-primary btn-sm"
                                                    @click="onSaveCustomerRequestedDate">
                                                <i class="glyphicon glyphicon-save"></i> Update
                                            </button>
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
                                    <span class="block">
                                        <button type="button" class="btn btn-success btn-sm"
                                                @click="onOpenSystemGeneratedDateModal">Get Available Dates
                                        </button>
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
                                                         @on-change="onChangeAgreedDate"
                                                         placeholder="Please select a date..."
                                                         style="width: 200px"></Date-picker>
                                         </span>
                                        <span class="block">
                                            <button type="button" class="btn btn-danger btn-sm"
                                                    @click="onSaveAgreedRequestedDate">
                                                <i class="glyphicon glyphicon-save"></i> Book
                                            </button>
                                        </span>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="col-title">
                                    Request Order Expedite
                                </td>
                                <td >
                                    <button type="button" class="btn btn-success btn-sm"
                                            @click="onOpenExpediteEnquiryModal('EXPEDITE')">
                                        <i class="glyphicon glyphicon-envelope"></i> Enquire
                                    </button>
                                    <button type="button" class="btn btn-warning btn-sm"
                                            @click="onOpenExpediteEnquiryHistoryModal('EXPEDITE')"
                                    ><i class="glyphicon glyphicon-list-alt"></i> Enquiry History
                                    </button>
                                </td>
                                <td>Earlier Delivery?
                                </td>
                                <td>
                                    <i-switch size="large" v-model="bookingInfo.isEarlierPossible" :disabled="true">
                                        <span slot="open">YES</span>
                                        <span slot="close">NO</span>
                                    </i-switch>
                                </td>
                                <td class="expedite-date">
                                    {{ formatDate(bookingInfo.expediteDate, 'DD/MM/YYYY', 'DD/MM/YYYY') }}
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
                <div id='associated-job-list' class="table-responsive">
                    <associated-jobs-list @onSearch="onSearch"></associated-jobs-list>
                </div>
                <div v-if="bookingInfo.agreedDate" id='associated-task-list' class="associated-task table-responsive">
                    <associated-tasks-list :key="selectedOrder.UDF1"
                                           :selectedOrder="selectedOrder"
                    ></associated-tasks-list>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import Vue from 'vue';
    import { mapGetters, mapState, mapActions} from 'vuex'
    import AssociatedJobs from './AsscociatedJobs/AssociatedJobs.vue'
    import AssociatedTasks from './AssociatedTasks/AssociatedTasks.vue'
    import ExpediteEnquiryModal from './ExpediteEnquiry/ExpediteEnquiryModal.vue'
    import ExpediteHistoryModal from './ExpediteEnquiry/ExpediteHistoryModal.vue'
    import DowellNotesPreparationModal from './DowellNotes/DowellNotesPreparationModal.vue'
    import DowellNotesHistoryModal from './DowellNotes/DowellNotesHistoryModal.vue'
    import SystemGeneratedDateModal from './SystemGeneratedDates/SystemGeneratedDateModal.vue'
//    import AssociatedTasks from '../ExpediteConfirmation/OrderActivityList.vue'

    export default 
    {   computed: {  ...mapState({   user: state => state.authUser,
                                    selectedOrder: state => state.tab.selectedOrder,
                                    details: state => state.orderDetails.details,
                                }),
                  },
        props: {     orderId :{  type: String, default: '', }  },
        data () { return {  customerDate: '', systemDate: '', agreedDate: '',  expediteDate: '',
                            isEarlierPossible: false,
                            dowellNotes: '',
                            updatedInfo: '',
                            isShowExpediteEnquiry: false,
                            isShowExpediteEnquiryHistory: false,
                            isShowDowellNotesHistory: false,
                            isShowDowellNotes: false,
                            isShowSystemGeneratedDate: false,
                            bookingInfo: {  customerDate: '', systemDate: '', agreedDate: '', expediteDate: '', isEarlierPossible: false, },
                            expediteEnquiry: {  expediteDate: '', isEarlierPossible: false, },
                            expediteType: '',
                        }
                },
        created() { console.log('/components/booking/booking/BookingMain.vue-BookingMain Component created.')
                    this.getLatestNotesRequest(); // get the latest customer notes
                    this.getBookingInformation();// get booking confirmation
                 },
        components: 
        {   'associated-jobs-list': AssociatedJobs,
            'associated-tasks-list': AssociatedTasks,
            'expedite-enquiry-modal': ExpediteEnquiryModal,
            'expedite-enquiry-history-modal': ExpediteHistoryModal,
            'dowell-notes-preparation-modal': DowellNotesPreparationModal,
            'dowell-notes-history-modal': DowellNotesHistoryModal,
            'system-generated-date-modal': SystemGeneratedDateModal,
        },
        mounted() { console.log('/components/booking/booking/BookingMain.vue-BookingMain Component mounted.')   },
        methods:
        {
            onSearch(payload) 
            {   console.log('/components/booking/booking/BookingMain.vue-BookingMain -> onSearch locationId=', payload.location);
                console.log('/components/booking/booking/BookingMain.vue-BookingMain -> onSearch orderId=', payload.orderId);
                this.$emit('onSearch', payload);
            },
            onRefreshNotes() { this.getLatestNotesRequest(); },
            getBookingInformation() 
            {   let payload = 
                    {    orderId: this.orderId,
                         quoteId: (this.selectedOrder? this.selectedOrder.QUOTE_ID: ''),
                         location: (this.selectedOrder? this.selectedOrder.QUOTE_NUM_PREF: ''),
                    };
                this.$store.dispatch('getBookingInformation', payload)
                    .then((response) => {
                        console.log('/components/booking/booking/BookingMain.vue-getBookingInformation response=', response);
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
                        this.expediteDate = this.formatDate(bookingInformation.expedite_date,'DD/MM/YYYY');
                    })
                    .catch((error) => {
                        console.log('/js/components/booking/booking/BookingMain.vue-getBookingInformation error=', error);
                    });
            },
            getLatestNotesRequest() 
            { let payload = 
                {   quoteId: (this.selectedOrder? this.selectedOrder.QUOTE_ID: ''),
                    location: (this.selectedOrder? this.selectedOrder.QUOTE_NUM_PREF: ''),
                    orderId: this.orderId,
                    type: 'DOWELL_NOTES',
                };
                this.$store.dispatch('getLatestNotesRequest', payload)
                    .then((response) => 
                    {   console.log('/components/booking/booking/BookingMain.vue-getLatestNotesRequest response=', response);
                        let notes = response.data;
                        if (!this.isEmpty(notes)) {   this.updatedInfo = 'Last update by '+ notes.created_by.name
                                                        + ' on ' + notes.updated_at;
                                                      this.dowellNotes = notes.comments;
                                                  } else  {   this.updatedInfo = '';   this.dowellNotes = ''; }
                    })
                    .catch((error) => { console.log('/booking/booking/BookingMain.vue-getLatestNotesRequest error=', error); });
            },
            onOpenSystemGeneratedDateModal() {console.log('/booking/BookingMain.vue-onOpenSystemGeneratedDateModal'); this.isShowSystemGeneratedDate = true;},
            onCloseSystemGeneratedDateModal(selectedDate) 
            {   console.log('/booking/BookingMain.vue-onCloseSystemGeneratedDateModal selectedDate=', selectedDate);
                this.isShowSystemGeneratedDate = false;
                if (selectedDate)
                { this.bookingInfo.systemDate = this.getFormatDate(this.formatDate(selectedDate,'DD/MM/YYYY')) }
            },
            onOpenExpediteEnquiryModal(value) { console.log('/booking/BookingMain.vue-onOpenExpediteEnquiryModal value=', value);  this.expediteType = value;  this.isShowExpediteEnquiry = true;  },
            onOpenExpediteEnquiryHistoryModal(value) {console.log('/booking/BookingMain.vue-onOpenExpediteEnquiryHistoryModal value=', value); this.expediteType = value; this.isShowExpediteEnquiryHistory = true; },
            onOpenDowellNotesModal() { console.log('/booking/booking/BookingMain.vue-onOpenDowellNotesModal');  this.isShowDowellNotes = true; },
            onOpenDowellNotesHistoryModal() { console.log('/booking/booking/BookingMain.vue-onOpenDowellNotesHistoryModal');this.isShowDowellNotesHistory = true; },
            onCloseExpediteEnquiryModal() {console.log('/booking/booking/BookingMain.vue-onCloseExpediteEnquiryModal'); this.isShowExpediteEnquiry = false; },
            onCloseExpediteEnquiryHistoryModal() { console.log('/booking/booking/BookingMain.vue-onCloseExpediteEnquiryHistoryModal'); this.isShowExpediteEnquiryHistory = false; },
            onCloseDowellNotesHistoryModal() { console.log('/booking/booking/BookingMain.vue-onCloseDowellNotesHistoryModal'); this.isShowDowellNotesHistory = false; },
            onCloseDowellNotesModal() {  console.log('/booking/booking/BookingMain.vue-onCloseDowellNotesModal');  this.isShowDowellNotes = false; },
            onSaveCustomerRequestedDate() 
            {   console.log('/components/booking/booking/BookingMain.vue-onSaveCustomerRequestedDate');
                if (this.isEmpty(this.customerDate))
                { this.$store.dispatch('showErrorNotification', 'Please input the customer requested delivery date!');
                    return;
                }
                let payload = 
                {   quoteId: (this.selectedOrder? this.selectedOrder.QUOTE_ID: ''),
                    location: (this.selectedOrder? this.selectedOrder.QUOTE_NUM_PREF: ''),
                    orderId: this.orderId,
                    type: 'CUSTOMER',
                    deliveryDate: this.customerDate,
                };
                this.$store.dispatch('updateDeliveryDateRequest', payload)
                    .then((response) => 
                    {   console.log('/components/booking/booking/BookingMain.vue-updateDeliveryDateRequest response=', response);
                        this.getBookingInformation();
                    })
                    .catch((error) => { console.log('/components/booking/booking/BookingMain.vue-updateDeliveryDateRequest error=', error);
                    });
            },
            onSaveAgreedRequestedDate() 
            {   console.log('/booking/booking/BookingMain.vue-onSaveAgreedRequestedDate');
                console.log('/booking/booking/BookingMain.vue-onSaveAgreedRequestedDate agreedDate=', this.agreedDate);
                console.log('/booking/booking/BookingMain.vue-onSaveAgreedRequestedDate systemDate=', this.bookingInfo.systemDate);
                if (this.isEmpty(this.agreedDate))
                { this.$store.dispatch('showErrorNotification', 'Please input the agreed delivery date!');  return;  }
                let agreedDate = this.getFormatDate(this.agreedDate, 'DD/MM/YYYY');
                console.log('/booking/booking/BookingMain.vue-onSaveAgreedRequestedDate agreedDate=', agreedDate);
                if (this.isAfter(this.bookingInfo.systemDate, agreedDate))
                {  this.$store.dispatch('showErrorNotification', 'Sorry, you cannot input a date earlier than system generated date!');
                    return;
                }
                let payload = 
                {   quoteId: (this.selectedOrder? this.selectedOrder.QUOTE_ID: ''),
                    location: (this.selectedOrder? this.selectedOrder.QUOTE_NUM_PREF: ''),
                    orderId: this.orderId,
                    type: 'AGREED',
                    deliveryDate: this.agreedDate,
                };
                this.$store.dispatch('updateDeliveryDateRequest', payload)
                    .then((response) => 
                    {   console.log('/booking/booking/BookingMain.vue-updateDeliveryDateRequest response=', response);
                        this.getBookingInformation();
                    })
                    .catch((error) => { console.log('/booking/booking/BookingMain.vue-updateDeliveryDateRequest error=', error);  });
            },  //onSaveAgreedRequestedDate() 
            onChangeAgreedDate(value) {console.log('/booking/booking/BookingMain.vue-onChangeAgreedDate value=', value); this.agreedDate = value; },
            onChangeCustomerDate(value) { console.log('/booking/booking/BookingMain.vue-onChangeCustomerDate value=', value); this.customerDate = value;},
            onChangeExpediteDate(value) { console.log('/booking/booking/BookingMain.vue-onChangeCustomerDate value=', value); this.expediteEnquiry.expediteDate = value; },
            onSaveExpediteDate() 
               {  console.log('/components/booking/booking/BookingMain.vue-onSaveAgreedRequestedDate');
                     if (this.isEmpty(this.expediteEnquiry.expediteDate))
                      { this.$store.dispatch('showErrorNotification', 'Please input the expedited delivery date!'); return;  }
                     let payload = 
                        { quoteId: (this.selectedOrder? this.selectedOrder.QUOTE_ID: ''),
                          location: (this.selectedOrder? this.selectedOrder.QUOTE_NUM_PREF: ''),
                          orderId: this.orderId, type: 'EXPEDITE',
                          deliveryDate: this.expediteEnquiry.expediteDate,
                        };
                    this.$store.dispatch('updateDeliveryDateRequest', payload)
                    .then((response) => 
                    {   console.log('/booking/booking/BookingMain.vue-updateDeliveryDateRequest response=', response);
                        this.getBookingInformation();
                    })
                    .catch((error) => { console.log('/booking/booking/BookingMain.vue-updateDeliveryDateRequest error=', error);   });
               }//onSaveExpediteDate() 
        }
    }
</script>

<style scoped>
    .associated-task { margin: 5px; }
    .system-date { background-color: orange;  }
    .expedite-date {  background-color: yellow;  width: 15% }
    .notes {  height: 200px; overflow-y: scroll; }
    .col-title { width: 35%  }
    .block { display: inline-block;  }
</style>
