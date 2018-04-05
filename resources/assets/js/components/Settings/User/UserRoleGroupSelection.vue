<template>
        <tr class="list-panel-row">
            <td width="90%">
                <div class="input-group-sm">
                    <Cascader v-model="cascade_role_group"
                              :data="options"
                              size="large"
                              placeholder="Please select a role/group..."
                              @on-change="handleChange"
                    >
                    </Cascader>
                </div>
            </td>
            <td width="10%">
                <span class="btn btn-del btn-danger btn-sm" @click="onDelete(rowData, rowIndex)">
                    <span class="glyphicon glyphicon-trash"></span>
                </span>
            </td>
        </tr>
</template>

<script>
    import { mapState } from 'vuex'

    export default {
        components: {
        },
        props: {
            currentRole: '',
            selectedRole: '',
            selectedGroup: '',
            rowData: {
                type: Object,
                required: true
            },
            rowIndex: {
                type: Number,
            },
            options: {
                type: Array,
                default: [],
            },
        },
        computed: {
            ...mapState({
                user: state => state.authUser,
            }),
        },
        data () {
            return {
                defaultRole: '',
                defaultGroup: '',
                groupOptions: [],
                roleOptions: [],
                minSearch: 1,
                itemData: {role: {id: '', name: ''}, group:{id: '', name: ''}},
                cascade_role_group: [],
            }
        },
        created() {
            console.log('########################created UserRoleGroupSelection created');
            console.log('created UserRoleGroupSelection created selectedRole=', this.selectedRole);
            console.log('created UserRoleGroupSelection created selectedGroup=', this.selectedGroup);
            this.cascade_role_group = [];
            this.cascade_role_group.push(this.selectedRole.id);
            this.cascade_role_group.push(this.selectedGroup.id);
        },
        methods: {
            handleChange (value, selectedData) {
                console.log('handleChange value=', value);
                console.log('handleChange selectedData=', selectedData);
                this.rowData.role = {id: '', name: ''};
                this.rowData.group = {id: '', name: ''};
                if (selectedData.length > 0)
                {
                    this.rowData.role = {id: selectedData[0].value, name: selectedData[0].label};
                    this.rowData.group = {id: selectedData[1].value, name: selectedData[1].label};
                }
            },
            setRoleOptions(roles) {
                console.log('setRoleOptions roles=',roles);
                let options = [];
                for (let role in roles) {
                    options.push({value: roles[role].name, label: roles[role].name});
                }
                this.roleOptions = options;
                if (options.length == 0) return;
                this.defaultRole = this.selectedRole ? this.selectedRole : this.roleOptions[0].value;
                console.log('setRoleOptions this.defaultRole=',this.defaultRole);

            },
            setGroupOptions(groups) {
                console.log('setGroupOptions groups=', groups);
                if (!groups || groups.length == 0) return;
                let options = [];
                for (let group in groups) {
                    options.push({value: groups[group].name, label: groups[group].name});
                }
                this.groupOptions = options;
                if (options.length == 0) return;
                this.defaultGroup = this.selectedGroup ? this.selectedGroup : this.groupOptions[0].value;
            },
            onDelete(rowData, rowIndex) {
                console.log('UserRoleGroupSelection onDelete');
                console.log('UserRoleGroupSelection onDelete rowData=', rowData);
                console.log('UserRoleGroupSelection onDelete =rowIndex', rowIndex);
                this.$emit('onDelete', {action: 'delete', data: rowData, index: rowIndex});

            },
            onRoleChanged(selVal) {
                console.log('*******************UserRoleGroupSelection onRoleChanged selectedRole=', selVal);
                //this.groupOptions = [];
                //this.selectedGroup = '';
                if (!selVal) return;
                // whenever role changes, get the associated groups
                this.$store.dispatch('getGroupOptions', selVal)
                    .then((response) => {
                        console.log('getGroupOptions success=', response);
                        this.groupOptions = [];
                        this.setGroupOptions(response.data.groups);
                        this.rowData.role = selVal;
                        this.rowData.group = this.groupOptions.length > 0 ? this.groupOptions[0].value : '';
                    })
                    .catch((error) => {
                        console.error('getGroupOptions error=', error);
                    });
            },
            onGroupChanged(selVal) {
                console.log('UserRoleGroupSelection onGroupChanged selVal=', selVal)
            }
        },
        watch: {
            defaultRole(newVal, oldVal) {
                console.log('defaultRole oldVal=', oldVal);
                console.log('defaultRole newVal=', newVal);
            },
            defaultGroup(newVal, oldVal) {
                console.log('defaultGroup oldVal=', oldVal);
                console.log('defaultGroup newVal=', newVal);
            }
        }
    }
</script>
<style>
</style>