<template>
    <div class="user-list-view">
        <table class="table table-hover table-striped table-responsive table-bordered table-condensed">
            <thead>
            <tr>
                <th>User Name</th>
                <th>User Email</th>
                <th>Group | Role</th>
                <th>Actions
                    <div class="pull-right">
                        <span class="btn btn-primary btn-xs" title="New" @click="onClickNew">
                            <span class="glyphicon glyphicon-plus"></span>NEW
                        </span>
                    </div>
                </th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="(userNode,index) in userNodes">
                <td>{{ userNode.name }}</td>
                <td>{{ userNode.email }}</td>
                <td>
                    <div v-for="usergroup in userNode.usergroups">
                        {{ usergroup.group? usergroup.group.name : ''  }} {{ usergroup.group.role? '|' + usergroup.group.role.name : '' }}
                    </div>

                </td>
                <td class="center">
                    <user-list-view-actions :item="userNode"
                                             :row-data="userNode"
                                             :row-index="index"
                                             @onActions="onActions"
                    >
                    </user-list-view-actions>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
    import Vue from 'vue';
    import UserCustomActions from './UserCustomActions.vue'


    export default {
        props: {
            userNodes: {
                type: Object,
                default() { return null; }
            },
        },
        data () {
            return {
            }
        },
        created() {
            console.log('UserListView Component created.')
        },
        updated() {
            console.log('UserListView Component updated.')
        },
        components: {
            'user-list-view-actions': UserCustomActions,
        },
        mounted() {
            console.log('UserListView Component mounted.')
        },
        methods: {
            onClickNew() {
                console.log('onClickNew');
                let formData = {
                    name: '',
                    comment: '',
                    id: '',
                };
                let payload = {
                    isShow: true,
                    data: {action: 'Add', data: formData, index: 0}
                };
                this.$store.dispatch('setUserShowModal', payload)
            },
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
                        text: 'You will not be able to recover this user!',
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
                        me.$store.dispatch('deleteUserRequest', data.data)
                            .then((response) => {})
                            .catch((error) => {});
                    }, function (dismiss) {
                    });
                    return;
                }
                this.$store.dispatch('setUserShowModal', payload)
            }
        }
    }
</script>

<!--<style lang="scss" src='sweetalert2/dist/sweetalert2.css' scoped>-->
<!--</style>-->