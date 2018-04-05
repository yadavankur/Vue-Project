<template>
    <div>
        <div>
            <custom-modal :value="isShowModal" @cancel="onClickCancel" effect="fade" :backdrop="false">
                <div slot="modal-header" class="modal-header">
                    <h4 class="modal-title"> Text Message Confirmation</h4>
                </div>
                <div slot="modal-body" class="modal-body">
                    <text-message-body v-if="isShowModal"
                                            :order="order"
                                            :smsType="smsType"
                                            ref="textMessageBody"></text-message-body>
                </div>
                <div slot="modal-footer" class="modal-footer">
                    <button type="button" class="btn btn-primary" @click="onClickCancel">Cancel</button>
                    <button type="button" class="btn btn-success" @click="onClickSend">Send</button>
                </div>
            </custom-modal>
        </div>
    </div>
</template>

<script>
    import Vue from 'vue';
    import { mapGetters, mapState, mapActions} from 'vuex'
    import modal from 'vue-strap/src/Modal'
    import TextMessageBody from './TextMessageBody.vue'

    export default {
        props: {
            isShowTextMsg: false,
            order: {
                type: Object,
            },
            smsType: {
                type: String,
                default: 'Confirmation',
            },
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
                this.isVisible = this.isShowTextMsg;
                return this.isVisible;
            }
        },
        created() {
            console.log('TextMessageModal Component created.')
        },
        components: {
            'text-message-body': TextMessageBody,
            'custom-modal': modal,
        },
        mounted() {
            console.log('TextMessageModal Component mounted.')
        },
        methods:
        {
            onClickSend() {
                console.log('onClickSend');
                if (this.isEmpty(this.$refs.textMessageBody.formData.emailTo))
                {
                    this.$store.dispatch('showErrorNotification', 'Please input phone number!');
                    return;
                }
                this.$emit('onCloseTextMessageModal');
                let formData = this.$refs.textMessageBody.formData;
                console.log('onClickSend formData=', formData);
                // SEND TEXT MESSAGE REQUEST
                this.$store.dispatch('sendTextMessageRequest', formData)
                    .then((response) => {
                        console.log('sendTextMessageRequest response=', response);
                    })
                    .catch((error) => {
                        console.log('sendTextMessageRequest error=', error);
                    });
            },
            onClickCancel() {
                console.log('onClickCancel');
                this.$emit('onCloseTextMessageModal');
            }
        }
    }
</script>

<style scoped>
</style>