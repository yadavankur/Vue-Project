<template>
    <div>
        <div class="CPMActivity">
            <cpm-activity-notes-history-modal :value="isShowCPMActivityNotesHistory" @cancel="onClickClose" effect="fade" :backdrop="true" :large="true">
                <div slot="modal-header" class="modal-header">
                    <h4 class="modal-title"> Activity Notes History </h4>
                </div>
                <div slot="modal-body" class="modal-body">
                    <cpm-activity-notes-history-body v-if="isShowCPMActivityNotesHistory"
                            :itemData="itemData"
                    >
                    </cpm-activity-notes-history-body>
                </div>
                <div slot="modal-footer" class="modal-footer">
                    <button type="button" class="btn btn-success" @click="onClickClose">Close</button>
                </div>
            </cpm-activity-notes-history-modal>
        </div>
    </div>
</template>

<script>
    import modal from 'vue-strap/src/Modal'
    import CPMActivityNotesHistoryBody from './CPMActivityNotesHistoryBody.vue'
    export default {
        props: {
            isShowCPMActivityNotesHistory: false,
            itemData: null,
        },
        data () {
            return {
            }
        },
        computed: {
        },
        created() {
            console.log('CPMActivityNotesHistoryModal Component created.')
        },
        components: {
            'cpm-activity-notes-history-body': CPMActivityNotesHistoryBody,
            'cpm-activity-notes-history-modal': modal,
        },
        mounted() {
            console.log('CPMActivityNotesHistoryModal Component mounted.')
        },
        methods:
        {
            onClickClose() {
                console.log('onClickClose');
                this.$emit('onCloseCPMActivityNotesHistoryModal');
            }
        }
    }
</script>

<style scoped>
    .modal-body {
        margin-bottom: 15px;
    }
    .CPMActivity {
        position: fixed;
        z-index: 1055;
    }
</style>