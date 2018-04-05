<template>
    <div class="role-list-view">
        <table class="table table-hover table-striped table-responsive table-bordered table-condensed">
            <thead>
            <tr>
                <td>
                    Role Name
                </td>
                <td>Parent Role Name</td>
                <td>Comment</td>
                <td>Actions</td>
            </tr>
            </thead>
            <tbody>
            <tr v-for="(roleNode,index) in roleNodes">
                <td>{{ roleNode.name }}</td>
                <td>{{ roleNode.parent? roleNode.parent.name: '' }}</td>
                <td>{{ roleNode.comment }}</td>
                <td class="center">
                    <role-list-view-actions :item="roleNode"
                            :row-data="roleNode"
                            :row-index="index"
                            :key="roleNode.id"
                            @onActions="onActions"
                            :selectedRole = "selectedRole"
                    >
                    </role-list-view-actions>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
    import Vue from 'vue';
    import RoleCustomActions from './RoleCustomActions.vue'
    export default {
        props: {
            roleNodes: {
                type: Object,
                default() { return null; }
            },
            selectedRole: {
                type: Object,
            }
        },
        data () {
            return {
            }
        },
        created() {
            console.log('RoleListView Component created.')
        },
        updated() {
            console.log('RoleListView Component updated.')
        },
        components: {
            'role-list-view-actions': RoleCustomActions,
        },
        mounted() {
            console.log('RoleListView Component mounted.')
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
                        text: 'You will not be able to recover this role!',
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes',
                        cancelButtonText: 'cancel',
                        confirmButtonClass: 'btn btn-success btn-sm',
                        cancelButtonClass: 'btn btn-danger btn-sm',
                        allowOutsideClick: false
                    }).then(function() {
                        me.$store.dispatch('deleteRoleRequest', data.data)
                            .then((response) => {})
                            .catch((error) => {});
                    }, function (dismiss) {
                    });
                    return;
                }

                this.$store.dispatch('setRoleShowModal', payload)
            }
        }
    }
</script>
