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

                <!-- inicio do card de locacoes -->
                <card-component titulo="Relação de Locações">
                    <template v-slot:conteudo>
                        <table-component
                            :dados="locacoes.data"
                            :dados-relacionais="{
                                dados: locacoes.data,
                                campoRelacional: null,
                                idRelacional: null,
                            }"
                            :visualizar="{
                                visivel: true,
                                dataToggle: 'modal',
                                dataTarget: '#modalVisualizarLocacoes',
                            }"
                            :atualizar="{
                                visivel: true,
                                dataToggle: 'modal',
                                dataTarget: '#modalAtualizarLocacoes',
                            }"
                            :remover="{
                                visivel: true,
                                dataToggle: 'modal',
                                dataTarget: '#modalRemoverLocacoes',
                            }"
                            :titulos="{
                                id: { titulo: 'ID', tipo: 'text' },
                                cliente_id: {
                                    tipo: 'withoutAppear',
                                },
                                carro_id: {
                                    tipo: 'withoutAppear',
                                },
                                cliente: {
                                    titulo: 'Cliente',
                                    tipo: 'obj',
                                    campo: 'nome',
                                },
                                carro: {
                                    titulo: 'Carro',
                                    tipo: 'obj',
                                    campo: 'placa',
                                },
                                data_inicio_periodo: {
                                    titulo: 'Data Inicio',
                                    tipo: 'data',
                                    separator: ' ',
                                },
                                data_final_previsto_periodo: {
                                    titulo: 'Data Final Previsto',
                                    tipo: 'data',
                                    separator: ' ',
                                },
                                data_final_realizado_periodo: {
                                    titulo: 'Data Final Realizado',
                                    tipo: 'data',
                                    separator: ' ',
                                },
                                valor_diaria: {
                                    titulo: 'Data Final Realizado',
                                    tipo: 'amount',
                                },
                                km_inicial: {
                                    titulo: 'Km Inicial',
                                    tipo: 'text',
                                },
                                km_final: { titulo: 'Km Final', tipo: 'text' },
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
                                        v-for="(l, key) in locacoes.links"
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
                                    data-bs-target="#modalCadastroLocacao"
                                    @click="configDefault()"
                                >
                                    Adicionar
                                </button>
                            </div>
                        </div>
                    </template>
                </card-component>
                <!-- fim do card de locacoes -->
            </div>
        </div>

        <!-- inicio do Modal de cadastro de locacoes -->
        <modal-component id="modalCadastroLocacao" titulo="Cadastrar Locação">
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
                    texto-ajuda="Informe o Cliente"
                >
                    <select
                        class="form-select"
                        v-model="form.cliente_id"
                        aria-label="novoModelo"
                    >
                        <option v-for="c in clientes" :key="c.id" :value="c.id">
                            {{ c.nome }}
                        </option>
                    </select>
                </input-container-component>

                <input-container-component
                    titulo="Carro / Placa"
                    id="novoCarro"
                    id-help="novoCarroHelp"
                    texto-ajuda="Informe o Carro desejado"
                >
                    <select
                        class="form-select"
                        v-model="form.carro_id"
                        aria-label="novoCarro"
                    >
                        <option v-for="c in carros" :key="c.id" :value="c.id">
                            {{ c.modelo.nome }} - {{ c.placa }}
                        </option>
                    </select>
                </input-container-component>

                <input-container-component
                    titulo="Data de Inicio"
                    id="novoDataInicio"
                    id-help="novoDataInicioHelp"
                    texto-ajuda="Informe a Data de Inicio do Período"
                >
                    <input
                        type="date"
                        class="form-control"
                        id="novoDataInicio"
                        v-model="form.data_inicio_periodo"
                    />
                </input-container-component>

                <input-container-component
                    titulo="Data Final Previsto"
                    id="novoDataFinalPrevista"
                    id-help="novoDataFinalPrevistaHelp"
                    texto-ajuda="Informe a Data Final Prevista do Período"
                >
                    <input
                        type="date"
                        class="form-control"
                        id="novoDataFinalPrevista"
                        v-model="form.data_final_previsto_periodo"
                    />
                </input-container-component>

                <input-container-component
                    titulo="Data Final Realizado"
                    id="novoDataFinalRealizado"
                    id-help="novoDataFinalRealizadoHelp"
                    texto-ajuda="Informe a Data Final Realizado do Período"
                >
                    <input
                        type="date"
                        class="form-control"
                        id="novoDataFinalRealizado"
                        v-model="form.data_final_realizado_periodo"
                    />
                </input-container-component>

                <input-container-component
                    titulo="Valor da Diária"
                    id="novoValorDiaria"
                    id-help="novoValorDiariaHelp"
                    texto-ajuda="Informe o Valor da Diária"
                >
                    <input
                        type="number"
                        class="form-control"
                        id="novoValorDiaria"
                        aria-describedby="novoValorDiariaHelp"
                        placeholder="Valor da Diária"
                        v-model="form.valor_diaria"
                    />
                </input-container-component>

                <input-container-component
                    titulo="Km Inicial"
                    id="novoKmInicial"
                    id-help="novoKmInicialHelp"
                    texto-ajuda="Informe o Km Inicial"
                >
                    <input
                        type="number"
                        class="form-control"
                        id="novoKmInicial"
                        aria-describedby="novoKmInicialHelp"
                        placeholder="Km Inicial"
                        v-model="form.km_inicial"
                    />
                </input-container-component>

                <input-container-component
                    titulo="Km Final"
                    id="novoKmFinal"
                    id-help="novoKmFinalHelp"
                    texto-ajuda="Informe o Km Inicial"
                >
                    <input
                        type="number"
                        class="form-control"
                        id="novoKmFinal"
                        aria-describedby="novoKmFinalHelp"
                        placeholder="Km Final"
                        v-model="form.km_final"
                    />
                </input-container-component>
            </template>

            <template v-slot:rodape>
                <button type="button" class="btn btn-primary" @click="salvar()">
                    Salvar
                </button>
            </template>
        </modal-component>
        <!-- fim do Modal de cadastro de locacoes -->

        <!-- inicio do Modal de visualização de locacoes -->
        <modal-component
            id="modalVisualizarLocacoes"
            titulo="Visualizar Locação"
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
                        :value="$store.state.item.cliente?.nome"
                        disabled
                    />
                </input-container-component>

                <input-container-component
                    titulo="Carro / Placa"
                    id="visualizarCarro"
                    id-help=""
                    texto-ajuda=""
                >
                    <input
                        type="text"
                        class="form-control"
                        id="visualizarCarro"
                        :value="
                            $store.state.item.carro?.modelo?.nome +
                            ' / ' +
                            $store.state.item.carro?.placa
                        "
                        disabled
                    />
                </input-container-component>

                <input-container-component
                    titulo="Data de Inicio"
                    id="visualizarDataInicio"
                    id-help=""
                    texto-ajuda=""
                >
                    <input
                        type="text"
                        class="form-control"
                        id="visualizarDataInicio"
                        :value="
                            $filters.formataDataTempo(
                                $store.state.item.data_inicio_periodo,
                                ' '
                            )
                        "
                        disabled
                    />
                </input-container-component>

                <input-container-component
                    titulo="Data Final Previsto"
                    id="visualizarDataFinalPrevista"
                    id-help=""
                    texto-ajuda=""
                >
                    <input
                        type="text"
                        class="form-control"
                        id="visualizarDataFinalPrevista"
                        :value="
                            $filters.formataDataTempo(
                                $store.state.item.data_final_previsto_periodo,
                                ' '
                            )
                        "
                        disabled
                    />
                </input-container-component>

                <input-container-component
                    titulo="Data Final Realizado"
                    id="visualizarDataFinalRealizado"
                    id-help=""
                    texto-ajuda=""
                >
                    <input
                        type="text"
                        class="form-control"
                        id="visualizarDataFinalRealizado"
                        :value="
                            $filters.formataDataTempo(
                                $store.state.item.data_final_realizado_periodo,
                                ' '
                            )
                        "
                        disabled
                    />
                </input-container-component>

                <input-container-component
                    titulo="Valor da Diária"
                    id="visualizarValorDiaria"
                    id-help=""
                    texto-ajuda=""
                >
                    <input
                        type="number"
                        class="form-control"
                        id="visualizarValorDiaria"
                        :value="$store.state.item.valor_diaria"
                        disabled
                    />
                </input-container-component>

                <input-container-component
                    titulo="Km Inicial"
                    id="visualizarKmInicial"
                    id-help=""
                    texto-ajuda=""
                >
                    <input
                        type="number"
                        class="form-control"
                        id="visualizarKmInicial"
                        :value="$store.state.item.km_inicial"
                        disabled
                    />
                </input-container-component>

                <input-container-component
                    titulo="Km Final"
                    id="visualizarKmFinal"
                    id-help=""
                    texto-ajuda=""
                >
                    <input
                        type="number"
                        class="form-control"
                        id="visualizarKmFinal"
                        :value="$store.state.item.km_final"
                        disabled
                    />
                </input-container-component>
            </template>
            -->
        </modal-component>
        <!-- fim do Modal de visualização de locacoes -->

        <!-- inicio do Modal de atualização de locacoes -->
        <modal-component
            id="modalAtualizarLocacoes"
            :titulo="'Atualizar Locação' + $store.state.item.id"
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
                    titulo="Erro ao tentar atualizar a locação"
                    :detalhes="$store.state.transacao"
                    v-if="$store.state.transacao.status == 'erro'"
                ></alert-component>
            </template>

            <template v-slot:conteudo>
                <input-container-component
                    titulo="Nome do Cliente"
                    id="atualizarCliente"
                    id-help="atualizarClienteHelp"
                    texto-ajuda="Informe o Cliente"
                >
                    <select
                        class="form-select"
                        v-model="$store.state.item.cliente_id"
                        aria-label="atualizarModelo"
                    >
                        <option v-for="c in clientes" :key="c.id" :value="c.id">
                            {{ c.nome }}
                        </option>
                    </select>
                </input-container-component>

                <input-container-component
                    titulo="Carro / Placa"
                    id="atualizarCarro"
                    id-help="atualizarCarroHelp"
                    texto-ajuda="Informe o Carro desejado"
                >
                    <select
                        class="form-select"
                        v-model="$store.state.item.carro_id"
                        aria-label="atualizarCarro"
                    >
                        <option v-for="c in carros" :key="c.id" :value="c.id">
                            {{ c.modelo.nome }} - {{ c.placa }}
                        </option>
                    </select>
                </input-container-component>

                <input-container-component
                    titulo="Data de Inicio"
                    id="atualizarDataInicio"
                    id-help="atualizarDataInicioHelp"
                    texto-ajuda="Informe a Data de Inicio do Período"
                >
                    <input
                        type="date"
                        class="form-control"
                        id="atualizarDataInicio"
                        v-model="dataInicio"
                    />
                </input-container-component>

                <input-container-component
                    titulo="Data Final Previsto"
                    id="atualizarDataFinalPrevista"
                    id-help="atualizarDataFinalPrevistaHelp"
                    texto-ajuda="Informe a Data Final Prevista do Período"
                >
                    <input
                        type="date"
                        class="form-control"
                        id="atualizarDataFinalPrevista"
                        v-model="dataFinalPrevisto"
                    />
                </input-container-component>

                <input-container-component
                    titulo="Data Final Realizado"
                    id="atualizarDataFinalRealizado"
                    id-help="atualizarDataFinalRealizadoHelp"
                    texto-ajuda="Informe a Data Final Realizado do Período"
                >
                    <input
                        type="date"
                        class="form-control"
                        id="atualizarDataFinalRealizado"
                        v-model="dataFinalRealizado"
                    />
                </input-container-component>

                <input-container-component
                    titulo="Valor da Diária"
                    id="atualizarValorDiaria"
                    id-help="atualizarValorDiariaHelp"
                    texto-ajuda="Informe o Valor da Diária"
                >
                    <input
                        type="number"
                        class="form-control"
                        id="atualizarValorDiaria"
                        aria-describedby="atualizarValorDiariaHelp"
                        placeholder="Valor da Diária"
                        v-model="$store.state.item.valor_diaria"
                    />
                </input-container-component>

                <input-container-component
                    titulo="Km Inicial"
                    id="atualizarKmInicial"
                    id-help="atualizarKmInicialHelp"
                    texto-ajuda="Informe o Km Inicial"
                >
                    <input
                        type="number"
                        class="form-control"
                        id="atualizarKmInicial"
                        aria-describedby="atualizarKmInicialHelp"
                        placeholder="Km Inicial"
                        v-model="$store.state.item.km_inicial"
                    />
                </input-container-component>

                <input-container-component
                    titulo="Km Final"
                    id="atualizarKmFinal"
                    id-help="atualizarKmFinalHelp"
                    texto-ajuda="Informe o Km Inicial"
                >
                    <input
                        type="number"
                        class="form-control"
                        id="atualizarKmFinal"
                        aria-describedby="atualizarKmFinalHelp"
                        placeholder="Km Final"
                        v-model="$store.state.item.km_final"
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
        <!-- fim do Modal de atualização de locacoes -->
    </div>
