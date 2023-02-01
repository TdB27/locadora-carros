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

import ExampleComponent from "./ExampleComponent.vue";
app.component("example-component", ExampleComponent);

import Login from "./pages/Login.vue";
app.component("login-component", Login);

import Home from "./pages/Home.vue";
app.component("home-component", Home);

import Marcas from "./pages/Marcas.vue";
app.component("marcas-component", Marcas);

import InputContainer from "./components/InputContainer.vue";
app.component("input-container-component", InputContainer);

import Table from "./components/Table.vue";
app.component("table-component", Table);

import TableRelacional from "./components/TableRelacional.vue";
app.component("table-relacional-component", TableRelacional);

import Card from "./components/Card.vue";
app.component("card-component", Card);

import Modal from "./components/Modal.vue";
app.component("modal-component", Modal);

import Alert from "./components/Alert.vue";
app.component("alert-component", Alert);

import Paginate from "./components/Paginate.vue";
app.component("paginate-component", Paginate);

import Modelos from "./pages/Modelos.vue";
app.component("modelos-component", Modelos);

import Carros from "./pages/Carros.vue";
app.component("carros-component", Carros);

import Clientes from "./pages/Clientes.vue";
app.component("clientes-component", Clientes);

import Locacoes from "./pages/Locacoes.vue";
app.component("locacoes-component", Locacoes);

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
    formataDataTempo(date, separator) {
        if (!date) return "";

        let separatorVal;
        separator ? (separatorVal = separator) : (separatorVal = "T");

        date = date.split(separatorVal);

        let data = date[0];
        let tempo = date[1];

        // formatando a data
        data = data.split("-");
        data = data[2] + "/" + data[1] + "/" + data[0];

        // formatando o tempo
        tempo = tempo.split(".");

        return data;
    },

    dadosTabelaRelacional(tabela, id, campo) {
        let dado = {};
        tabela.forEach((i) => {
            if (i.id == id) dado = i;
        });

        return dado[campo];
    },

    translatePaginate(label) {
        let arr = label.split(" ");
        let filter = arr.map((item, index) => {
            if (item == "Previous") item = "Anterior";
            if (item == "Next") item = "Proximo";

            return item;
        });

        return filter.join(" ");
    },
};

app.mount("#app");
