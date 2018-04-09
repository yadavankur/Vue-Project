<template>
    <div>
        <div>
            <custom-modal :value="isShowModal" @cancel="onClickCancel" effect="fade" :backdrop="false">
                <div slot="modal-header" class="modal-header">
                    <h4 class="modal-title"> Customer Notes Confirmation </h4>
                </div>
                <div slot="modal-body" class="modal-body">
                    <customer-notes-preparation-body v-if="isShowModal" ref="customerNotes"></customer-notes-preparation-body>
                </div>
                <div slot="modal-footer" class="modal-footer">
                    <button type="button" class="btn btn-primary" @click="onClickCancel">Cancel</button>
                    <button type="button" class="btn btn-success" @click="onClickSave">Save</button>
                </div>
            </custom-modal>
        </div>
    </div>
</template>

<script>
    import Vue from 'vue';
    import { mapGetters, mapState, mapActions} from 'vuex'
    import modal from 'vue-strap/src/Modal'
    import CustomerNotesPreparationBody from './CustomerNotesPreparationBody.vue'
    export default 
        {   props: {  isShowCustomerNotes: false,  },
            data () {   return {  isVisible: false,   }     },
            computed: {  isShowModal() {   this.isVisible = this.isShowCustomerNotes;
                                            return this.isVisible;
                                        },
                        ...mapState({    user: state => state.authUser,
                                        selectedOrder: state => state.tab.selectedOrder,
                                    }),
                    },
            created() {  console.log('CustomerNotesPreparationModal Component created.')    },
            components: {  'customer-notes-preparation-body': CustomerNotesPreparationBody,
                        'custom-modal': modal,
                        },
            mounted() {  console.log('CustomerNotesPreparationModal Component mounted.')   },
            methods:
                {   onClickSave() 
                    {   console.log('onClickSave');
                        this.$emit('onCloseCustomerNotesModal');
                        // send save request
                        let formData = 
                        {   orderId: this.selectedOrder? this.selectedOrder.UDF1 : '',
                            quoteId:  this.selectedOrder? this.selectedOrder.QUOTE_ID : '',
                            location:  this.selectedOrder? this.selectedOrder.QUOTE_NUM_PREF : '',
                            notes: this.$refs.customerNotes.customerNotesContent
                        };
                        console.log('onClickSave formData=', formData);
                         // SEND EMAIL REQUEST
                        this.$store.dispatch('saveCustomerNotesRequest', formData)
                        .then((response) => {  console.log('saveCustomerNotesRequest response=', response);
                                            // refresh the customer notes
                                                this.$events.fire('customer-notes-refresh');
                                            })
                        .catch((error) => { console.log('saveCustomerNotesRequest error=', error);  });
                    },
                    onClickCancel() {  console.log('onClickCancel');
                                        this.$emit('onCloseCustomerNotesModal');
                                    }
                }//methods end
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
</style>


