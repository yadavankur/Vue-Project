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
                        <div class="block_title"><label for="newDeliveryDate">New Agreed Delivery Date</label></div>
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
                    <div class="update-confirmed-note-row">
                        <div><label for="noteType">Note Type</label></div>
                        <div>
                            <Select  v-model="formData.noteTypeId"
                                     clearable
                                     @on-change="onChangeNoteType"
                                     placeholder="Please select a note type...">
                                <Option v-for="item in noteTypeOptions" :value="item.value" :key="item" :label="item.label">{{ item.label }}</Option>
                            </Select>
                        </div>
                    </div>
                    <div class="update-confirmed-note-row">
                        <div><label for="dowellNotesContent">Notes</label></div>
                        <div>
                            <dowell-notes-editor v-model="formData.dowellNotesContent"
                                                 ref="dowellNotesQuillEditor"
                                                 :options="editorOption">
                            </dowell-notes-editor>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import { quillEditor } from 'vue-quill-editor'

    export default {
        props: {
            itemData: null,
        },
        data () {
            return {
                editorOption: {
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
                currentDeliveryDate: '',
                currentConfirmedDate: '',
                newAgreedDate: '',
                formData : {
                    associatedJobId: '',
                    quoteId: '',
                    location: '',
                    orderId: '',
                    newAgreedDate: '',
                    dowellNotesContent: '',
                    noteTypeId: '',
                },
                noteTypeOptions: '',
            }
        },
        created() {
            console.log('UpdateConfirmedDateBody Component created.');
            // need to change the date string type to date type
            // datepicker only accept date type
            // otherwise it will cause issues on IE11
            this.currentDeliveryDate = (this.itemData && this.itemData.agreed_date)? this.itemData.agreed_date: this.itemData.DELIVERY_DATE;
            this.currentConfirmedDate =  (this.itemData && this.itemData.confirmed_date)? this.itemData.confirmed_date: '';
            this.currentDeliveryDate = this.getFormatDate(this.formatDate(this.currentDeliveryDate, 'DD/MM/YYYY'));
            this.currentConfirmedDate = this.getFormatDate(this.formatDate(this.currentConfirmedDate, 'DD/MM/YYYY'));
            //this.newConfirmedDate = moment();
            //console.log('UpdateConfirmedDateBody Component created. newConfirmedDate=', this.newConfirmedDate);
            //this.formData.newConfirmedDate = this.formatDatetime(this.newConfirmedDate, 'DD/MM/YYYY');
            this.newAgreedDate = this.currentDeliveryDate;
            this.formData = {
                id: this.itemData? this.itemData.id : '',
                quoteId: this.itemData? this.itemData.quote_id : '',
                orderId: this.itemData? this.itemData.UDF1 : '',
                location: this.itemData? this.itemData.QUOTE_NUM_PREF : '',
                newAgreedDate: this.formatDate(this.currentDeliveryDate, 'DD/MM/YYYY'),
                dowellNotesContent: '',
                noteTypeId: '',
            };
            this.getDowellNoteTypeOptions();
        },
        components: {
            'dowell-notes-editor': quillEditor,
        },
        mounted() {
            console.log('UpdateConfirmedDateBody Component mounted.')
        },
        methods:
        {
            onChangeNoteType(val) {
                console.log('onChangeNoteType val=', val);
            },
            onChangeNewConfirmedDate(value) {
                console.log('onChangeNewDeliveryDate value=', value);
                this.formData.newAgreedDate = value;
            },
            updateData: function (data) {
                console.log('updateData data=', data);
                // sync content to component
                this.formData.dowellNotesContent = data;
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
        text-align: left;
        width: 100%;
        margin-top: 10px;
        margin-bottom: 10px;
    }
    .update-confirmed-note-row {
        text-align: left;
    }
</style>
