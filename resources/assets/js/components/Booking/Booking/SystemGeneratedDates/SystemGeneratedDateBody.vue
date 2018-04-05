<template>
    <div>
        <div>
            <div v-if="dateList.length > 0" id="availableDeliveryDateList" class="panel-collapse collapse in table-responsive">
                <Table border
                       highlight-row
                       :columns="columns"
                       :data="dateList"
                       height="200"
                       @on-current-change="onSelectionChange"
                ></Table>
            </div>
            <!--<div v-else>-->
                <!--<bs-loading size="sm" v-model="isLoading" text="Loading..."></bs-loading>-->
            <!--</div>-->
        </div>
    </div>
</template>

<script>
    import Vue from 'vue';
    import { mapGetters, mapState, mapActions} from 'vuex'
//    import spinner from 'vue-strap/src/Spinner'

    export default {
        props: {
            dateList: {
                type: Array,
                default: [],
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
                        type: 'index',
                        width: 60,
                        align: 'center'
                    },
                    {
                        title: 'Available Date',
                        key: 'date',
                    },
                ],
                availableDate: null,
            }
        },
        created() {
            console.log('SystemGeneratedDateBody Component created.');
            this.$emit('onGetDateList');
        },
        components: {
//            'bs-loading': spinner,
        },
        mounted() {
            console.log('SystemGeneratedDateBody Component mounted.')
        },
        methods:
        {
            onSelectionChange(currentRow, oldCurrentRow) {
                console.log('onSelectionChange currentRow=', currentRow);
                console.log('onSelectionChange oldCurrentRow=', oldCurrentRow);
                this.availableDate = currentRow.date;
                this.$emit('onSelectDeliveryDate', this.availableDate);
            },
        }
    }
</script>

<style>
    .ivu-table-row-highlight td, .ivu-table-stripe .ivu-table-body tr.ivu-table-row-highlight:nth-child(2n) td, .ivu-table-stripe .ivu-table-fixed-body tr.ivu-table-row-highlight:nth-child(2n) td, tr.ivu-table-row-highlight.ivu-table-row-hover td {
        background-color: #57a3f3 !important;
        color: white;
    }
</style>
