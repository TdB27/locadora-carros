<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <!-- inicio do card de busca -->
                <card-component titulo="Busca de Marcas">
                    <template v-slot:conteudo>
                        <div class="row">
                            <div class="col mb-3">
                                <input-container-component
                                    titulo="ID"
                                    id="inputId"
                                    id-help="idHelp"
                                    texto-ajuda="Opcional. Informe o ID do registro"
                                >
                                    <input
                                        type="number"
                                        class="form-control"
                                        id="inputId"
                                        aria-describedby="idHelp"
                                        placeholder="ID"
                                        v-model="busca.id"
                                    />
                                </input-container-component>
                            </div>

                            <div class="col mb-3">
                                <input-container-component
                                    titulo="Nome do Modelo"
                                    id="inputNome"
                                    id-help="NomeHelp"
                                    texto-ajuda="Opcional. Informe o Nome do modelo"
                                >
                                    <input
                                        type="text"
                                        class="form-control"
                                        id="inputNome"
                                        aria-describedby="NomeHelp"
                                        placeholder="Nome do modelo"
                                        v-model="busca.nome"
                                    />
                                </input-container-component>
                            </div>
                        </div>
                    </template>

                    <template v-slot:rodape>
                        <button
                            type="submit"
                            class="btn btn-secondary btn-sm float-end"
                        >
                            Pesquisar
                        </button>
                    </template>
                </card-component>
                <!-- fim do card de busca -->

                <!-- inicio do card de modelos -->
                <card-component titulo="Relação de Modelos">
                    <template v-slot:conteudo>
                        <table-component
                            :dados="modelos.data"
                            :dados-relacionais="{
                                dados: modelos.data,
                                campoRelacional: ['marca'],
                                idRelacional: 'marca_id',
                            }"
                            :visualizar="{
                                visivel: true,
                                dataToggle: 'modal',
                                dataTarget: '#modalVisualizarModelo',
                            }"
                            :atualizar="{
                                visivel: true,
                                dataToggle: 'modal',
                                dataTarget: '#modalAtualizarModelo',
                            }"
                            :remover="{
                                visivel: true,
                                dataToggle: 'modal',
                                dataTarget: '#modalRemoverModelo',
                            }"
                            :titulos="{
                                id: { titulo: 'ID', tipo: 'text' },
                                nome: { titulo: 'Nome', tipo: 'text' },
                                imagem: { titulo: 'Imagem', tipo: 'imagem' },
                                numero_portas: {
                                    titulo: 'Numero Portas',
                                    tipo: 'text',
                                },
                                lugares: { titulo: 'lugares', tipo: 'text' },
                                air_bag: { titulo: 'Air Bag', tipo: 'boolean' },
                                abs: { titulo: 'ABS', tipo: 'boolean' },
                                created_at: {
                                    titulo: 'Data de criação',
                                    tipo: 'data',
                                },
                            }"
                        ></table-component>
                    </template>

                    <template v-slot:rodape>
                        <div class="row">
                            <div class="col-10">
                                <paginate-component>
                                    <li
                                        v-for="(l, key) in modelos.links"
                                        :key="key"
                                        :class="
                                            l.active
                                                ? 'page-item active'
                                                : 'page-item'
                                        "
                                        @click.prevent="paginacao(l)"
                                    >
                                        <a class="page-link" v-html="l.label">
                                        </a>
                                    </li>
                                </paginate-component>
                            </div>
                            <div class="col-2">
                                <button
                                    type="button"
                                    class="btn btn-secondary btn-sm float-end"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalModelo"
                                >
                                    Adicionar
                                </button>
                            </div>
                        </div>
                    </template>
                </card-component>
                <!-- fim do card de modelos -->
            </div>
        </div>

        <!-- inicio do Modal de inclusão de marca -->
        <modal-component id="modalModelo" titulo="Adicionar Modelo">
            <template v-slot:alertas>
                <alert-component
                    tipo="success"
                    :detalhes="transacaoDetalhes"
                    titulo="Cadastro realizado com sucesso"
                    v-if="transacaoStatus == 'add'"
                >
                </alert-component>
                <alert-component
                    tipo="danger"
                    :detalhes="transacaoDetalhes"
                    titulo="Erro ao tentar salvar a marca"
                    v-if="transacaoStatus == 'erro'"
                >
                </alert-component>
            </template>

            <template v-slot:conteudo>
                <div class="mb-3">
                    <input-container-component
                        titulo="Marcas"
                        id="novaMarca"
                        id-help="novaMarca"
                        texto-ajuda="Informe a marca do modelo"
                    >
                        <select
                            class="form-select"
                            v-model="form.marca_id"
                            aria-label="novaMarca"
                        >
                            <option
                                v-for="m in marcas"
                                :key="m.id"
                                :value="m.id"
                            >
                                {{ m.nome }}
                            </option>
                        </select>
                    </input-container-component>
                </div>

                <div class="mb-3">
                    <input-container-component
                        titulo="Nome da Marca"
                        id="novoNome"
                        id-help="novoNomeHelp"
                        texto-ajuda="Informe o Nome do Modelo"
                    >
                        <input
                            type="text"
                            class="form-control"
                            id="novoNome"
                            aria-describedby="novoNomeHelp"
                            placeholder="Nome do Modelo"
                            v-model="form.nome"
                        />
                    </input-container-component>
                </div>

                <div class="mb-3">
                    <input-container-component
                        titulo="Image"
                        id="novaImagem"
                        id-help="novaImagemHelp"
                        texto-ajuda="Selecione uma imagem no formato PNG"
                    >
                        <input
                            type="file"
                            class="form-control"
                            id="novaImagem"
                            aria-describedby="novaImagemHelp"
                            placeholder="Selecione uma imagem"
                            @change="carregarImagem($event)"
                        />
                    </input-container-component>
                </div>

                <div class="mb-3 col-6">
                    <input-container-component
                        titulo="Numero de Portas"
                        id="novaNumeroPortas"
                        id-help="novaNumeroPortas"
                        texto-ajuda="Informe o Numero de Portas"
                    >
                        <input
                            type="number"
                            class="form-control"
                            id="novaNumeroPortas"
                            aria-describedby="novaNumeroPortas"
                            placeholder="Numero de Portas"
                            v-model="form.numero_portas"
                        />
                    </input-container-component>
                </div>

                <div class="mb-3 col-6">
                    <input-container-component
                        titulo="Lugares"
                        id="novoLugares"
                        id-help="novoLugares"
                        texto-ajuda="Informe a quantidade de lugares"
                    >
                        <input
                            type="number"
                            class="form-control"
                            id="novoLugares"
                            aria-describedby="novoLugares"
                            placeholder="Lugares"
                            v-model="form.lugares"
                        />
                    </input-container-component>
                </div>

                <div class="col-6">
                    <input-container-component
                        titulo="Air Bag"
                        id="novoAirBag"
                        id-help="novoAirBag"
                        texto-ajuda="Informe se há Air Bag"
                    >
                        <select
                            class="form-select"
                            v-model="form.air_bag"
                            aria-label="novoAirBag"
                        >
                            <option value="1">Sim</option>
                            <option value="0">Não</option>
                        </select>
                    </input-container-component>
                </div>

                <div class="col-6">
                    <input-container-component
                        titulo="ABS"
                        id="novoAbs"
                        id-help="novoAbs"
                        texto-ajuda="Informe se há ABS"
                    >
                        <select
                            class="form-select"
                            v-model="form.abs"
                            aria-label="novoAbs"
                        >
                            <option value="1">Sim</option>
                            <option value="0">Não</option>
                        </select>
                    </input-container-component>
                </div>
            </template>

            <template v-slot:rodape>
                <button
                    type="button"
                    class="btn btn-secondary"
                    data-bs-dismiss="modal"
                >
                    Fechar
                </button>
                <button type="button" class="btn btn-primary" @click="salvar()">
                    Salvar
                </button>
            </template>
        </modal-component>
        <!-- fim do Modal de inclusão de marca -->
    </div>
