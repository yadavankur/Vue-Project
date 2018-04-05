<template>
    <div>
        <div>
            <dowell-notes-history-modal :value="isShowModal" @cancel="onClickClose" effect="fade" :backdrop="true" :large="true">
                <div slot="modal-header" class="modal-header">
                    <h4 class="modal-title"> Dowell Notes History </h4>
                </div>
                <div slot="modal-body" class="modal-body">
                    <dowell-notes-history-body v-if="isShowModal"
                                               :quoteId="quoteId"
                                               :location="location"
                                               :orderId="orderId"></dowell-notes-history-body>
                </div>
                <div slot="modal-footer" class="modal-footer">
                    <button type="button" class="btn btn-success" @click="onClickClose">Close</button>
                </div>
            </dowell-notes-history-modal>
        </div>
    </div>
</template>

<script>
    import modal from 'vue-strap/src/Modal'
    import DowellNotesHistoryBody from './DowellNotesHistoryBody.vue'
    export default {
        props: {
            isShowDowellNotesHistory: false,
            orderId: '',
            quoteId: '',
            location: '',
        },
        data () {
            return {
                isVisible: false,
            }
        },
        computed: {
            isShowModal() {
                this.isVisible = this.isShowDowellNotesHistory;
                return this.isVisible;
            }
        },
        created() {
            console.log('DowellNotesHistoryModal Component created.')
        },
        components: {
            'dowell-notes-history-body': DowellNotesHistoryBody,
            'dowell-notes-history-modal': modal,
        },
        mounted() {
            console.log('DowellNotesHistoryModal Component mounted.')
        },
        methods:
        {
            onClickClose() {
                console.log('onClickClose');
                this.$emit('onCloseDowellNotesHistoryModal');
            }
        }
    }
</script>

<style scoped>
    .modal-body {
        margin-bottom: 15px;
    }
</style>


