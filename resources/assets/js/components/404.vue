<template>
  <div id="notfound">
      <div class="top-menu-bg">
      </div> 
      <hr class="spartan">
      <div class="row vertical-10p">
        <div class="container">
          <img src="../../img/logo.png" class="center-block logo">
          <div class="text-center col-sm-6 col-sm-offset-3">
            <h1>You are lost.</h1>
            <h4>This page doesn't exist.</h4>
            <router-link to="/">Take me home.</router-link>
          </div>
        </div>
      </div>
  </div>
</template>
<script>
  export default {
    name: 'NotFound'
  }
</script>

<style>
.top-menu-bg {
    width: 100%;
    background-color: #0060af;
    min-height: 29px;
    position: relative;
}
</style>
