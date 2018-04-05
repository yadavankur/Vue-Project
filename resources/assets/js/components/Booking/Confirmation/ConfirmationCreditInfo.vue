<template>
    <div id="confirmation-credit-info-root">
        <sms-modal
                :isShowTextMsg="isShowSMSModal"
                :order="order.order"
                :smsType="smsType"
                @onCloseTextMessageModal="onCloseTextMessageModal"
        >
        </sms-modal>
        <ibox title="Credit Information" :closeable="false">
            <div slot="ibox-content" class="ibox-content">
                <div class="row-content">
                    <table class="table table-hover table-striped table-responsive table-bordered table-condensed">
                        <tbody>
                        <tr>
                            <td>Credit Status
                            </td>
                            <td class="center">
                                <div v-if="order && order.customer && order.customer.dbp_cust && order.customer.dbp_cust.CSHLDF != 'N'">
                                    <span class="label label-danger">ACCOUNT HOLDING</span>
                                    <span>Please call supervisor for further information!</span>
                                </div>
                                <div v-else>
                                    <span class="label label-info">NORMAL</span>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>Fully Paid?
                            </td>
                            <td class="center">
                                <div v-if="order && order.fully_paid">
                                    <span class="label label-primary" title="Order Releasable">YES</span>
                                </div>
                                <div v-else>
                                    <span class="label label-danger" title="Order On Hold">NO</span>
                                    <button class="btn btn-success btn-xs" @click="sendSMS" title="Send SMS"><i class="glyphicon glyphicon-comment"></i> SMS</button>
                                </div>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </ibox>
    </div>
</template>

<script>
    import Vue from 'vue';
    import { mapState} from 'vuex'
    import IBox from '../../IBox/IBox.vue'
    import SMSModal from './TextMessage/TextMessageModal.vue'

    export default {
        computed: {
            ...mapState({
                user: state => state.authUser,
            }),
        },
        props: {
            order: null,
        },
        data () {
            return {
                isShowSMSModal: false,
                smsType: 'Credit',
            }
        },
        created() {
            console.log('ConfirmationCreditInfo vue created!');
        },
        mounted() {
            console.log('ConfirmationCreditInfo vue mounted');
        },
        components: {
            'ibox': IBox,
            'sms-modal': SMSModal,
        },
        methods: {
            onCloseTextMessageModal() {
                console.log('onCloseTextMessageModal.');
                this.isShowSMSModal = false;
            },
            sendSMS() {
                console.log('sendSMS.');
                this.isShowSMSModal = true;
            }
        }
    }
</script>

<style scoped>
</style>
