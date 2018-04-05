<template>
    <div>
        <cs-update-confirmed-date-modal
                :isShowUpdateConfirmedDate="isShowUpdateConfirmedDate"
                :itemData="itemData"
                @onCloseUpdateConfirmedDateModal="onCloseUpdateConfirmedDateModal">
        </cs-update-confirmed-date-modal>
        <div class="associated-jobs-custom-actions text-center">
            <button v-if="actConfirm" class="btn btn-warning btn-sm" @click="itemAction('Confirm', rowData, rowIndex)" title="Update Delivery Date"><i class="glyphicon glyphicon-pencil"></i></button>
            <button v-if="actEmail && rowData && rowData.booking" class="btn btn-primary btn-sm" @click="itemAction('Email', rowData, rowIndex)" title="Email Single Confirmation"><i class="glyphicon glyphicon-envelope"></i></button>
            <button v-if="actText && rowData && rowData.booking" class="btn btn-success btn-sm" @click="itemAction('Text', rowData, rowIndex)" title="Text Single Confirmation"><i class="glyphicon glyphicon-comment"></i></button>
        </div>
    </div>
</template>
<script>
    import CSUpdateConfirmedDateModal from './UpdateConfirmedDateModal.vue'
    export default {
        components: {
            'cs-update-confirmed-date-modal': CSUpdateConfirmedDateModal,
        },
        props: {
            item: {
                type: Object,
            },
            rowData: {
                type: Object,
                required: true
            },
            rowIndex: {
                type: Number,
            },
        },
        data () {
            return {
                actConfirm: {
                    type: Boolean,
                    default: true
                },
                actEmail: {
                    type: Boolean,
                    default: true
                },
                actText: {
                    type: Boolean,
                    default: true
                },
                isShowUpdateConfirmedDate: false,
                itemData: null,
            }
        },
        mounted() {
        },
        updated() {
            console.log('CSAssociatedJobsCustomActions Component updated.');
        },
        created() {
            console.log('CSAssociatedJobsCustomActions created: ', this.rowData, this.rowIndex);
        },
        methods: {
            onCloseUpdateConfirmedDateModal() {
                console.log('onCloseUpdateConfirmedDateModal');
                this.isShowUpdateConfirmedDate = false;
                this.itemData = null;

            },
            itemAction (action, data, index) {
                console.log('itemAction: CustomAction:action-item :' + action, data, index);
                //this.$parent.$emit('CustomAction:action-item', {action: action, data: data, index: index});
                switch (action)
                {
                    case 'Confirm':
                        // update confirmed date
                        this.itemData = data;
                        this.isShowUpdateConfirmedDate = true;
                        break;
                    case 'Email':
                        // sending email
                        this.$parent.$emit('CustomAction:action-item', {action: action, data: data, index: index});
                        break;
                    case 'Text':
                        // sending text message
                        this.$parent.$emit('CustomAction:action-item', {action: action, data: data, index: index});
                        break;
                }
            },
        },
        watch: {
        }
    }
</script>

<style scoped>
    .custom-actions button.ui.button {
        padding: 8px 8px;
    }
    .custom-actions button.ui.button > i.icon {
        margin: auto !important;
    }
</style>