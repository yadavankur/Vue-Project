<template>
    <div id="permission-list">
        <div class="main">
            <div class="container">
            
                <csticket-crud-modal></csticket-crud-modal>
               
                <div class="row">
                    <div class="col-md-12"> <csticket-list-search></csticket-list-search> </div>
                    <div class="col-md-12">
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <div class="permission-title"><span class="pull-left">Customer Service Tickets</span></div>
                            </div>
                            <div id="permissionlist" class="panel-body table-responsive">
                                <csticket-list-table ></csticket-list-table>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6" v-if="selectedTicket">
                         <order-info></order-info>
                         <v6items></v6items>
                         <order-history-summary></order-history-summary>
                           <bomfinish></bomfinish> <bomcomponent></bomcomponent>
                      
                    </div>


                    
                    <div class="col-md-6" v-if="selectedTicketType == 'TYPE1'">
                          <ticket-type1></ticket-type1>
                          <ticket-type1crud></ticket-type1crud>
                    </div>
                    <div class="col-md-6" v-if="selectedTicketType == 'Credit Note'">
                        <ticket-type2A></ticket-type2A>  
                        <ticket-type2Acrud></ticket-type2Acrud>
                    </div>
                    <div class="col-md-6" v-if="selectedTicketType == 'TYPE2B'">
                    <ticket-type2B></ticket-type2B>
                    </div>
                    <div class="col-md-6" v-if="selectedTicketType == 'TYPE3'">
                    <ticket-type3></ticket-type3>
                    <ticket-type3crud></ticket-type3crud>
                    </div>
                      <div class="col-md-6" v-if="selectedTicketType == 'TYPE4'">
                    <ticket-type4></ticket-type4>
                    </div>
                      <div class="col-md-6" v-if="selectedTicketType == 'TYPE5'">
                    <ticket-type5></ticket-type5>
                    </div>
                      <div class="col-md-6" v-if="selectedTicketType == 'TYPE6'">
                    <ticket-type6></ticket-type6>
                    </div>
                      <div class="col-md-6" v-if="selectedTicketType == 'TYPE7'">
                    <ticket-type7></ticket-type7>
                    </div>
                      <div class="col-md-6" v-if="selectedTicketType == 'TYPE8'">
                    <ticket-type8></ticket-type8>
                    </div>
                      <div class="col-md-6" v-if="selectedTicketType == 'TYPE9'">
                    <ticket-type9></ticket-type9>
                    </div>
                    <div class="col-md-6" v-if="selectedTicket">

                    <email-booking-info> </email-booking-info>
                    </div>

                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import Vue from 'vue'
    import { mapGetters, mapState, mapActions} from 'vuex'

    import CsTicketSearch from './CsTicketSearch.vue'
 
    import CsTicketListTable from './CsTicketListTable.vue'
    import CsTicketCrudModal from './CsTicketCrudModal.vue'
    import AttachmentInfo from './CsTicketAttachmentInfo.vue'
    import OrderInfo from './OrderInfoincs.vue'
    import OrderHistorySummary from './OrderHistorySummary.vue'
    import V6Items from './V6Items.vue'
    import bomfinish from './bomfinish.vue'
    import bomcomponent from './bomcomponents.vue'
    import CsTicketType1 from './CsTicketType1.vue'
    import CsTicketType1Crud from './CsTicketType1Crud.vue'
    import CsTicketType2ACrud from './Type2A/CsTicketType2ACrud.vue'
    import CsTicketType3Crud from './Type3/CsTicketType3Crud.vue'
    import CsTicketType2A from './CsTicketType2A.vue'
    import CsTicketType2B from './CsTicketType2B.vue'
    import CsTicketType3 from './CsTicketType3.vue'
    import CsTicketType4 from './CsTicketType4.vue'
    import CsTicketType5 from './CsTicketType5.vue'
    import CsTicketType6 from './CsTicketType6.vue'
    import CsTicketType7 from './CsTicketType7.vue'
    import CsTicketType8 from './CsTicketType8.vue'
    import CsTicketType9 from './CsTicketType9.vue'

    import EmailBookingInfo from './EmailnCommentsinfo.vue'

    export default 
    {   data () {      return {     }    },
        computed: {  ...mapGetters({ 
                                }),
                     ...mapState({ user: state => state.authUser, 
                                   selectedTicket: state => state.cstkt.selectedTicket,
                                   selectedTicketType: state => state.cstkt.selectedTicketType,
                     
                                }),
                  },
        created() {  console.log('/cs/cs1ticket -vue Component created.');  },
        components: { 'csticket-list-search': CsTicketSearch, 
                      'csticket-crud-modal': CsTicketCrudModal,
                      'csticket-list-table': CsTicketListTable,
                      'order-info': OrderInfo,
                      'order-history-summary': OrderHistorySummary, 'v6items':V6Items,'bomfinish':bomfinish,'bomcomponent':bomcomponent,
                      'email-booking-info': EmailBookingInfo,
                      'ticket-type1': CsTicketType1,'ticket-type1crud': CsTicketType1Crud,
                      'ticket-type2A': CsTicketType2A,'ticket-type2Acrud': CsTicketType2ACrud,
                        'ticket-type2B': CsTicketType2B,
                         'ticket-type3': CsTicketType3,'ticket-type3crud': CsTicketType3Crud,
                         'ticket-type4': CsTicketType4,
                         'ticket-type5': CsTicketType5,
                         'ticket-type6': CsTicketType6,
                         'ticket-type7': CsTicketType7,
                         'ticket-type8': CsTicketType8,
                         'ticket-type9': CsTicketType9,
            
                    },
        mounted() { console.log('/cs/cs1ticket - vue Component mounted.'); },
        methods: {    },
        watch: {    }
    }
</script>

<style scoped>
    .permission-title {  margin-left: 6px;  height: 30px; }
</style>