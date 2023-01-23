/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import "./bootstrap";
import { createApp } from "vue";

// importando o vuex
import { createStore } from "vuex";

const store = createStore({
    state: {
        urlBaseApi: "http://localhost:8000/api/v1",
        item: {},
        itensRelacionais: [],
        transacao: {
            status: "",
            mensagem: "",
            dados: "",
        },
    },
});

/**
 * Next, we will create a fresh Vue application instance. You may then begin
 * registering components with the application instance so they are ready
 * to use in your application's views. An example is included for you.
 */

const app = createApp({ store });

app.use(store);

import ExampleComponent from "./components/ExampleComponent.vue";
app.component("example-component", ExampleComponent);

import Login from "./components/Login.vue";
app.component("login-component", Login);

import Home from "./components/Home.vue";
app.component("home-component", Home);

import Marcas from "./components/Marcas.vue";
app.component("marcas-component", Marcas);

import InputContainer from "./components/generals/InputContainer.vue";
app.component("input-container-component", InputContainer);

import Table from "./components/generals/Table.vue";
app.component("table-component", Table);

import TableRelacional from "./components/generals/TableRelacional.vue";
app.component("table-relacional-component", TableRelacional);

import Card from "./components/generals/Card.vue";
app.component("card-component", Card);

import Modal from "./components/generals/Modal.vue";
app.component("modal-component", Modal);

import Alert from "./components/generals/Alert.vue";
app.component("alert-component", Alert);

import Paginate from "./components/generals/Paginate.vue";
app.component("paginate-component", Paginate);

import Modelos from "./components/Modelos.vue";
app.component("modelos-component", Modelos);

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-componenst></example-component>
 */

// Object.entries(import.meta.glob('./**/*.vue', { eager: true })).forEach(([path, definition]) => {
//     app.component(path.split('/').pop().replace(/\.\w+$/, ''), definition.default);
// });

/**
 * Finally, we will attach the application instance to a HTML element with
 * an "id" attribute of "app". This element is included with the "auth"
 * scaffolding. Otherwise, you will need to add an element yourself.
 */

app.config.globalProperties.$filters = {
    // filtros globais dentro do vue 3
    formataDataTempo(d) {
        if (!d) return "";

        d = d.split("T");

        let data = d[0];
        let tempo = d[1];

        // formatando a data
        data = data.split("-");
        data = data[2] + "/" + data[1] + "/" + data[0];

        // formatando o tempo
        tempo = tempo.split(".");
        tempo = tempo[0];

        return data + " " + tempo;
    },
};

app.mount("#app");
