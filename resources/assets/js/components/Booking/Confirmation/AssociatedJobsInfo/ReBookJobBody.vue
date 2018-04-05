<template>
    <div>
        <div>
            <div class="rebook-job-body">
                <div class="form-group">
                    <div v-if="bookableTitle"><button type="button"
                                 :class="[isOKToBook ? 'btn btn-success' : 'btn btn-danger', 'canBook']"
                                 disabled>
                        {{ bookableTitle }}
                        </button>
                    </div>
                    <div class="rebook-row">
                        <div class="block_title">
                            <label for="currentDeliveryDate">Current Delivery Date</label>
                        </div>
                        <div class="block_content">
                            <Date-picker
                                         id="currentDeliveryDate"
                                         :value="bookingInfo.agreedDeliveryDate"
                                         format="dd/MM/yyyy" type="date"
                                         :disabled="true"
                                         readonly
                                         >
                            </Date-picker>
                        </div>
                    </div>
                    <div class="rebook-row">
                        <div class="block_title"><label for="newDeliveryDate">New Delivery Date</label></div>
                        <div class="block_content">
                            <Date-picker
                                         id="newDeliveryDate"
                                         :value="bookingInfo.newDeliveryDate"
                                         format="dd/MM/yyyy" type="date"
                                         @on-change="onChangeNewDeliveryDate"
                                         >
                            </Date-picker>
                        </div>
                        <div class="block_content" v-if="isOKToBook">
                            <button type="button" class="btn btn-danger btn-sm"
                                    :diabled="isOKToBook? false : true"
                                    @click="onUpdateNewDeliveryDateRequest">
                                <i class="glyphicon glyphicon-save"></i> Update
                            </button>
                        </div>
                    </div>
                    <div class="rebook-row-left" v-if="!isOKToBook && bookableTitle">
                        <div class="block_content">
                            <button class="btn btn-warning"
                                    @click="onClickGoToBookingScreen"
                            >GO TO <br>BOOKING SCREEN</button>
                        </div>
                        <div class="block_content">
                            <button class="btn btn-danger"
                                    @click="onClickRequestOrderExpedite"
                            >REQUEST ORDER<br> EXPEDITE</button>
                        </div>
                    </div>
                    <div class="rebook-row">
                        <div><label for="rebookingNotesContent">Notes</label></div>
                        <rebook-notes-editor v-model="reBookingNotesContent"
                                             ref="rebookingNotesQuillEditor"
                                             :options="editorOption">
                        </rebook-notes-editor>
                    </div>
                    <!--<button type="button" class="btn btn-primary" @click="onClickSave">Save Notes</button>-->
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import { mapGetters, mapState, mapActions} from 'vuex'
    import { quillEditor } from 'vue-quill-editor'

    export default {
        computed: {
            ...mapState({
                user: state => state.authUser,
                selectedOrder: state => state.confirmation.selectedOrder,
            }),
        },
        data () {
            return {
                editorOption: {
                    //readOnly: true,
                    modules: {
                        toolbar: [
                            ['bold', 'italic', 'underline', 'strike'],
                            [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                            [{ 'indent': '-1'}, { 'indent': '+1' }],
                            [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
                            [{ 'color': [] }, { 'background': [] }],
                            [{ 'font': [] }],
                            [{ 'align': [] }],
                            ['link', 'image']
                        ],
                    },
                    placeholder: 'Please input notes...',
                    theme: 'snow'
                },
                reBookingNotesContent: '',
                newDeliveryDate: '',
                bookingInfo: {
                    agreedDeliveryDate: '',
                    newDeliveryDate: '',
                },
                isOKToBook: false,
                bookableTitle: '',
            }
        },
        created() {
            console.log('ReBookJobBody Component created.');
            //this.bookingInfo.agreedDeliveryDate = this.getFormatDate(this.formatDate('2017-05-01', 'DD/MM/YYYY'));
            let currentDeliveryDate = this.selectedOrder.agreed_date ? this.selectedOrder.agreed_date : this.selectedOrder.DELIVERY_DATE;
            this.bookingInfo = {
                agreedDeliveryDate: this.getFormatDate(this.formatDate(currentDeliveryDate, 'DD/MM/YYYY')),
                newDeliveryDate: '',
            }

        },
        components: {
            'rebook-notes-editor': quillEditor,
        },
        mounted() {
            console.log('ReBookJobBody Component mounted.')
        },
        methods:
        {
            onClickRequestOrderExpedite() {
                console.log('onClickRequestOrderExpedite');
                this.$emit('onClickRequestOrderExpedite');
            },
            onClickGoToBookingScreen() {
                console.log('onClickGoToBookingScreen');
                this.$emit('onCloseReBookJobModal');
                let orderNo = this.selectedOrder.order_id;
                let payload = {
                    orderNo: orderNo,
                    location: this.selectedOrder.QUOTE_NUM_PREF,
                    quoteId: this.selectedOrder.quote_id,
                };
                this.$store.dispatch('getOrderDetailOnSearch', payload)
                    .then((response) => {
                        console.log('onClickGoToBookingScreen getOrderDetailOnSearch response=', response);
                        if (this.isEmpty(response.body))
                        {
                            this.$store.dispatch('setSelectedOrder', null);
                            this.$store.dispatch('hideAllNotifications');
                            this.$router.push({name: 'orderlist'});
                        }
                        else
                        {
                            this.$store.dispatch('setSelectedOrder', response.body);
                            this.$store.dispatch('getOrderDetail', payload);
                            this.$store.dispatch('hideAllNotifications');
                            this.$router.push({name: 'booking'});
                        }
                    })
                    .catch((error) => {
                        console.log('onClickGoToBookingScreen getOrderDetailOnSearch error=', error);
                        this.$store.dispatch('setSelectedOrder', null);
                        this.$store.dispatch('hideAllNotifications');
                        this.$router.push({name: 'orderlist'});
                    });

            },
            onUpdateNewDeliveryDateRequest() {
                console.log('onUpdateNewDeliveryDateRequest');
                let payload = {
                    orderId: this.selectedOrder.order_id,
                    quoteId: this.selectedOrder.quote_id,
                    location: this.selectedOrder.QUOTE_NUM_PREF,
                    type: 'AGREED',
                    deliveryDate: this.newDeliveryDate,
                };
                this.$emit('onCloseReBookJobModal');
                this.$store.dispatch('updateDeliveryDateRequest', payload)
                    .then((response) => {
                        console.log('updateDeliveryDateRequest response=', response);
                        // refresh search table
                        this.$events.fire('refreshBookingListTable');
                    })
                    .catch((error) => {
                        console.log('updateDeliveryDateRequest error=', error);
                    });
            },
            onClickSave() {
                console.log('onClickSave');
            },
            onChangeNewDeliveryDate(value) {
                console.log('onChangeNewDeliveryDate value=', value);
                this.newDeliveryDate = value;
                let originalDeliveryDate = this.formatDate(this.bookingInfo.agreedDeliveryDate, 'DD/MM/YYYY', 'DD/MM/YYYY');
                console.log('originalDeliveryDate=',originalDeliveryDate);
                if (this.dateCompare(this.newDeliveryDate, originalDeliveryDate))
                {
                    this.isOKToBook = true;
                    this.bookableTitle = 'OK TO BOOK';
                }
                else
                {
                    this.isOKToBook = false;
                    this.bookableTitle = 'NOT OK TO BOOK';
                }
                this.$emit('onChangeDeliveryDate', this.isOKToBook);
            },
        }
    }
</script>

<style scoped>
    .canBook {
        width: 100%;
    }
    .block_title {
        width: 50%;
        display: inline-block;
    }
    .block_content {
        display: inline-block;
    }
    .rebook-row {
        width: 100%;
        margin-top: 10px;
        margin-bottom: 10px;
    }
    .rebook-row-left {
        width: 100%;
        margin-top: 10px;
        margin-bottom: 10px;
        text-align: right;
    }
</style>
