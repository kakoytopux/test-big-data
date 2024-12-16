<template>
  <main>
    <section class="domens">
      <TableDomens />
    </section>
  </main>
</template>

<script>
  import TableDomens from './components/TableDomens.vue';

  export default {
    components: {
      TableDomens
    }
  }
</script>

<style scoped>
  .domens {
    display: flex;
    align-items: center;
    flex-direction: column;
    padding-top: 50px;
  }
</style>
