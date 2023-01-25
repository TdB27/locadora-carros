<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <!-- inicio do button de busca -->
                <button
                    type="button"
                    class="btn btn-secondary btn-sm mb-3"
                    data-bs-toggle="modal"
                    data-bs-target="#modalFiltros"
                    @click="busca = {}"
                >
                    Filtros
                </button>
                <!-- fim do button de busca -->

                <!-- inicio do card de marcas -->
                <card-component
                    titulo="Relação de Carros vinculados a um Modelo"
                >
                    <template v-slot:conteudo>
                        <table-component
                            :dados="carros.data"
                            :dados-relacionais="{
                                dados: carros.data,
                                campoRelacional: null,
                                idRelacional: null,
                                tabela: modelos,
                            }"
                            :visualizar="{
                                visivel: true,
                                dataToggle: 'modal',
                                dataTarget: '#modalVisualizarCarro',
                            }"
                            :atualizar="{
                                visivel: true,
                                dataToggle: 'modal',
                                dataTarget: '#modalAtualizarCarro',
                            }"
                            :remover="{
                                visivel: true,
                                dataToggle: 'modal',
                                dataTarget: '#modalRemoverCarro',
                            }"
                            :titulos="{
                                id: { titulo: 'ID', tipo: 'text' },
                                modelo_id: {
                                    titulo: 'Modelo',
                                    tipo: 'relational',
                                    campo: 'nome',
                                },
                                placa: { titulo: 'Placa', tipo: 'text' },
                                disponivel: {
                                    titulo: 'Disponível',
                                    tipo: 'boolean',
                                },
                                km: { titulo: 'Km', tipo: 'text' },
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
                                        v-for="(l, key) in carros.links"
                                        :key="key"
                                        :class="
                                            l.active
                                                ? 'page-item active'
                                                : 'page-item'
                                        "
                                        @click.prevent="paginacao(l)"
                                    >
                                        <a
                                            class="page-link"
                                            v-html="
                                                $filters.translatePaginate(
                                                    l.label
                                                )
                                            "
                                        >
                                        </a>
                                    </li>
                                </paginate-component>
                            </div>
                            <div class="col-2">
                                <button
                                    type="button"
                                    class="btn btn-secondary btn-sm float-end"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalCarro"
                                >
                                    Adicionar
                                </button>
                            </div>
                        </div>
                    </template>
                </card-component>
                <!-- fim do card de marcas -->
            </div>
        </div>

        <!-- inicio do Modal de inclusão de marca -->
        <modal-component id="modalCarro" titulo="Vincular Carro a um Modelo">
            <template v-slot:alertas>
                <alert-component
                    tipo="success"
                    titulo="Cadastro realizado com sucesso"
                    :detalhes="transacaoDetalhes"
                    v-if="transacaoStatus == 'add'"
                ></alert-component>
                <alert-component
                    tipo="danger"
                    titulo="Erro ao tentar salvar a marca"
                    :detalhes="transacaoDetalhes"
                    v-if="transacaoStatus == 'erro'"
                ></alert-component>
            </template>

            <template v-slot:conteudo>
                <input-container-component
                    titulo="Modelo"
                    id="novoModelo"
                    id-help="novoModeloHelp"
                    texto-ajuda="Informe o Modelo do Carro"
                >
                    <select
                        class="form-select"
                        v-model="form.modelo_id"
                        aria-label="novoModelo"
                    >
                        <option v-for="m in modelos" :key="m.id" :value="m.id">
                            {{ m.nome }}
                        </option>
                    </select>
                </input-container-component>

                <input-container-component
                    titulo="Nome da Placa do Carro"
                    id="novaPlaca"
                    id-help="novaPlacaHelp"
                    texto-ajuda="Informe o Placa do Carro"
                >
                    <input
                        type="text"
                        class="form-control"
                        id="novaPlaca"
                        aria-describedby="novaPlacaHelp"
                        placeholder="Placa do Carro"
                        v-model="form.placa"
                    />
                </input-container-component>

                <div class="col-6">
                    <input-container-component
                        titulo="Está Disponível?"
                        id="novoDisponivel"
                        id-help="novoDisponivelHelp"
                        texto-ajuda="Informe se o carro está disponível"
                    >
                        <select
                            class="form-select"
                            v-model="form.disponivel"
                            aria-label="novoDisponivelHelp"
                        >
                            <option value="1">Sim</option>
                            <option value="0">Não</option>
                        </select>
                    </input-container-component>
                </div>

                <div class="col-6">
                    <input-container-component
                        titulo="Km"
                        id="novoKm"
                        id-help="novoKmHelp"
                        texto-ajuda="Informe o KM do Carro"
                    >
                        <input
                            type="text"
                            class="form-control"
                            id="novoKm"
                            aria-describedby="novoKmHelp"
                            placeholder="Km"
                            v-model="form.km"
                        />
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
export default {
    props: ["modelos"],
    data() {
        return {
            urlBase: this.$store.state.urlBaseApi + "/carro",
            urlFiltro: "",
            urlPaginacao: "",

            carros: { data: [] },
            busca: {},
            form: {},
            transacaoStatus: "",
            transacaoDetalhes: {},
        };
    },
    mounted() {
        this.carregarLista();
    },
    methods: {
        carregarLista() {
            let url = this.urlBase + "?" + this.urlPaginacao + this.urlFiltro;

            axios
                .get(url)
                .then((response) => {
                    this.carros = response.data;
                })
                .catch((errors) => {
                    console.log(erros.response);
                });
        },
        paginacao(l) {
            if (l.url) {
                this.urlPaginacao = l.url.split("?")[1];
                this.carregarLista();
            }
        },
        salvar() {
            axios
                .post(this.urlBase, this.form)
                .then((response) => {
                    console.log(response);
                    this.transacaoDetalhes = {
                        mensagem: "ID do Registro: " + response.data.id,
                    };
                    this.transacaoStatus = "add";
                    this.carregarLista();
                })
                .catch((errors) => {
                    this.transacaoDetalhes = {
                        mensagem: errors.response.data.message,
                        dados: errors.response.data.errors,
                    };
                    this.transacaoStatus = "erro";
                    console.log(errors.response);
                    console.log(this.transacaoDetalhes);
                    console.log(this.transacaoStatus);
                });
        },
    },
};
</script>