</template>

<script>
export default {
    props: ["clientes", "carros"],
    data() {
        return {
            urlBase: this.$store.state.urlBaseApi + "/locacao",
            urlFiltro: "",
            urlPaginacao: "",
            locacoes: { data: [] },
            busca: {},
            form: {},
            transacaoDetalhes: {},
            transacaoStatus: "",
            dataInicio: "",
            dataFinalPrevisto: "",
            dataFinalRealizado: "",
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
                    this.locacoes = response.data;
                })
                .catch((errors) => {
                    console.log(errors.response);
                });
        },
        paginacao(l) {
            if (l.url) {
                this.urlPaginacao = l.url.split("?")[1];
                this.carregarLista();
            }
        },
        configDefault() {
            this.form = {};
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
            let url = this.urlBase + "/" + this.$store.state.item.id;

            this.$store.state.item.data_inicio_periodo = this.dataInicio;
            this.$store.state.item.data_final_previsto_periodo =
                this.dataFinalPrevisto;
            this.$store.state.item.data_final_realizado_periodo =
                this.dataFinalRealizado;
            this.$store.state.item._method = "patch";

            this.$store.state.transacao.mensagem = "";
            this.$store.state.transacao.dados = "";

            axios
                .post(url, this.$store.state.item)
                .then((response) => {
                    this.$store.state.transacao.status = "sucesso";
                    this.$store.state.transacao.mensagem =
                        "Registro de locação atualizado com sucesso!";
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
    },
    watch: {
        formataDataInicio() {
            this.dataInicio = this.formataDataInicio;
        },
        formataDataFinalPrevisto() {
            this.dataFinalPrevisto = this.formataDataFinalPrevisto;
        },
        formataDataFinalRealizado() {
            this.dataFinalRealizado = this.formataDataFinalRealizado;
        },
    },
    computed: {
        formataDataInicio() {
            return this.$filters.formataDataInput(
                this.$store.state.item?.data_inicio_periodo
            );
        },
        formataDataFinalPrevisto() {
            return this.$filters.formataDataInput(
                this.$store.state.item?.data_final_previsto_periodo
            );
        },
        formataDataFinalRealizado() {
            return this.$filters.formataDataInput(
                this.$store.state.item?.data_final_realizado_periodo
            );
        },
    },
};
</script>
