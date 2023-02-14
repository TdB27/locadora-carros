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

                <!-- inicio do card de carros -->
                <card-component
                    titulo="Relação de Carros vinculados a um Modelo"
                >
                    <template v-slot:conteudo>
                        <table-component
                            :dados="carros.data"
                            :dados-relacionais="{
                                dados: carros.data,
                                campoRelacional: ['locacoes'],
                                idRelacional: 'carro_id',
                                tabela: modelos,
                            }"
                            :menu="{
                                visualizar: {
                                    visivel: true,
                                    dataToggle: 'modal',
                                    dataTarget: '#modalVisualizarCarro',
                                },
                                atualizar: {
                                    visivel: true,
                                    dataToggle: 'modal',
                                    dataTarget: '#modalAtualizarCarro',
                                },
                                remover: {
                                    visivel: true,
                                    dataToggle: 'modal',
                                    dataTarget: '#modalRemoverCarro',
                                },
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
                                    data-bs-target="#modalCadastroCarro"
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

        <!-- inicio do Modal de inclusão do carro -->
        <modal-component
            id="modalCadastroCarro"
            titulo="Vincular Carro a um Modelo"
        >
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
                <button type="button" class="btn btn-primary" @click="salvar()">
                    Salvar
                </button>
            </template>
        </modal-component>
        <!-- fim do Modal de inclusão do carro -->

        <!-- inicio do Modal de visualização do carro -->
        <modal-component
            id="modalVisualizarCarro"
            :titulo="'Visualizar Carro ' + $store.state.item.id"
        >
            <template v-slot:conteudo>
                <input-container-component
                    titulo="Modelo"
                    id="visualizarModelo"
                    id-help=""
                    texto-ajuda=""
                >
                    <input
                        type="text"
                        class="form-control"
                        id="visualizarModelo"
                        :value="
                            $filters.dadosTabelaRelacional(
                                modelos,
                                $store.state.item.modelo_id,
                                'nome'
                            )
                        "
                        disabled
                    />
                </input-container-component>

                <input-container-component
                    titulo="Número da Placa do Carro"
                    id="visualizarPlaca"
                    id-help=""
                    texto-ajuda=""
                >
                    <input
                        type="text"
                        class="form-control"
                        id="visualizarPlaca"
                        :value="$store.state.item.placa"
                        disabled
                    />
                </input-container-component>

                <div class="col-6">
                    <input-container-component
                        titulo="Está Disponível?"
                        id="visualizarDisponivel"
                        id-help=""
                        texto-ajuda=""
                    >
                        <input
                            type="text"
                            class="form-control"
                            id="visualizarDisponivel"
                            :value="
                                $store.state.item.disponivel == 0
                                    ? 'Não'
                                    : 'Sim'
                            "
                            disabled
                        />
                    </input-container-component>
                </div>

                <div class="col-6">
                    <input-container-component
                        titulo="Km"
                        id="visualizarKm"
                        id-help=""
                        texto-ajuda=""
                    >
                        <input
                            type="text"
                            class="form-control"
                            id="visualizarKm"
                            :value="$store.state.item.km"
                            disabled
                        />
                    </input-container-component>
                </div>
            </template>
        </modal-component>
        <!-- fim do Modal de visualização do carro -->

        <!-- inicio do Modal de atualização do carro -->
        <modal-component id="modalAtualizarCarro" titulo="Atualizar Carro">
            <template v-slot:alertas>
                <alert-component
                    tipo="success"
                    titulo="Cadastro realizado com sucesso"
                    :detalhes="transacaoDetalhes"
                    v-if="$store.state.transacao.status == 'sucesso'"
                ></alert-component>
                <alert-component
                    tipo="danger"
                    titulo="Erro ao tentar salvar a marca"
                    :detalhes="transacaoDetalhes"
                    v-if="$store.state.transacao.status == 'erro'"
                ></alert-component>
            </template>

            <template v-slot:conteudo>
                <input-container-component
                    titulo="Modelo"
                    id="atualizarModelo"
                    id-help="atualizarModeloHelp"
                    texto-ajuda="Informe o Modelo do Carro"
                >
                    <select
                        class="form-select"
                        v-model="$store.state.item.modelo_id"
                        aria-label="atualizarModelo"
                    >
                        <option v-for="m in modelos" :key="m.id" :value="m.id">
                            {{ m.nome }}
                        </option>
                    </select>
                </input-container-component>

                <input-container-component
                    titulo="Nome da Placa do Carro"
                    id="atualizarPlaca"
                    id-help="atualizarPlacaHelp"
                    texto-ajuda="Informe o Placa do Carro"
                >
                    <input
                        type="text"
                        class="form-control"
                        id="atualizarPlaca"
                        aria-describedby="atualizarPlacaHelp"
                        placeholder="Placa do Carro"
                        v-model="$store.state.item.placa"
                    />
                </input-container-component>

                <div class="col-6">
                    <input-container-component
                        titulo="Está Disponível?"
                        id="atualizarDisponivel"
                        id-help="atualizarDisponivelHelp"
                        texto-ajuda="Informe se o carro está disponível"
                    >
                        <select
                            class="form-select"
                            v-model="$store.state.item.disponivel"
                            aria-label="atualizarDisponivelHelp"
                        >
                            <option value="1">Sim</option>
                            <option value="0">Não</option>
                        </select>
                    </input-container-component>
                </div>

                <div class="col-6">
                    <input-container-component
                        titulo="Km"
                        id="atualizarKm"
                        id-help="atualizarKmHelp"
                        texto-ajuda="Informe o KM do Carro"
                    >
                        <input
                            type="text"
                            class="form-control"
                            id="atualizarKm"
                            aria-describedby="atualizarKmHelp"
                            placeholder="Km"
                            v-model="$store.state.item.km"
                        />
                    </input-container-component>
                </div>
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
        <!-- fim do Modal de atualização do carro -->

        <!-- inicio do Modal de remoção do carro -->
        <modal-component
            id="modalRemoverCarro"
            :titulo="'Remover Carro ' + $store.state.item.id"
        >
            <template v-slot:alertas>
                <alert-component
                    tipo="success"
                    titulo="Cadastro realizado com sucesso"
                    :detalhes="$store.state.transacao"
                    v-if="$store.state.transacao.status == 'add'"
                ></alert-component>
                <alert-component
                    tipo="danger"
                    titulo="Erro ao tentar salvar a marca"
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
                    titulo="Modelo"
                    id="removerModelo"
                    id-help=""
                    texto-ajuda=""
                >
                    <input
                        type="text"
                        class="form-control"
                        id="removerModelo"
                        :value="
                            $filters.dadosTabelaRelacional(
                                modelos,
                                $store.state.item.modelo_id,
                                'nome'
                            )
                        "
                        disabled
                    />
                </input-container-component>

                <input-container-component
                    titulo="Número da Placa do Carro"
                    id="removerPlaca"
                    id-help=""
                    texto-ajuda=""
                >
                    <input
                        type="text"
                        class="form-control"
                        id="removerPlaca"
                        :value="$store.state.item.placa"
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
        <!-- fim do Modal de remoção do carro -->

        <!-- inicio do Modal de filtros do carro -->
        <modal-component id="modalFiltros" titulo="Filtrar Carro">
            <template v-slot:conteudo>
                <input-container-component
                    titulo="Modelo"
                    id="novoModelo"
                    id-help="novoModeloHelp"
                    texto-ajuda="Opcional. Informe o Modelo do Carro"
                >
                    <select
                        class="form-select"
                        v-model="busca.modelo_id"
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
                    texto-ajuda="Opcional. Informe o Placa do Carro"
                >
                    <input
                        type="text"
                        class="form-control"
                        id="novaPlaca"
                        aria-describedby="novaPlacaHelp"
                        placeholder="Placa do Carro"
                        v-model="busca.placa"
                    />
                </input-container-component>

                <div class="col-6">
                    <input-container-component
                        titulo="Está Disponível?"
                        id="novoDisponivel"
                        id-help="novoDisponivelHelp"
                        texto-ajuda="Opcional. Informe se o carro está disponível"
                    >
                        <select
                            class="form-select"
                            v-model="busca.disponivel"
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
                        texto-ajuda="Opcional. Informe o KM do Carro"
                    >
                        <input
                            type="text"
                            class="form-control"
                            id="novoKm"
                            aria-describedby="novoKmHelp"
                            placeholder="Km"
                            v-model="busca.km"
                        />
                    </input-container-component>
                </div>
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
        <!-- fim do Modal de filtros do carro -->
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
        atualizar() {
            this.transacaoDetalhes = {};
            let url = this.urlBase + "/" + this.$store.state.item.id;
            this.$store.state.item._method = "patch";

            axios
                .post(url, this.$store.state.item)
                .then((response) => {
                    this.$store.state.transacao.status = "sucesso";
                    this.transacaoDetalhes.mensagem =
                        "Registro de marca atualizado com sucesso!";
                    this.carregarLista();
                })
                .catch((errors) => {
                    this.$store.state.transacao.status = "erro";
                    this.transacaoDetalhes = {
                        mensagem: errors.response.data.message,
                        dados: errors.response.data.errors,
                    };
                });
        },
        remover() {
            this.transacaoDetalhes = {};
            let url = this.urlBase + "/" + this.$store.state.item.id;
            let form = {
                _method: "delete",
            };

            axios
                .post(url, form)
                .then((response) => {
                    this.$store.state.transacao.status = "add";
                    this.$store.state.transacao.mensagem =
                        "Registro de removido com sucesso!";
                    this.carregarLista();
                })
                .catch((errors) => {
                    this.$store.state.transacao.status = "erro";
                    this.$store.state.transacao.mensagem =
                        errors.response.data.erro;
                });
        },
        configDefault() {
            this.form = {};
            this.transacaoStatus = "";
            this.transacaoDetalhes = {};
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
