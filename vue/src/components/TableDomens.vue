<template>
  <h2>Домены недели</h2>

  <div class="table">
    <ul class="table__titles">
      <li>id домена</li>
      <li>Домен</li>
      <li>Кол-во посещений</li>
      <li>Кол-во посещений за прошлый месяц</li>
      <li>Кол-во оставленных ĸонтаĸтов</li>
      <li>Кол посетителей с оставленными ĸонтаĸтами</li>
      <li>Дата последнего ĸонтаĸта</li>
    </ul>

    <div class="table__values-container">
      <ul
        v-for="(domain, index) in domains"
        class="table__values"
        :class="index % 2 === 0 ? 'table__values_background_black' : 'table__values_background_white'"
      >
        <li>
          {{ domain.domain_id }}
        </li>

        <li>
          {{ domain.domain }}
        </li>

        <li>
          {{ domain.count_visits }}
        </li>

        <li>
          {{ domain.count_visits_prev_month }}
        </li>

        <li>
          {{ domain.count_contacts }}
        </li>

        <li>
          {{ domain.count_users_contacts }}
        </li>

        <li>
          {{ domain.date_last_contact || 'Нет информации' }}
        </li>
      </ul>
    </div>
  </div>
</template>

<script>
  export default {
    data() {
      return {
        domains: []
      }
    },

    methods: {
      getDomens() {
        fetch('http://localhost/test-big-data/api/getVisitInfo.php', {
          method: 'GET',
          headers: {
            'Content-Type': 'application/json'
          },
        })
        .then(res => res.ok ? res.json() : Promise.reject(res))
        .then(res => this.domains = res)
        .catch(err => console.error(err));
      }
    },

    mounted() {
      this.getDomens();
    }
  }
</script>

<style scoped>
  .table {
    max-width: 1100px;
    width: 100%;
    display: flex;
    flex-direction: column;
    margin-top: 30px;
  }

  .table__titles {
    display: flex;
  }

  .table__titles > * {
    width: 100%;
    font-size: 16px;
  }

  .table__values-container {
    margin-top: 20px;
  }

  .table__values {
    display: flex;
    border-radius: 4px;
    padding: 7px 4px;
  }

  .table__values > * {
    width: 100%;
    font-size: 15px;
  }

  .table__values_background_black {
    background-color: #424242;
    color: #fff;
  }

  .table__values_background_white {
    background-color: #AEAEAE;
    color: #000;
  }
</style>