<script>

import vSelect from 'vue-select'
Vue.component('v-select',vSelect);
Vue.use(BootstrapVue);
Vue.component('DatePicker',DatePicker);

import relacionBecarioMentor from "../../components/relacionBecarioMentorComponent.vue";
const app = new Vue({
  el: "#relacionBecarioMentorID",
  data: {},
  components: {
    relacionBecarioMentor
  }
});
</script>