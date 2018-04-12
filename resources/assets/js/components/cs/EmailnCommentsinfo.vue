<template>
    <div id="root-email-booking-info">
        <div class="app-row">
            <email-preparation-modal
                    :isShowEmail="isShowEmail"
                    :order="selectedOrder? selectedOrder: null"
                    :attachments="selectedAttachments"
                    @onSelectAttachment="onOpenAttachmentModal"
                    @onCloseEmailModal="onCloseEmailModal"
            >
            </email-preparation-modal>
            <email-history-modal
                    :isShowEmailHistory="isShowEmailHistory"
                    @onCloseEmailHistoryModal="onCloseEmailHistoryModal"
            >
            </email-history-modal>
            <attachment-preparation-modal
                :isShowAttachment="isShowAttachment"
                :order="selectedOrder? selectedOrder: null"
                :selectedAttachments="selectedAttachments"
                @onCloseAttachmentModal="onCloseAttachmentModal">
            </attachment-preparation-modal>
            <customer-notes-preparation-modal
                    :isShowCustomerNotes="isShowCustomerNotes"
                    @onCloseCustomerNotesModal="onCloseCustomerNotesModal"
            >
            </customer-notes-preparation-modal>
            <customer-notes-history-modal
                    :isShowCustomerNotesHistory="isShowCustomerNotesHistory"
                    @onCloseCustomerNotesHistoryModal="onCloseCustomerNotesHistoryModal"
            >
            </customer-notes-history-modal>
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <a class="accordion-toggle" data-toggle="collapse"  href="#emailbookinginfo">  Email Confirmation </a>
                </div>
                <div id="emailbookinginfo" class="panel-collapse collapse in table-responsive">
                    <table class="table table-hover table-striped table-bordered table-condensed">
                        <tbody>
                        <tr>
                            <td class="col-title">Emails </td>
                            <td><button type="button" class="btn btn-success btn-sm"  @click="onOpenEmailModal" ><i class="glyphicon glyphicon-envelope"></i> New Email </button>
                                <button type="button" class="btn btn-warning btn-sm"  @click="onOpenEmailHistoryModal"> <i class="glyphicon glyphicon-th-list"></i> Email History </button>
                            </td>
                        </tr>
                        <tr>
                            <td class="col-title">Ticket Notes 
                                 <tr></tr><tr></tr><tr></tr> <br><br>
                                <tr> <a><strong> Main Comments: </strong></a></tr>
                                <tr> {{ selectedTicket1.comment }}</tr>
                            </td>
                            <td >
                                <button type="button" class="btn btn-success btn-sm" @click="onOpenCustomerNotesModal" ><i class="glyphicon glyphicon-comment"></i> New Notes </button>
                                <button type="button" class="btn btn-warning btn-sm" @click="onOpenCustomerNotesHistoryModal" ><i class="glyphicon glyphicon-list-alt"></i> Notes History </button>
                                <br><br>
                                <a><strong>{{ updatedInfo }}</strong></a>
                               
                                <br>
                                <div class="notes" v-html="customerNotes"></div>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import Vue from 'vue';
    import { mapGetters, mapState, mapActions} from 'vuex'
    import EmailPreparationModal from './Email/EmailPreparationModal.vue'
    import EmailHistoryModal from './Email/EmailHistoryModal.vue'
    import CustomerNotesPreparationModal from './CustomerNotes/CustomerNotesPreparationModal.vue'
    import CustomerNotesHistoryModal from './CustomerNotes/CustomerNotesHistoryModal.vue'
    import AttachmentPreparationModal from './Attachment/AttachmentPreparationModal.vue'

    export default 
    {   computed: 
        {   ...mapState({ 
                          selectedOrder: state => state.tab.selectedOrder,  

                          user: state => state.authUser,
                          selectedTicket: state => state.cstkt.selectedTicket,
                          
                          }),
               selectedTicket1() { return this.selectedTicket; }, 
        },
            props: { orderId :{ type: String, default: '', } },
        data () 
        {   return {  isShowEmail: false,   isShowEmailHistory: false,   isShowCustomerNotes: false,
                      isShowCustomerNotesHistory: false,  isShowAttachment: false,
                      customerNotes: '',  updatedInfo: '',  selectedAttachments: [],
                   }
        },
        created() 
        {   console.log('/cs/Emailncommentsinfo.vue-Component created.');
          //  this.getLatestNotesRequest();
        },
        components: 
        {
            'email-preparation-modal': EmailPreparationModal,
            'email-history-modal': EmailHistoryModal,
            'customer-notes-preparation-modal': CustomerNotesPreparationModal,
            'customer-notes-history-modal': CustomerNotesHistoryModal,
            'attachment-preparation-modal': AttachmentPreparationModal,
        },
        mounted() { console.log('/cs/Emailncommentsinfo.vue--App Component mounted.')  },
        events: {'customer-notes-refresh' () { this.getLatestNotesRequest(); }, },
        methods:
        {  onOpenAttachmentModal() 
            {   console.log('/cs/Emailncommentsinfo.vue--EmailBooking component -> onOpenAttachmentModal');
                this.isShowAttachment = true;
            },
            getLatestNotesRequest() 
            { let payload = { //orderId: this.this.selectedTicket.ticket_no,
                              ticket_no: this.selectedTicket1.ticket_no,
                             // quoteId: this.selectedOrder? this.selectedOrder.QUOTE_ID : '',
                            //  location: this.selectedOrder? this.selectedOrder.QUOTE_NUM_PREF : '',
                             // type: 'CUSTOMER_NOTES',
                            };
                this.$store.dispatch('getLatestcsComments', payload)
                    .then((response) => 
                    {   console.log('/cs/Emailncommentsinfo.vue--getLatestcsComments response=', response);
                        let notes = response.data;
                        if (!this.isEmpty(notes))
                        {   this.updatedInfo = 'Last update by '+ notes.created_by.name
                                + ' on ' + notes.updated_at;
                            this.customerNotes = notes.comments;
                        }
                        else  {   this.updatedInfo = ''; this.customerNotes = ''; }

                    })
                    .catch((error) => { console.log('/cs/Emailncommentsinfo.vue--getLatestNotesRequest error=', error); });
            },
            onOpenCustomerNotesModal() { console.log('/cs/Emailncommentsinfo.vue--onOpenCustomerNotesModal.'); this.isShowCustomerNotes = true;  },
            onOpenCustomerNotesHistoryModal() { console.log('/cs/Emailncommentsinfo.vue--onOpenCustomerNotesHistoryModal.'); this.isShowCustomerNotesHistory = true; },
            onOpenEmailModal() {  console.log('/cs/Emailncommentsinfo.vue--onOpenEmailModal.');this.selectedAttachments = []; this.isShowEmail = true;  },
                // populate a modal window // to let the operator to select email type and template
            onOpenEmailHistoryModal() 
            {   console.log('/cs/Emailncommentsinfo.vue--onOpenEmailHistoryModal.');
                this.isShowEmailHistory = true;// populate a modal window // to let the operator to select email type and template
            },
            onCloseEmailModal() { console.log('/cs/Emailncommentsinfo.vue---onCloseEmailModal.'); this.isShowEmail = false; },
            onCloseEmailHistoryModal() { console.log('/cs/Emailncommentsinfo.vue---onCloseEmailHistoryModal.'); this.isShowEmailHistory = false;  },
            onCloseCustomerNotesModal() { console.log('/cs/Emailncommentsinfo.vue---onCloseCustomerNotesModal.'); this.isShowCustomerNotes = false; },
            onCloseCustomerNotesHistoryModal() { console.log('/cs/Emailncommentsinfo.vue---onCloseCustomerNotesHistoryModal.'); this.isShowCustomerNotesHistory = false; },
            onCloseAttachmentModal(selectedAttachments) 
                {  console.log('/cs/Emailncommentsinfo.vue---onCloseAttachmentModal selectedAttachments=', selectedAttachments);
                   this.isShowAttachment = false;  this.selectedAttachments = selectedAttachments;
                }
        }
    }
</script>

<style scoped>
    .notes {
        height: 200px;
        overflow-y: scroll;
    }
    .col-title {
        width: 35%
    }
</style>
