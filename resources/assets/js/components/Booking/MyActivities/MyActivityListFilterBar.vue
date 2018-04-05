<template>
    <div class="my-activity-list-filter-bar filter-bar">
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
                <bs-input placeholder="task name ..." type="text" :rows="1" :maxlength="255" v-model="formFilter.filterText"></bs-input>
                <button class="btn btn-success btn-sm" @click.prevent="doFilter">Search</button>
                <button class="btn btn-warning btn-sm" @click.prevent="resetFilter">Reset</button>
            </div>
        </form>
    </div>
</template>

<script>
    import input from 'vue-strap/src/Input'
    export default
     {  components: { 'bs-input': input, },
        props: {   },
        data () { return {  formFilter: {  status: '',  filterText: '',  }, statusOptions: [],  }  },
        created() 
        {       this.$store.dispatch('getActivityStatuses')
                .then((response) => {
                    console.log('/resources/js/components/booking/myactivities/MyActivityListFilterBar.vue-getActivityStatuses response=', response);
                    this.statusOptions = response.body.statuses;
                })
                .catch((error) => {
                    console.log('/resources/js/components/booking/myactivities/MyActivityListFilterBar.vue-getActivityStatuses error=', error);
                });
        },
        methods: {
                   doFilter () {  this.$events.fire('my-activity-list-filter-set', this.formFilter)  },
                   resetFilter () 
                     { this.formFilter = {    status: '',   filterText: '',    };
                       this.$events.fire('my-activity-list-filter-reset')
                     },
                  }
    }
</script>
