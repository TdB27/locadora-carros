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

                <!-- inicio do card de modelos -->
                <card-component titulo="Relação de Modelos">
                    <template v-slot:conteudo>
                        <table-component
                            :dados="modelos.data"
                            :dados-relacionais="{
                                dados: modelos.data,
                                campoRelacional: null, // modelo pertence a marca
                                idRelacional: null, // modelo pertence a marca
                                tabela: marcas,
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
                                marca_id: {
                                    titulo: 'Marca',
                                    tipo: 'relational',
                                    campo: 'nome',
                                },
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

        <!-- inicio do Modal de filtros de modelos -->
        <modal-component id="modalFiltros" titulo="Filtrar Modelo">
            <template v-slot:conteudo>
                <div class="mb-3 col-6">
                    <input-container-component
                        titulo="ID"
                        id="filtroId"
                        id-help=""
                        texto-ajuda=""
                    >
                        <input
                            type="number"
                            class="form-control"
                            id="filtroId"
                            placeholder="ID"
                            v-model="busca.id"
                        />
                    </input-container-component>
                </div>

                <div class="mb-3">
                    <input-container-component
                        titulo="Marcas"
                        id="novaMarca"
                        id-help=""
                        texto-ajuda=""
                    >
                        <select
                            class="form-select"
                            v-model="busca.marca_id"
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
                        id="filtroNome"
                        id-help=""
                        texto-ajuda=""
                    >
                        <input
                            type="text"
                            class="form-control"
                            id="filtro"
                            placeholder="Nome do Modelo"
                            v-model="busca.nome"
                        />
                    </input-container-component>
                </div>

                <div class="mb-3 col-6">
                    <input-container-component
                        titulo="Numero de Portas"
                        id="filtroNumeroPortas"
                        id-help=""
                        texto-ajuda=""
                    >
                        <input
                            type="number"
                            class="form-control"
                            id="filtroNumeroPortas"
                            placeholder="Numero de Portas"
                            v-model="busca.numero_portas"
                        />
                    </input-container-component>
                </div>

                <div class="mb-3 col-6">
                    <input-container-component
                        titulo="Lugares"
                        id="filtroNovoLugares"
                        id-help=""
                        texto-ajuda=""
                    >
                        <input
                            type="number"
                            class="form-control"
                            id="filtroNovoLugares"
                            placeholder="Lugares"
                            v-model="busca.lugares"
                        />
                    </input-container-component>
                </div>

                <div class="col-6">
                    <input-container-component
                        titulo="Air Bag"
                        id="filtroAirBag"
                        id-help=""
                        texto-ajuda=""
                    >
                        <select class="form-select" v-model="busca.air_bag">
                            <option value="1">Sim</option>
                            <option value="0">Não</option>
                        </select>
                    </input-container-component>
                </div>

                <div class="col-6">
                    <input-container-component
                        titulo="ABS"
                        id="filtroAbs"
                        id-help=""
                        texto-ajuda=""
                    >
                        <select class="form-select" v-model="busca.abs">
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
                <button
                    type="button"
                    class="btn btn-primary"
                    data-bs-dismiss="modal"
                    @click="filtrar()"
                >
                    Filtrar
                </button>
            </template>
        </modal-component>
        <!-- fim do Modal de filtros de modelos -->

        <!-- inicio do Modal de atualização de modelos -->
        <modal-component
            id="modalAtualizarModelo"
            :titulo="'Atualizar Modelo ' + $store.state.item.id"
        >
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
                        id="atualizarMarca"
                        id-help="atualizarMarca"
                        texto-ajuda="Informe a marca do modelo"
                    >
                        <select
                            class="form-select"
                            v-model="$store.state.item.marca_id"
                            aria-label="atualizarMarca"
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
                        id="atualizarNome"
                        id-help="atualizarNomeHelp"
                        texto-ajuda="Informe o Nome do Modelo"
                    >
                        <input
                            type="text"
                            class="form-control"
                            id="atualizarNome"
                            aria-describedby="atualizarNomeHelp"
                            placeholder="Nome do Modelo"
                            v-model="$store.state.item.nome"
                        />
                    </input-container-component>
                </div>

                <div class="mb-3">
                    <input-container-component
                        titulo="Image"
                        id="atualizarImagem"
                        id-help="atualizarImagemHelp"
                        texto-ajuda="Selecione uma imagem no formato PNG"
                    >
                        <input
                            type="file"
                            class="form-control"
                            id="atualizarImagem"
                            aria-describedby="atualizarImagemHelp"
                            placeholder="Selecione uma imagem"
                            @change="carregarImagem($event)"
                        />
                    </input-container-component>
                </div>

                <div class="mb-3 col-6">
                    <input-container-component
                        titulo="Numero de Portas"
                        id="atualizarNumeroPortas"
                        id-help="atualizarNumeroPortas"
                        texto-ajuda="Informe o Numero de Portas"
                    >
                        <input
                            type="number"
                            class="form-control"
                            id="atualizarNumeroPortas"
                            aria-describedby="atualizarNumeroPortas"
                            placeholder="Numero de Portas"
                            v-model="$store.state.item.numero_portas"
                        />
                    </input-container-component>
                </div>

                <div class="mb-3 col-6">
                    <input-container-component
                        titulo="Lugares"
                        id="atualizarLugares"
                        id-help="atualizarLugares"
                        texto-ajuda="Informe a quantidade de lugares"
                    >
                        <input
                            type="number"
                            class="form-control"
                            id="atualizarLugares"
                            aria-describedby="atualizarLugares"
                            placeholder="Lugares"
                            v-model="$store.state.item.lugares"
                        />
                    </input-container-component>
                </div>

                <div class="col-6">
                    <input-container-component
                        titulo="Air Bag"
                        id="atualizarAirBag"
                        id-help="atualizarAirBag"
                        texto-ajuda="Informe se há Air Bag"
                    >
                        <select
                            class="form-select"
                            v-model="$store.state.item.air_bag"
                            aria-label="atualizarAirBag"
                        >
                            <option value="1">Sim</option>
                            <option value="0">Não</option>
                        </select>
                    </input-container-component>
                </div>

                <div class="col-6">
                    <input-container-component
                        titulo="ABS"
                        id="atualizarAbs"
                        id-help="atualizarAbs"
                        texto-ajuda="Informe se há ABS"
                    >
                        <select
                            class="form-select"
                            v-model="$store.state.item.abs"
                            aria-label="atualizarAbs"
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
                <button
                    type="button"
                    class="btn btn-primary"
                    @click="atualizar()"
                >
                    Atualizar
                </button>
            </template>
        </modal-component>
        <!-- fim do Modal de atualização de modelos -->

        <!-- inicio do Modal de visualização de modelos -->
        <modal-component
            id="modalVisualizarModelo"
            :titulo="'Visualizar Modelo ' + $store.state.item.id"
        >
            <template v-slot:conteudo>
                <div class="mb-3">
                    <input-container-component
                        titulo="Marcas"
                        id="visualizarMarca"
                        id-help=""
                        texto-ajuda=""
                    >
                        <input
                            type="text"
                            class="form-control"
                            id="visualizarMarca"
                            placeholder="Marca"
                            :value="
                                $filters.dadosTabelaRelacional(
                                    marcas,
                                    $store.state.item.marca_id,
                                    'nome'
                                )
                            "
                            disabled
                        />
                    </input-container-component>
                </div>

                <div class="mb-3">
                    <input-container-component
                        titulo="Nome da Marca"
                        id="atualizarNome"
                        id-help=""
                        texto-ajuda=""
                    >
                        <input
                            type="text"
                            class="form-control"
                            id="atualizarNome"
                            :value="$store.state.item.nome"
                            disabled
                        />
                    </input-container-component>
                </div>

                <div class="mb-3 col-6">
                    <input-container-component
                        titulo="Numero de Portas"
                        id="visualizarNumeroPortas"
                        id-help=""
                        texto-ajuda=""
                    >
                        <input
                            type="number"
                            class="form-control"
                            id="visualizarNumeroPortas"
                            placeholder="Numero de Portas"
                            :value="$store.state.item.numero_portas"
                            disabled
                        />
                    </input-container-component>
                </div>

                <div class="mb-3 col-6">
                    <input-container-component
                        titulo="Lugares"
                        id="visualizarLugares"
                        id-help=""
                        texto-ajuda=""
                    >
                        <input
                            type="number"
                            class="form-control"
                            id="visualizarLugares"
                            placeholder="Lugares"
                            :value="$store.state.item.lugares"
                            disabled
                        />
                    </input-container-component>
                </div>

                <div class="col-6">
                    <input-container-component
                        titulo="Air Bag"
                        id="visualizarAirBag"
                        id-help=""
                        texto-ajuda=""
                    >
                        <input
                            type="text"
                            class="form-control"
                            id="visualizarAirBag"
                            :value="
                                $store.state.item.air_bag == '0' ? 'Não' : 'Sim'
                            "
                            disabled
                        />
                    </input-container-component>
                </div>

                <div class="col-6">
                    <input-container-component
                        titulo="ABS"
                        id="visualizarAbs"
                        id-help=""
                        texto-ajuda=""
                    >
                        <input
                            type="text"
                            class="form-control"
                            id="visualizarAbs"
                            :value="
                                $store.state.item.abs == '0' ? 'Não' : 'Sim'
                            "
                            disabled
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
            </template>
        </modal-component>
        <!-- fim do Modal de visualização de modelos -->

        <!-- inicio do Modal de remoção de modelos -->
        <modal-component
            id="modalRemoverModelo"
            :titulo="'Remover Modelo ' + $store.state.item.id"
        >
            <template v-slot:alertas>
                <alert-component
                    tipo="success"
                    titulo="Transação realizada com sucesso"
                    :detalhes="$store.state.transacao"
                    v-if="$store.state.transacao.status == 'sucesso'"
                >
                </alert-component>
                <alert-component
                    tipo="danger"
                    titulo="Erro na transação"
                    :detalhes="$store.state.transacao"
                    v-if="$store.state.transacao.status == 'erro'"
                >
                </alert-component>
            </template>

            <template v-slot:conteudo>
                <div class="mb-3">
                    <input-container-component
                        titulo="Marca"
                        id="visualizarMarca"
                        id-help=""
                        texto-ajuda=""
                    >
                        <input
                            type="text"
                            class="form-control"
                            id="visualizarMarca"
                            placeholder="Marca"
                            :value="
                                $filters.dadosTabelaRelacional(
                                    marcas,
                                    $store.state.item.marca_id,
                                    'nome'
                                )
                            "
                            disabled
                        />
                    </input-container-component>
                </div>

                <div class="mb-3">
                    <input-container-component
                        titulo="Nome da Marca"
                        id="atualizarNome"
                        id-help=""
                        texto-ajuda=""
                    >
                        <input
                            type="text"
                            class="form-control"
                            id="atualizarNome"
                            :value="$store.state.item.nome"
                            disabled
                        />
                    </input-container-component>
                </div>

                <div class="mb-3">
                    <input-container-component
                        titulo=""
                        id="atualizarNome"
                        id-help=""
                        texto-ajuda=""
                    >
                        <img
                            :src="'/storage/' + $store.state.item.imagem"
                            height="60"
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
                <button type="button" class="btn btn-danger" @click="remover()">
                    Remover
                </button>
            </template>
        </modal-component>
        <!-- fim do Modal de remoção de modelos -->
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
            let url = this.urlBase + "?" + this.urlPaginacao + this.urlFiltro;

            axios.get(url).then((response) => {
                this.modelos = response.data;
            });
        },
        carregarImagem(event) {
            this.form.imagem = event.target.files;
        },
        salvar() {
            this.transacaoDetalhes = {};

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
                    novaImagem.value = "";
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
        filtrar() {
            let filtro = "";

            for (let chave in this.busca) {
                let operatorSign = ":like:";
                let percentSign = "%";

                if (this.busca[chave]) {
                    if (filtro != "") filtro += ";";

                    if (chave == "id" || chave == "marca_id") {
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
        atualizar() {
            this.transacaoDetalhes = {};

            let url = this.urlBase + "/" + this.$store.state.item.id;

            let formData = new FormData();
            formData.append("marca_id", this.$store.state.item.marca_id);
            formData.append("nome", this.$store.state.item.nome);

            if (this.form.imagem)
                formData.append("imagem", this.form.imagem[0]);

            formData.append(
                "numero_portas",
                this.$store.state.item.numero_portas
            );
            formData.append("lugares", this.$store.state.item.lugares);
            formData.append("air_bag", this.$store.state.item.air_bag);
            formData.append("abs", this.$store.state.item.abs);
            formData.append("_method", "patch");

            let config = {
                headers: {
                    "Content-Type": "multipart/form-data",
                },
            };

            axios
                .post(url, formData, config)
                .then((response) => {
                    this.transacaoStatus = "add";
                    atualizarImagem.value = "";
                    this.carregarLista();
                })
                .catch((error) => {
                    this.transacaoDetalhes.mensagem =
                        error.response.data.message;
                    this.transacaoDetalhes.dados = error.response.data.errors;
                    this.transacaoStatus = "erro";
                });
        },
        remover() {
            let confirmacao = confirm(
                "Tem certeza que deseja remover esse registro?"
            );

            if (confirmacao) {
                let url = this.urlBase + "/" + this.$store.state.item.id;

                let formData = new FormData();
                formData.append("_method", "delete");

                axios
                    .post(url, formData)
                    .then((response) => {
                        this.$store.state.transacao.status = "sucesso";
                        this.$store.state.transacao.mensagem =
                            response.data.msg;
                        this.carregarLista();
                    })
                    .catch((error) => {
                        this.$store.state.transacao.status = "erro";
                        this.$store.state.transacao.mensagem =
                            errors.response.data.erro;
                    });
            }
        },
    },
};
</script>
