<template>
    <div>
        <div>
            <div id="attachmentList" class="panel-collapse collapse in table-responsive">
                <Table border :columns="columns"
                       :data="attachmentList"
                       height="400"
                       @on-select="onSelect"
                       @on-selection-change="onSelectionChange"
                       @on-select-all="onSelectAll"
                ></Table>
            </div>
        </div>
    </div>
</template>

<script>
    import Vue from 'vue';
    import { mapGetters, mapState, mapActions} from 'vuex'

    export default {
        props: {
            order: {
                type: Object,
            },
            selectedAttachments: {
                type: Array,
            }
        },
        computed: {
            ...mapState({
                user: state => state.authUser,
            }),
        },
        data () {
            return {
                columns: [
                    {
                        type: 'selection',
                        align: 'center',
                        width: '60'
                    },
                    {
                        title: 'File Name',
                        key: 'fileName',
                        width: '50%'
                    },
                    {
                        title: 'Version',
                        key: 'version',
                        filters: [
                            {
                                label: 'Latest',
                                value: 'Latest'
                            },
                            {
                                label: 'Old',
                                value: 'Old'
                            },
                        ],
                        filterMethod (value, row) {
                            return row.version.indexOf(value) > -1;
                        }
                    },
                    {
                        title: 'Actions',
                        key: 'action',
                        align: 'center',
                        render: (h, params) => {
                            return h('div', [
                                h('Button', {
                                    props: {
                                        type: 'primary',
                                        size: 'small'
                                    },
                                    on: {
                                        click: () => {
                                            this.showAttachment(params)
                                        }
                                    }
                                }, 'View'),
                            ]);
                        }
                    }
                ],
                attachmentList: [
                ],
                attachments: [],
            }
        },
        created() {
            console.log('AttachmentPreparationBody Component created.');
            // get pdf files associated to this order
            let payload = {
                orderId: this.order.UDF1,
                quoteId: this.order.QUOTE_ID,
                location: this.order.QUOTE_NUM_PREF,
            };
            this.$store.dispatch('getOrderAttachments', payload)
                .then((response) => {
                    console.log('getOrderAttachments response=', response);
                    this.attachmentList = response.body.attachments;
                    this.attachments = this.selectedAttachments;
                    if (this.selectedAttachments.length > 0)
                    {
                        let fileNameArray = this.selectedAttachments.map(function(a) {return a.fileName;});
                        console.log('fileNameArray= ', fileNameArray);
                        this.attachmentList.forEach( (attachment) => {
                            console.log('attachment= ', attachment);
                            let foundResults = fileNameArray.filter(function(a) {return (a == attachment.fileName)});
                            console.log('foundResults= ', foundResults);
                            if (foundResults && (foundResults.length > 0))
                            {
                                console.log('fitler true attachment= ', attachment);
                                attachment._checked = true;
                            }
                        });
                    }
                })
                .catch((error) => {
                    console.log('getOrderAttachments error=', error);
                });
        },
        components: {
        },
        mounted() {
            console.log('AttachmentPreparationBody Component mounted.')
        },
        methods:
        {
            onSelect(selection, row) {
                console.log('onSelect selection=', selection);
                this.attachments = selection;
            },
            onSelectionChange(selection) {
                console.log('onSelectionChange selection=', selection);
                this.attachments = selection;
            },
            onSelectAll(selection) {
                console.log('onSelectAll selection=', selection);
                this.attachments = selection;
            },
            showAttachment(params) {
                console.log('showAttachment params=', params);
                // download the pdf file from the server
                // to show in the new window
                let payload = {
                    //orderId: this.order.UDF1, // actually no need orderId
                    fileName: params.row.fullPath,
                };
                this.$store.dispatch('downloadAttachment', payload)
                    .then((response) => {
                        console.log('showAttachment -> downloadAttachment successfully');
                        this.downLoad(response);
                    })
                    .catch((error) => {
                        console.log('showAttachment -> downloadAttachment error=', error.body.error);
                    });
            }
        }
    }
</script>

<style scoped>
    label {
        margin-top: 5px;
    }
</style>
