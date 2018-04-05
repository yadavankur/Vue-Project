<template>
    <div class="menu-list-view">
        <table class="table table-hover table-striped table-responsive table-bordered table-condensed">
            <thead>
            <tr>
                <th>Menu Name</th>
                <th>Url Name</th>
                <th>Parent Menu Name</th>
                <th>Comment</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="(menuNode,index) in menuNodes">
                <td>{{ menuNode.name }}</td>
                <td>{{ menuNode.link }}</td>
                <td>{{ menuNode.parent? menuNode.parent.name: '' }}</td>
                <td>{{ menuNode.comment }}</td>
                <td class="center">
                    <menu-list-view-actions :item="menuNode"
                                            :row-data="menuNode"
                                            :row-index="index"
                                            @onActions="onActions"
                    >
                    </menu-list-view-actions>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
    import Vue from 'vue';
    import MenuCustomActions from './MenuCustomActions.vue'
    import VueSweetAlert from 'vue-sweetalert'
    Vue.use(VueSweetAlert);

    export default {
        props: {
            menuNodes: {
                type: Object,
                default() { return null; }
            },
        },
        data () {
            return {
            }
        },
        created() {
            console.log('MenuListView Component created.')
        },
        updated() {
            console.log('MenuListView Component updated.')
        },
        components: {
            'menu-list-view-actions': MenuCustomActions,
        },
        mounted() {
            console.log('MenuListView Component mounted.')
        },
        methods: {
            onActions(data) {
                console.log('onActions data=', data);
                let payload = {
                    isShow: true,
                    data: data
                };
                if (data.action === 'Delete')
                {
                    //
                    let swal = this.$swal;
                    let me = this;
                    this.$swal({
                        title: 'Are you sure?',
                        text: 'You will not be able to recover this menu!',
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes',
                        cancelButtonText: 'cancel',
                        confirmButtonClass: 'btn btn-success',
                        cancelButtonClass: 'btn btn-danger',
                        allowOutsideClick: false
                    }).then(function() {
                        me.$store.dispatch('deleteMenuRequest', data.data)
                            .then((response) => {})
                            .catch((error) => {});
                    }, function (dismiss) {
                    });
                    return;
                }

                this.$store.dispatch('setMenuShowModal', payload)
            }
        }
    }
</script>

<style lang="scss" src='sweetalert2/dist/sweetalert2.css' scoped>
    .swal2-modal .swal2-styled+.swal2-styled {
        margin-top: 0px !important;
    }
</style>