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

                <!-- inicio do card de clientes -->
                <card-component titulo="Relação de Clientes">
                    <template v-slot:conteudo>
                        <table-component
                            :dados="clientes.data"
                            :dados-relacionais="{
                                dados: clientes.data,
                                campoRelacional: ['locacoes'],
                                idRelacional: 'cliente_id',
                            }"
                            :menu="{
                                visualizar: {
                                    visivel: true,
                                    dataToggle: 'modal',
                                    dataTarget: '#modalVisualizarCliente',
                                },
                                atualizar: {
                                    visivel: true,
                                    dataToggle: 'modal',
                                    dataTarget: '#modalAtualizarCliente',
                                },
                                remover: {
                                    visivel: true,
                                    dataToggle: 'modal',
                                    dataTarget: '#modalRemoverCliente',
                                },
                            }"
                            :titulos="{
                                id: { titulo: 'ID', tipo: 'text' },
                                nome: { titulo: 'Nome', tipo: 'text' },
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
                                        v-for="(l, key) in clientes.links"
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
                                    data-bs-target="#modalCadastroCliente"
                                    @click="configDefault()"
                                >
                                    Adicionar
                                </button>
                            </div>
                        </div>
                    </template>
                </card-component>
                <!-- fim do card de carros -->
            </div>
        </div>

        <!-- inicio do Modal de cadastro do cliente -->
        <modal-component id="modalCadastroCliente" titulo="Cadastrar Cliente">
            <template v-slot:alertas>
                <alert-component
                    tipo="success"
                    titulo="Cadastro realizado com sucesso"
                    :detalhes="transacaoDetalhes"
                    v-if="transacaoStatus == 'add'"
                ></alert-component>
                <alert-component
                    tipo="danger"
                    titulo="Erro ao tentar salvar o cliente"
                    :detalhes="transacaoDetalhes"
                    v-if="transacaoStatus == 'erro'"
                ></alert-component>
            </template>

            <template v-slot:conteudo>
                <input-container-component
                    titulo="Nome do Cliente"
                    id="novoCliente"
                    id-help="novoClienteHelp"
                    texto-ajuda="Informe o Nome do Cliente"
                >
                    <input
                        type="text"
                        class="form-control"
                        id="novoCliente"
                        aria-describedby="novoClienteHelp"
                        placeholder="Nome do Cliente"
                        v-model="form.nome"
                    />
                </input-container-component>
            </template>

            <template v-slot:rodape>
                <button type="button" class="btn btn-primary" @click="salvar()">
                    Salvar
                </button>
            </template>
        </modal-component>
        <!-- fim do Modal de cadastro do cliente -->

        <!-- inicio do Modal de visualização do cliente -->
        <modal-component
            id="modalVisualizarCliente"
            :titulo="'Visualizar Cliente ' + $store.state.item.id"
        >
            <template v-slot:conteudo>
                <input-container-component
                    titulo="Nome do Cliente"
                    id="visualizarCliente"
                    id-help=""
                    texto-ajuda=""
                >
                    <input
                        type="text"
                        class="form-control"
                        id="visualizarCliente"
                        aria-describedby=""
                        :value="$store.state.item.nome"
                        disabled
                    />
                </input-container-component>
            </template>
        </modal-component>
        <!-- fim do Modal de visualização do cliente -->

        <!-- inicio do Modal de atualizacao do cliente -->
        <modal-component
            id="modalAtualizarCliente"
            :titulo="'Atualizar Cliente ' + $store.state.item.id"
        >
            <template v-slot:alertas>
                <alert-component
                    tipo="success"
                    titulo="Cadastro atualizado com sucesso"
                    :detalhes="$store.state.transacao"
                    v-if="$store.state.transacao.status == 'sucesso'"
                ></alert-component>
                <alert-component
                    tipo="danger"
                    titulo="Erro ao tentar atualizar o cliente"
                    :detalhes="$store.state.transacao"
                    v-if="$store.state.transacao.status == 'erro'"
                ></alert-component>
            </template>

            <template v-slot:conteudo>
                <input-container-component
                    titulo="Nome do Cliente"
                    id="atualizarCliente"
                    id-help="atualizarClienteHelp"
                    texto-ajuda="Informe o Nome do Cliente"
                >
                    <input
                        type="text"
                        class="form-control"
                        id="atualizarCliente"
                        aria-describedby="atualizarClienteHelp"
                        placeholder="Nome do Cliente"
                        v-model="$store.state.item.nome"
                    />
                </input-container-component>
            </template>

            <template v-slot:rodape>
                <button
                    type="button"
                    class="btn btn-primary"
                    @click="atualizar()"
                >
                    Atualizar
                </button>
            </template>
        </modal-component>
        <!-- fim do Modal de atualizacao do cliente -->

        <!-- inicio do Modal de remoção do cliente -->
        <modal-component
            id="modalRemoverCliente"
            :titulo="'Remover Cliente ' + $store.state.item.id"
        >
            <template v-slot:alertas>
                <alert-component
                    tipo="success"
                    titulo="Cliente removido com sucesso"
                    :detalhes="$store.state.transacao"
                    v-if="$store.state.transacao.status == 'sucesso'"
                ></alert-component>
                <alert-component
                    tipo="danger"
                    titulo="Erro ao tentar remover o cliente"
                    :detalhes="$store.state.transacao"
                    v-if="$store.state.transacao.status == 'erro'"
                ></alert-component>
                <alert-component
                    tipo="danger"
                    titulo="Erro na transação"
                    :detalhes="{
                        mensagem: 'Há locações vinculados a esse carro',
                    }"
                    v-if="$store.state.itensRelacionais.length > 0"
                >
                </alert-component>
            </template>

            <template v-slot:conteudo>
                <input-container-component
                    titulo="Nome do Cliente"
                    id="novoCliente"
                    id-help=""
                    texto-ajuda=""
                >
                    <input
                        type="text"
                        class="form-control"
                        id="novoCliente"
                        :value="$store.state.item.nome"
                        disabled
                    />
                </input-container-component>
            </template>

            <template v-slot:rodape>
                <button
                    type="button"
                    class="btn btn-danger"
                    @click="remover()"
                    v-if="$store.state.itensRelacionais.length <= 0"
                >
                    Remover
                </button>
            </template>
        </modal-component>
        <!-- fim do Modal de remoção do cliente -->

        <!-- inicio do Modal de filtro do cliente -->
        <modal-component id="modalFiltros" titulo="Filtrar Cliente">
            <template v-slot:conteudo>
                <input-container-component
                    titulo="Nome do Cliente"
                    id="buscaCliente"
                    id-help="buscaClienteHelp"
                    texto-ajuda="Informe o Nome do Cliente"
                >
                    <input
                        type="text"
                        class="form-control"
                        id="buscaCliente"
                        aria-describedby="buscaClienteHelp"
                        placeholder="Nome do Cliente"
                        v-model="busca.nome"
                    />
                </input-container-component>
            </template>

            <template v-slot:rodape>
                <button
                    type="button"
                    class="btn btn-primary"
                    @click="filtrar()"
                >
                    Filtrar
                </button>
            </template>
        </modal-component>
        <!-- fim do Modal de filtro do cliente -->
    </div>