</template>

<script>
import axios from "axios";

export default {
    props: ["marcas"],
    data() {
        return {
            urlBase: this.$store.state.urlBaseApi + "/modelo",
            urlFiltro: "",
            urlPaginacao: "",
            busca: {},
            modelos: { data: [] },
            form: {},
            transacaoDetalhes: {},
            transacaoStatus: "",
        };
    },
    mounted() {
        this.carregarLista();
    },
    methods: {
        carregarLista() {
            let url = this.urlBase + "?" + this.urlPaginacao;

            axios.get(url).then((response) => {
                this.modelos = response.data;
            });
        },
        carregarImagem(event) {
            this.form.imagem = event.target.files;
        },
        salvar() {
            let formData = new FormData();
            formData.append("marca_id", this.form.marca_id);
            formData.append("nome", this.form.nome);
            formData.append("imagem", this.form.imagem[0]);
            formData.append("numero_portas", this.form.numero_portas);
            formData.append("lugares", this.form.lugares);
            formData.append("air_bag", this.form.air_bag);
            formData.append("abs", this.form.abs);

            let config = {
                headers: {
                    "Content-Type": "multipart/form-data",
                },
            };

            axios
                .post(this.urlBase, formData, config)
                .then((response) => {
                    this.transacaoStatus = "add";
                    this.carregarLista();
                })
                .catch((error) => {
                    this.transacaoDetalhes.mensagem =
                        error.response.data.message;
                    this.transacaoDetalhes.dados = error.response.data.errors;
                    this.transacaoStatus = "erro";
                });
        },
        paginacao(l) {
            if (l.url) {
                this.urlPaginacao = l.url.split("?")[1];
                this.carregarLista();
            }
        },
    },
};
</script>
