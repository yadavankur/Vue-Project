<template>
    <div>
        <div>
            <email-history-modal :value="isShowModal" @cancel="onClickOk" effect="fade" :backdrop="true" :large="true">
                <div slot="modal-header" class="modal-header">
                    <h4 class="modal-title"> Email History </h4>
                </div>
                <div slot="modal-body" class="modal-body">
                    <email-history-body v-if="isShowModal"></email-history-body>
                </div>
                <div slot="modal-footer" class="modal-footer">
                    <button type="button" class="btn btn-success" @click="onClickOk">Close</button>
                </div>
            </email-history-modal>
        </div>
    </div>
</template>

<script>
    import modal from 'vue-strap/src/Modal'
    import EmailHistoryBody from './EmailHistoryBody.vue'
    export default {
        props: {
            isShowEmailHistory: false,
        },
        data () {
            return {
                isVisible: false,
            }
        },
        computed: {
            isShowModal() {
                this.isVisible = this.isShowEmailHistory;
                return this.isVisible;
            }
        },
        created() {
            console.log('EmailHistoryModal Component created.')
        },
        components: {
            'email-history-body': EmailHistoryBody,
            'email-history-modal': modal,
        },
        mounted() {
            console.log('EmailHistoryModal Component mounted.')
        },
        methods:
        {
            onClickOk() {
                console.log('onClickOk');
                this.$emit('onCloseEmailHistoryModal');
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


