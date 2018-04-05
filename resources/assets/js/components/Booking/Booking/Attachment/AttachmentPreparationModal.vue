<template>
    <div>
        <div>
            <custom-modal :value="isShowModal" @cancel="onClickCancel" effect="fade" :backdrop="false">
                <div slot="modal-header" class="modal-header">
                    <h4 class="modal-title"> Attachment Selection </h4>
                </div>
                <div slot="modal-body" class="modal-body">
                    <attachment-preparation-body v-if="isShowModal"
                                            :order="order"
                                            :selectedAttachments="selectedAttachments"
                                            ref="attachmentBody">
                    </attachment-preparation-body>
                </div>
                <div slot="modal-footer" class="modal-footer">
                    <button type="button" class="btn btn-primary" @click="onClickCancel">Cancel</button>
                    <button type="button" class="btn btn-success" @click="onClickOk">OK</button>
                </div>
            </custom-modal>
        </div>
    </div>
</template>

<script>
    import Vue from 'vue';
    import { mapGetters, mapState, mapActions} from 'vuex'
    import modal from 'vue-strap/src/Modal'
    import AttachmentPreparationBody from './AttachmentPreparationBody.vue'

    export default {
        props: {
            isShowAttachment: false,
            order: {
                type: Object,
            },
            selectedAttachments: {
                type: Array,
            }
        },
        data () {
            return {
                isVisible: false,
            }
        },
        computed: {
            ...mapState({
                user: state => state.authUser,
            }),
            isShowModal() {
                this.isVisible = this.isShowAttachment;
                return this.isVisible;
            }
        },
        created() {
            console.log('AttachmentPreparationModal Component created.')
        },
        components: {
            'attachment-preparation-body': AttachmentPreparationBody,
            'custom-modal': modal,
        },
        mounted() {
            console.log('AttachmentPreparationModal Component mounted.')
        },
        methods:
        {
            onClickOk() {
                console.log('onClickOk');

                let attachments = this.$refs.attachmentBody.attachments;
                console.log('onClickOk formData=', attachments);
                this.$emit('onCloseAttachmentModal', attachments);
//                // send adding attachment request
//                this.$store.dispatch('sendAttachmentRequest', formData)
//                    .then((response) => {
//                        console.log('sendAttachmentRequest response=', response);
//                    })
//                    .catch((error) => {
//                        console.log('sendAttachmentRequest error=', error);
//                    });
            },
            onClickCancel() {
                console.log('onClickCancel');
                this.$emit('onCloseAttachmentModal', this.selectedAttachments);
            }
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
</style>


