<template>
    <div class="filter-bar">
        <form class="form-inline form-group-sm">
            <div class="form-group">

                <button class="btn btn-primary btn-sm" title="New" @click.prevent="onClickNew">
                    <span class="glyphicon glyphicon-plus"></span>NEW
                </button>
            </div>
        </form>
    </div>
</template>

<script>
    export default 
    {   
        methods: 
        {   
            onClickNew() {  console.log('/cs/filterbar-- onClickNew');
                            this.$emit('onClickNew', {action: 'Add'});
                         }
        }
    }
</script>
<!--<style scoped>-->
    <!--.filter-bar {-->
        <!--padding: 10px;-->
    <!--}-->
<!--</style>-->