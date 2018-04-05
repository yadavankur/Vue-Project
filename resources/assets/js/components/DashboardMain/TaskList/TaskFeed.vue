<template>
    <div >
        <table v-if="pagination && pagination.total > 0" class="table table-hover no-margins">
            <thead>
            <tr>
                <th>
                    Sales Order No
                </th>
                <th>
                    Task Name
                </th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="item in dataItems" :key="item">
                <td class="sales-order-no">
                    <div>{{item.order_id}}</div>
                </td>
                <td class="task-name">
                    <div>{{item.name}}</div>
                </td>
            </tr>
            </tbody>
        </table>
        <button v-if="pagination && pagination.next_page_url" class="btn btn-primary btn-block m-t" @click="showMore()">
            <i class="fa fa-arrow-down"></i> Show More
        </button>
    </div>
</template>

<script>
    import Vue from 'vue';
    import { mapGetters, mapState, mapActions} from 'vuex'
    import * as api from './../../../config';

    export default {
        computed: {
            url(){
                this.moreParams = {
                    type: 'OWN',
                    dueType: this.dueType,
                };
                return api.currentTaskList;
            },
            ...mapGetters({
            }),
            ...mapState({
                user: state => state.authUser
            }),
        },
        props: {
            dueType: { type: String, default: ''}
        },
        components: {
        },
        data () {
            return {
                dataItems: [],
                pageNo: 1,
                perPage: 5,
                pagination: null,
            }
        },
        created() {
            let payload = {
                sort: 'cpm_order_matrixes.end_date|desc',
                page: this.pageNo,
                per_page: this.perPage,
                type: 'OWN',
                dueType: this.dueType,
            };
            this.getTaskFeedByPagination(payload);
        },
        methods: {
            getTaskFeedByPagination(payload)
            {
                console.log('getTaskFeedByPagination');
                this.$store.dispatch('getTaskFeedByPagination', payload)
                    .then((response) => {
                        console.log('getTaskFeedByPagination response=', response);
                        this.pagination = response.body;
                        //this.dataItems.concat(response.body.data);
                        response.body.data.forEach( (item) => {
                            this.dataItems.push(item);
                        });

                    })
                    .catch((error) => {
                        console.log('getTaskFeedByPagination error=', error);
                    });
            },
            showMore() {
                console.log('showMore');
                let payload = {
                    sort: 'cpm_order_matrixes.end_date|desc',
                    page: this.pagination.current_page+1,
                    per_page: this.perPage,
                    type: 'OWN',
                    dueType: this.dueType,
                };
                this.getTaskFeedByPagination(payload);
            }
        },
        watch: {
        },
    }
</script>

<style scoped>
    .no-margins {
        margin: 0 !important;
    }
</style>
