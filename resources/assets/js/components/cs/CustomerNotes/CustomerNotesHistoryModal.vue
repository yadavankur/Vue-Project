<template>
    <div>
        <div>
            <customer-notes-history-modal :value="isShowModal" @cancel="onClickClose" effect="fade" :backdrop="true" :large="true">
                <div slot="modal-header" class="modal-header">
                    <h4 class="modal-title"> CustomerNotes History </h4>
                </div>
                <div slot="modal-body" class="modal-body">
                    <customer-notes-history-body v-if="isShowModal"></customer-notes-history-body>
                </div>
                <div slot="modal-footer" class="modal-footer">
                    <button type="button" class="btn btn-success" @click="onClickClose">Close</button>
                </div>
            </customer-notes-history-modal>
        </div>
    </div>
</template>

<script>
    import modal from 'vue-strap/src/Modal'
    import CustomerNotesHistoryBody from './CustomerNotesHistoryBody.vue'
    export default 
    {   props: {  isShowCustomerNotesHistory: false,   },
        data () { return { isVisible: false,   }  },
        computed: {  isShowModal() {  this.isVisible = this.isShowCustomerNotesHistory;
                                      return this.isVisible;
                                    }
                },
        created() { console.log('CustomerNotesHistoryModal Component created.')  },
        components: { 'customer-notes-history-body': CustomerNotesHistoryBody,
                      'customer-notes-history-modal': modal,
                    },
        mounted() { console.log('CustomerNotesHistoryModal Component mounted.') },
        methods:
             { onClickClose() {  console.log('onClickClose');
                                this.$emit('onCloseCustomerNotesHistoryModal');
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
        margin-bottom: 15px;
    }
</style>


