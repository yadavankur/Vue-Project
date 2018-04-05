<template>
    <div id="root-comments-info">
        <dowell-notes-preparation-modal
                :isShowDowellNotes="isShowDowellNotes"
                :orderId="orderId"
                :quoteId="quoteId"
                :location="location"
                @onCloseDowellNotesModal="onCloseDowellNotesModal"
                @onRefreshNotes="onRefreshNotes"
        >
        </dowell-notes-preparation-modal>
        <dowell-notes-history-modal
                :isShowDowellNotesHistory="isShowDowellNotesHistory"
                :orderId="orderId"
                :quoteId="quoteId"
                :location="location"
                @onCloseDowellNotesHistoryModal="onCloseDowellNotesHistoryModal"
        >
        </dowell-notes-history-modal>
        <div class="app-row">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <a class="accordion-toggle" data-toggle="collapse"  href="#commentsinfo">
                        {{ title }}
                    </a>
                </div>
                <div id="commentsinfo" class="panel-collapse collapse in table-responsive">
                    <div class="dowellNotes-content">
                        <table class="table table-hover table-striped table-responsive table-bordered table-condensed">
                            <tbody>
                            <tr>
                                <td class="col-title">Dowell Notes
                                </td>
                                <td>
                                    <button type="button" class="btn btn-success btn-sm"
                                            @click="onOpenDowellNotesModal"
                                    ><i class="glyphicon glyphicon-comment"></i> New Notes
                                    </button>
                                    <button type="button" class="btn btn-warning btn-sm"
                                            @click="onOpenDowellNotesHistoryModal"
                                    ><i class="glyphicon glyphicon-list-alt"></i> Notes History
                                    </button>
                                    <br><br>
                                    <a><strong>{{ updatedInfo }}</strong></a>
                                    <br>
                                    <div class="notes" v-html="dowellNotes"></div>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import { mapGetters, mapState, mapActions} from 'vuex'
    import DowellNotesPreparationModal from '../Booking/DowellNotes/DowellNotesPreparationModal.vue'
    import DowellNotesHistoryModal from '../Booking/DowellNotes/DowellNotesHistoryModal.vue'

    export default {
        props: {
            title: '',
            quoteId: '',
            location: '',
            orderId: '',
        },
        computed: {
            ...mapGetters({
            }),
            ...mapState({
                user: state => state.authUser,
            }),
        },
        data () {
            return {
                dowellNotes: '',
                updatedInfo: '',
                isShowDowellNotesHistory: false,
                isShowDowellNotes: false,

            }
        },
        created() {
            console.log('CommentsInfo Component created.')
            // get the latest customer notes
            this.getLatestNotesRequest();
        },
        components: {
            'dowell-notes-preparation-modal': DowellNotesPreparationModal,
            'dowell-notes-history-modal': DowellNotesHistoryModal,
        },
        mounted() {
            console.log('CommentsInfo Component mounted.')
        },
        events: {
            'refreshLastDowellNotes'() {
                this.onRefreshNotes();
            }
        },
        methods:
        {
            onRefreshNotes() {
                this.getLatestNotesRequest();
            },
            getLatestNotesRequest() {
                let payload = {
                    quoteId: this.quoteId,
                    location: this.location,
                    orderId: this.orderId,
                    type: 'DOWELL_NOTES',
                };
                this.$store.dispatch('getLatestNotesRequest', payload)
                    .then((response) => {
                        console.log('getLatestNotesRequest response=', response);
                        let notes = response.data;
                        if (!this.isEmpty(notes))
                        {
                            this.updatedInfo = 'Last update by '+ notes.created_by.name
                                + ' on ' + notes.updated_at;
                            this.dowellNotes = notes.comments;
                        }
                        else
                        {
                            this.updatedInfo = '';
                            this.dowellNotes = '';
                        }
                    })
                    .catch((error) => {
                        console.log('getLatestNotesRequest error=', error);
                    });
            },
            onOpenDowellNotesModal() {
                console.log('onOpenDowellNotesModal');
                this.isShowDowellNotes = true;
            },
            onOpenDowellNotesHistoryModal() {
                console.log('onOpenDowellNotesHistoryModal');
                this.isShowDowellNotesHistory = true;
            },
            onCloseDowellNotesHistoryModal() {
                console.log('onCloseDowellNotesHistoryModal');
                this.isShowDowellNotesHistory = false;
            },
            onCloseDowellNotesModal() {
                console.log('onCloseDowellNotesModal');
                this.isShowDowellNotes = false;
            },
        }
    }
</script>

<style scoped>
    /*.panel-primary > .panel-heading {*/
        /*color: white;*/
        /*background-color: #0a5b9e;*/
        /*border-color: #0a5b9e;*/
    /*}*/
    /*.panel-heading a {*/
        /*color: white;*/
    /*}*/
    /*.panel-heading .accordion-toggle:after {*/
        /*!* symbol for "opening" panels *!*/
        /*font-family: 'Glyphicons Halflings';*/
        /*content: "\e114";*/
        /*float: right;*/
        /*color: white;*/
    /*}*/
    /*.panel-heading .accordion-toggle.collapsed:after {*/
        /*!* symbol for "collapsed" panels *!*/
        /*content: "\e080";*/
    /*}*/
    .notes {
        height: 200px;
        overflow-y: scroll;
    }
    .table {
        width: 100%;
        max-width: 100%;
        margin: 2px;
    }
    .dowellNotes-content {
        margin: 2px;
    }
</style>
