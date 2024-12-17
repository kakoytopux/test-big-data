<template>
  <div @click="closePopupScreen" class="popup">
    <div class="popup__container">
      <ul class="popup__titles">
        <li>id домена</li>
        <li>Название</li>
        <li>Баланс</li>
        <li>Категории</li>
      </ul>

      <ul class="popup__values">
        <li>{{ domainsInfo.domain_id }}</li>
        <li>{{ domainsInfo.name }}</li>
        <li>{{ domainsInfo.balance }}</li>
        <ul class="popup__values_type_categories">
          <li
            v-for="category in domainsInfo.categories"
            :key="category.id"
          >
            {{ category.categories }}
          </li>
        </ul>
      </ul>

      <div class="popup__contacts-container">
        <h2>Все виды контактов</h2>

        <ul class="popup__contacts-lists">
          <li
            v-for="contact in contacts"
            :key="contact.id"
          >
            {{ contact.info }}
          </li>
        </ul>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    closePopupScreen: Function,
    domainsInfo: Object,
    contacts: Array
  },
};
</script>

<style scoped>
.popup {
  display: flex;
  justify-content: center;
  align-items: center;
  position: fixed;
  top: 0;
  left: 0;
  z-index: 10;
  background-color: rgba(0, 0, 0, 0.6);
  width: 100%;
  height: 100%;
}

.popup__container {
  width: 100%;
  max-width: 400px;
  height: 400px;
  background-color: #fff;
  border-radius: 6px;
  padding: 10px;
}

.popup__titles {
  display: flex;
}

.popup__titles > * {
  width: 100%;
  font-size: 17px;
}

.popup__values {
  display: flex;
  margin-top: 10px;
}

.popup__values > * {
  width: 100%;
  font-size: 14px;
}

.popup__values_type_categories {
  display: flex;
  flex-direction: column;
  gap: 6px;
}

.popup__contacts-container {
  margin-top: 20px;
}

.popup__contacts-lists {
  display: flex;
  flex-direction: column;
  gap: 5px;
  margin-top: 5px;
}
</style>
