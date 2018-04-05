<template>
    <div>
        <div>
            <div class="update-confirmed-date-body">
                <div class="form-group">
                    <div class="update-confirmed-date-row">
                        <div class="block_title">
                            <label for="currentDeliveryDate">Current Delivery Date</label>
                        </div>
                        <div class="block_content">
                            <Date-picker
                                    id="currentDeliveryDate"
                                    :value="currentDeliveryDate"
                                    format="dd/MM/yyyy" type="date"
                                    :disabled="true"
                                    readonly
                            >
                            </Date-picker>
                        </div>
                    </div>
                    <div class="update-confirmed-date-row">
                        <div class="block_title"><label for="newDeliveryDate">Current Confirmed Date</label></div>
                        <div class="block_content">
                            <Date-picker
                                    id="currentConfirmedDate"
                                    :value="currentConfirmedDate"
                                    format="dd/MM/yyyy" type="date"
                                    :disabled="true"
                                    readonly
                            >
                            </Date-picker>
                        </div>
                    </div>
                    <div class="update-confirmed-date-row">
                        <div class="block_title"><label for="newDeliveryDate">New Confirmed Date</label></div>
                        <div class="block_content">
                            <Date-picker
                                    id="newAgreedDate"
                                    :value="newAgreedDate"
                                    format="dd/MM/yyyy" type="date"
                                    @on-change="onChangeNewConfirmedDate"
                            >
                            </Date-picker>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import moment from 'moment'
    export default {
        props: {
            itemData: null,
        },
        data () {
            return {
                currentDeliveryDate: '',
                currentConfirmedDate: '',
                newAgreedDate: '',
                formData : {
                    associatedJobId: '',
                    quoteId: '',
                    newAgreedDate: '',
                }
            }
        },
        created() {
            console.log('UpdateConfirmedDateBody Component created.');
            // need to change the date string type to date type
            // datepicker only accept date type
            // otherwise it will cause issues on IE11
            this.currentDeliveryDate = (this.itemData && this.itemData.new_delivery_date)? this.itemData.new_delivery_date: this.itemData.DELIVERY_DATE;
            this.currentConfirmedDate =  (this.itemData && this.itemData.confirmed_date)? this.itemData.confirmed_date: '';
            this.currentDeliveryDate = this.getFormatDate(this.formatDate(this.currentDeliveryDate, 'DD/MM/YYYY'));
            this.currentConfirmedDate = this.getFormatDate(this.formatDate(this.currentConfirmedDate, 'DD/MM/YYYY'));
            //this.newConfirmedDate = moment();
            //console.log('UpdateConfirmedDateBody Component created. newConfirmedDate=', this.newConfirmedDate);
            //this.formData.newConfirmedDate = this.formatDatetime(this.newConfirmedDate, 'DD/MM/YYYY');
            this.formData = {
                id: this.itemData? this.itemData.id : '',
                quoteId: this.itemData? this.itemData.quote_id : '',
                orderId: this.itemData? this.itemData.order_id : '',
                newAgreedDate: '',
            }
        },
        components: {
        },
        mounted() {
            console.log('UpdateConfirmedDateBody Component mounted.')
        },
        methods:
        {
            onChangeNewConfirmedDate(value) {
                console.log('onChangeNewDeliveryDate value=', value);
                this.formData.newAgreedDate = value;
            },
        }
    }
</script>

<style scoped>
    .block_title {
        width: 50%;
        text-align: left;
        display: inline-block;
    }
    .block_content {
        display: inline-block;
    }
    .update-confirmed-date-row {
        width: 100%;
        margin-top: 10px;
        margin-bottom: 10px;
    }
</style>
