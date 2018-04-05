<template>
    <div>
        <div>
            <custom-modal :value="isShowModal" @cancel="onClickCancel" effect="fade" :backdrop="false">
                <div slot="modal-header" class="modal-header">
                    <h4 class="modal-title">
                        Earliest Available Delivery Date
                    </h4>
                    <div v-if="loading" class="modal-title-spinner">
                        <app-spinner :show="loading"></app-spinner>
                    </div>
                </div>
                <div slot="modal-body" class="modal-body">
                    <system-generated-date-body
                            v-if="isShowModal"
                            :dateList="dateList"
                            @onGetDateList="onGenerateSystemDate"
                            @onSelectDeliveryDate="onSelectDeliveryDate"
                            ref="deliveryDateBody">
                    </system-generated-date-body>
                </div>
                <div slot="modal-footer" class="modal-footer">
                    <button type="button" class="btn btn-primary" @click="onClickCancel">Cancel</button>
                    <button :disabled="selectedDate == null" type="button" class="btn btn-success" @click="onClickSend">Select</button>
                </div>
            </custom-modal>
        </div>
    </div>
</template>

<script>
    import Vue from 'vue';
    import { mapGetters, mapState, mapActions} from 'vuex'
    import modal from 'vue-strap/src/Modal'
    import SystemGeneratedDateBody from './SystemGeneratedDateBody.vue'
    import AppSpinner from '../../../AppSpinner.vue'

    export default {
        props: {
            isShow: false,
            order: {
                type: Object,
                default: null,
            },
        },
        data () {
            return {
                loading: false,
                selectedDate: null,
                dateList: [],
            }
        },
        computed: {
            ...mapState({
                user: state => state.authUser,
            }),
            isShowModal() {
                return this.isShow;
            }
        },
        created() {
            console.log('SystemGeneratedDateModal Component created.')
            //this.onGenerateSystemDate();
        },
        components: {
            'system-generated-date-body': SystemGeneratedDateBody,
            'custom-modal': modal,
            'app-spinner': AppSpinner,
        },
        mounted() {
            console.log('SystemGeneratedDateModal Component mounted.')
        },
        methods:
        {
            onSelectDeliveryDate(date) {
                console.log('onSelectDeliveryDate date=', date);
                this.selectedDate = date;
            },
            onClickSend() {
                console.log('onClickSend');
                this.$emit('onCloseSystemGeneratedDateModal', this.selectedDate);
                this.dateList = [];
                this.selectedDate = null;
            },
            onClickCancel() {
                console.log('onClickCancel');
                this.selectedDate = null;
                this.dateList = [];
                this.$emit('onCloseSystemGeneratedDateModal');
            },
            onGenerateSystemDate() {
                // pass flag and offset to api
                // flag: 0 -> get earliest available date
                // flag: 1 -> get the list of all needed activities
                // flag: 2 offset  ->  get the list of available dates
                // TODO
                let payload = {
                    quoteId: (this.order? this.order.QUOTE_ID: ''),
                    location: (this.order? this.order.QUOTE_NUM_PREF: ''),
                    orderId: (this.order? this.order.UDF1: ''),
                    serviceId: 1,
                    flag: 2,
                    offset: 10,
                };
                this.loading = true;
                this.$store.dispatch('getSystemGeneratedDeliveryDate', payload)
                    .then((response) => {
                        console.log('getSystemGeneratedDeliveryDate response=', response);
                        if (this.loading)
                            this.dateList = response.body;
                        this.loading = false;
                    })
                    .catch((error) => {
                        console.log('getSystemGeneratedDeliveryDate error=', error);
                    });
            },
        }
    }
</script>

<style scoped>
    .modal-body {
        min-height: 40px;
        text-align: center;
    }
</style>
