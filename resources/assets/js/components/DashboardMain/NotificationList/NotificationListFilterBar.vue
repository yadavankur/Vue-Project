<template>
    <div class="notification-list-filter-bar filter-bar">
        <form class="form-inline form-group-sm">
            <div class="pull-left">
                <label >Search for:</label>
                <Select filterable
                        clearable
                        v-model="formFilter.status"
                        placeholder="status..."
                        style="width:180px">
                    <Option v-for="item in statusOptions" :value="item.value" :key="item" :label="item.label">{{ item.label }}</Option>
                </Select>
                <bs-input placeholder="subject or message ..." type="text" :rows="1" :maxlength="255" v-model="formFilter.filterText"></bs-input>
                <button class="btn btn-success btn-sm" @click.prevent="doFilter">Search</button>
                <button class="btn btn-warning btn-sm" @click.prevent="resetFilter">Reset</button>
            </div>
        </form>
    </div>
</template>

<script>
    import input from 'vue-strap/src/Input'
    export default {
        components: {
            'bs-input': input,
        },
        props: {
        },
        data () {
            return {
                formFilter: {
                    status: 'UnRead',
                    filterText: '',
                },
                statusOptions : [
                    { value: 'Read', label: 'Read'},
                    { value: 'UnRead', label: 'UnRead'},
                ],
            }
        },
        created() {
        },
        methods: {
            doFilter () {
                this.$events.fire('notification-list-filter-set', this.formFilter)
            },
            resetFilter () {
                this.formFilter = {
                    status: 'UnRead',
                    filterText: '',
                };
                this.$events.fire('notification-list-filter-reset', this.formFilter)
            },
        }
    }
</script>
