<template>
    <div>
        <div>
            <custom-modal :value="isShowChangeAddress" @cancel="onClickCancel" large effect="fade" :backdrop="false">
                <div slot="modal-header" class="modal-header">
                    <h4 class="modal-title"> Changing Address </h4>
                </div>
                <div slot="modal-body" class="modal-body">
                    <change-address-body
                            v-if="isShowChangeAddress"
                            :order="order"
                            ref="changeAddress">
                    </change-address-body>
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
    import ChangeAddressBody from './ChangeAddressBody.vue'

    export default 
    {
        props: { isShowChangeAddress: false,  order: null, },
        data () { return {   isVisible: false,  }   },
        computed: {  ...mapState({ user: state => state.authUser, }),   },
        created() { console.log('/Booking/booking/Changeadd/ChangeAddressModal.vue -- created.')   },
        components: {'change-address-body': ChangeAddressBody, 'custom-modal': modal,  },
        mounted() { console.log('ChangeAddressModal Component mounted.') },
        methods: {
            onClickSave() { console.log('/Booking/booking/Changeadd/ChangeAddressModal.vue--onClickSave'); this.changeAddress();  },
            onClickCancel() {  console.log('/Booking/booking/Changeadd/ChangeAddressModal.vue--onClickCancel'); this.$emit('onCloseChangeAddressModal');   },
            changeAddress() {
                let formData = this.$refs.changeAddress.formData;
                formData.quoteId = this.order? this.order.QUOTE_ID: '';
                formData.orderId = this.order? this.order.UDF1: '';
                formData.quoteVers = this.order? this.order.QUOTE_VERS: '';
                formData.delAddrId = this.order? this.order.DEL_ADDR_ID: '';
                formData.location = this.order? this.order.QUOTE_NUM_PREF: '';
                console.log('/Booking/booking/Changeadd/ChangeAddressModal.vue--changeAddress formData=', formData);
                this.$store.dispatch('changeAddressRequest', formData)
                    .then((response) => {
                        console.log('/Booking/booking/Changeadd/ChangeAddressModal.vue--changeAddressRequest response=', response);
                        // and refresh booking page
                        let payload = 'refresh';
                        this.$emit('onCloseChangeAddressModal', payload);
                    })
                    .catch((error) => {
                        console.log('/Booking/booking/Changeadd/ChangeAddressModal.vue--changeAddressRequest error=', error);
                    });
            }
        }
    }
</script>

<style scoped>
</style>