</template>

<script>
export default {
    data() {
        return {
            urlBase: this.$store.state.urlBaseApi + "/cliente",
            urlFiltro: "",
            urlPaginacao: "",
            clientes: { data: [] },
            busca: {},
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
            let url = this.urlBase + "?" + this.urlPaginacao + this.urlFiltro;

            axios
                .get(url)
                .then((response) => {
                    this.clientes = response.data;
                })
                .catch((errors) => {
                    console.log(errros.response);
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
                });
        },
        configDefault() {
            this.form = {};
            this.transacaoStatus = "";
            this.transacaoDetalhes = {};
        },
        atualizar() {
            let url = this.urlBase + "/" + this.$store.state.item.id;

            this.$store.state.item._method = "patch";

            axios
                .post(url, this.$store.state.item)
                .then((response) => {
                    this.$store.state.transacao.status = "sucesso";
                    this.$store.state.transacao.mensagem =
                        "Registro de cliente atualizado com sucesso!";
                    this.carregarLista();
                })
                .catch((errors) => {
                    this.$store.state.transacao.status = "erro";
                    this.$store.state.transacao.mensagem =
                        errors.response.data.message;
                    this.$store.state.transacao.dados =
                        errors.response.data.errors;
                });
        },
        remover() {
            let url = this.urlBase + "/" + this.$store.state.item.id;
            let form = {
                _method: "delete",
            };

            axios
                .post(url, form)
                .then((response) => {
                    this.$store.state.transacao.status = "sucesso";
                    this.$store.state.transacao.mensagem =
                        "Registro de cliente removido com sucesso!";
                    this.carregarLista();
                })
                .catch((errors) => {
                    this.$store.state.transacao.status = "erro";
                    this.$store.state.transacao.mensagem =
                        errors.response.data.erro;
                });
        },
        filtrar() {
            let filtro = "";

            for (let chave in this.busca) {
                let operatorSign = ":like:";
                let percentSign = "%";

                if (this.busca[chave]) {
                    if (filtro != "") filtro += ";";

                    if (chave == "id" || chave == "modelo_id") {
                        operatorSign = ":=:";
                        percentSign = "";
                    }

                    filtro +=
                        chave +
                        operatorSign +
                        percentSign +
                        this.busca[chave] +
                        percentSign;
                }
            }

            if (filtro != "") {
                this.urlPaginacao = "page=1";
                this.urlFiltro = "&filtro=" + filtro;
            } else {
                this.urlFiltro = "";
            }

            this.carregarLista();
        },
    },
};
</script>
