<template>
    Not used
  <div id="appsubmenu">
    <router-link :to="menu.link" v-if="!submenus">{{ menu.name }}</router-link>
    <li v-else="submenus" class="dropdown" >
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">{{ menu.name }} <span class="caret"></span></a>
            <ul class="dropdown-menu">
            <li v-for="submenu in submenus">
                <router-link :to="submenu.link" >{{ submenu.name }}</router-link>
            </li>
        </ul>
    </li>
  </div>
</template>

<script>
export default {
    props:{
        menu: {
            type: Object
        }
    },
    computed: {
        submenus() {
            if (typeof this.menu.children !== 'undefined')
            {
                return this.menu.children;
            }
        },
    },
    data () {
        return {}
    },
    created() {
        console.log('AppSubMenu Component created. menu=', this.menu)
    },
    components: {

    },
    mounted() {
        console.log('AppSubMenu Component mounted.')
    }
}
</script>

<style >

</style>
