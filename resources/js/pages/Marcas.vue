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
                <card-component titulo="Relação de Marcas">
                    <template v-slot:conteudo>
                        <table-component
                            :dados="marcas.data"
                            :dados-relacionais="{
                                dados: marcas.data,
                                campoRelacional: ['modelos'],
                                idRelacional: 'marca_id',
                            }"
                            :menu="{
                                visualizar: {
                                    visivel: true,
                                    dataToggle: 'modal',
                                    dataTarget: '#modalVisualizarMarca',
                                },
                                atualizar: {
                                    visivel: true,
                                    dataToggle: 'modal',
                                    dataTarget: '#modalAtualizarMarca',
                                },
                                remover: {
                                    visivel: true,
                                    dataToggle: 'modal',
                                    dataTarget: '#modalRemoverMarca',
                                },
                            }"
                            :titulos="{
                                id: { titulo: 'ID', tipo: 'text' },
                                nome: { titulo: 'Nome', tipo: 'text' },
                                imagem: { titulo: 'Imagem', tipo: 'imagem' },
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
                                        v-for="(l, key) in marcas.links"
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
                                    data-bs-target="#modalMarca"
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
        <modal-component id="modalMarca" titulo="Adicionar Marca">
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
                        titulo="Nome da Marca"
                        id="novoNome"
                        id-help="novoNomeHelp"
                        texto-ajuda="Informe o Nome da Marca"
                    >
                        <input
                            type="text"
                            class="form-control"
                            id="novoNome"
                            aria-describedby="novoNomeHelp"
                            placeholder="Nome da Marca"
                            v-model="nomeMarca"
                        />
                    </input-container-component>
                </div>

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
            </template>

            <template v-slot:rodape>
                <button type="button" class="btn btn-primary" @click="salvar()">
                    Salvar
                </button>
            </template>
        </modal-component>
        <!-- fim do Modal de inclusão de marca -->

        <!-- inicio do Modal de visualização de marca -->
        <modal-component id="modalVisualizarMarca" titulo="Visualizar Marca">
            <template v-slot:alertas></template>

            <template v-slot:conteudo>
                <input-container-component titulo="ID">
                    <input
                        type="text"
                        class="form-control"
                        :value="$store.state.item.id"
                        disabled
                    />
                </input-container-component>

                <input-container-component titulo="Nome da marca">
                    <input
                        type="text"
                        class="form-control"
                        :value="$store.state.item.nome"
                        disabled
                    />
                </input-container-component>

                <input-container-component
                    v-if="$store.state.item.imagem"
                    titulo="Imagem"
                >
                    <img :src="'storage/' + $store.state.item.imagem" />
                </input-container-component>

                <input-container-component titulo="Data de criação">
                    <input
                        type="text"
                        class="form-control"
                        :value="
                            $filters.formataDataTempo(
                                $store.state.item.created_at
                            )
                        "
                        disabled
                    />
                </input-container-component>

                <div
                    class="mt-4"
                    v-if="$store.state.itensRelacionais.length > 0"
                >
                    <table-relacional-component
                        titulo="Modelos dos carros"
                        :titulos-table="{
                            id: { titulo: 'ID', tipo: 'text' },
                            nome: { titulo: 'Nome', tipo: 'text' },
                            imagem: { titulo: 'Imagem', tipo: 'imagem' },
                        }"
                    ></table-relacional-component>
                </div>
            </template>
        </modal-component>
        <!-- fim do Modal de visualização de marca -->

        <!-- inicio do Modal de remoção de marca -->
        <modal-component id="modalRemoverMarca" titulo="Remover Marca">
            <template v-slot:alertas>
                <alert-component
                    tipo="success"
                    titulo="Transação realizada com sucesso"
                    :detalhes="$store.state.transacao"
                    v-if="$store.state.transacao.status == 'sucesso'"
                ></alert-component>
                <alert-component
                    tipo="danger"
                    titulo="Erro na transação"
                    :detalhes="$store.state.transacao"
                    v-if="$store.state.transacao.status == 'erro'"
                ></alert-component>
                <alert-component
                    tipo="danger"
                    titulo="Erro na transação"
                    :detalhes="{
                        mensagem: 'Há modelos vinculados a essa marca',
                    }"
                    v-if="$store.state.itensRelacionais.length > 0"
                ></alert-component>
            </template>

            <template
                v-slot:conteudo
                v-if="$store.state.transacao.status != 'sucesso'"
            >
                <input-container-component titulo="ID">
                    <input
                        type="text"
                        class="form-control"
                        :value="$store.state.item.id"
                        disabled
                    />
                </input-container-component>

                <input-container-component titulo="Nome da marca">
                    <input
                        type="text"
                        class="form-control"
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
                    v-if="
                        $store.state.transacao.status != 'sucesso' &&
                        $store.state.itensRelacionais.length <= 0
                    "
                >
                    Remover
                </button>
            </template>
        </modal-component>
        <!-- fim do Modal de remoção de marca -->

        <!-- inicio do Modal de atualização de marca -->
        <modal-component
            id="modalAtualizarMarca"
            :titulo="'Atualizar Marca ' + $store.state.item.id"
        >
            <template v-slot:alertas>
                <alert-component
                    tipo="success"
                    titulo="Transação realizada com sucesso"
                    :detalhes="$store.state.transacao"
                    v-if="$store.state.transacao.status == 'sucesso'"
                ></alert-component>
                <alert-component
                    tipo="danger"
                    titulo="Erro na transação"
                    :detalhes="$store.state.transacao"
                    v-if="$store.state.transacao.status == 'erro'"
                ></alert-component>
            </template>

            <template v-slot:conteudo>
                <div class="mb-3">
                    <input-container-component
                        titulo="Nome da Marca"
                        id="atualizarNome"
                        id-help="atualizarNomeHelp"
                        texto-ajuda="Informe o Nome da Marca"
                    >
                        <input
                            type="text"
                            class="form-control"
                            id="atualizarNome"
                            aria-describedby="atualizarNomeHelp"
                            placeholder="Nome da Marca"
                            v-model="$store.state.item.nome"
                        />
                    </input-container-component>
                </div>

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
        <!-- fim do Modal de atualização de marca -->

        <!-- inicio do Modal de filtros de modelos -->
        <modal-component id="modalFiltros" titulo="Filtrar Marca">
            <template v-slot:conteudo>
                <div class="col-6">
                    <input-container-component
                        titulo="ID"
                        id="filtroId"
                        id-help="idHelp"
                        texto-ajuda="Opcional. Informe o ID da marca"
                    >
                        <input
                            type="number"
                            class="form-control"
                            id="filtroId"
                            placeholder="ID"
                            aria-describedby="idHelp"
                            v-model="busca.id"
                        />
                    </input-container-component>
                </div>

                <input-container-component
                    titulo="Nome da Marca"
                    id="inputNome"
                    id-help="NomeHelp"
                    texto-ajuda="Opcional. Informe o Nome da marca"
                >
                    <input
                        type="text"
                        class="form-control"
                        id="inputNome"
                        aria-describedby="NomeHelp"
                        placeholder="Nome da marca"
                        v-model="busca.nome"
                    />
                </input-container-component>
            </template>

            <template v-slot:rodape>
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
    </div>
</template>

<script>
export default {
    data() {
        return {
            urlbase: "http://localhost:8000/api/v1/marca",
            urlPaginacao: "",
            urlFiltro: "",
            nomeMarca: "",
            arquivoImagem: [],
            transacaoStatus: "",
            transacaoDetalhes: {},
            marcas: { data: [] },
            busca: {},
        };
    },
    mounted() {
        this.carregarLista();
    },
    methods: {
        carregarLista() {
            let url = this.urlbase + "?" + this.urlPaginacao + this.urlFiltro;

            axios
                .get(url)
                .then((response) => {
                    this.marcas = response.data;
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
        carregarImagem(e) {
            this.arquivoImagem = e.target.files;
        },
        salvar() {
            let formData = new FormData();
            formData.append("nome", this.nomeMarca);
            formData.append("imagem", this.arquivoImagem[0]);

            let config = {
                headers: {
                    "Content-Type": "multipart/form-data",
                },
            };

            axios
                .post(this.urlbase, formData, config)
                .then((response) => {
                    this.transacaoStatus = "add";
                    this.transacaoDetalhes = {
                        mensagem: "ID do registro: " + response.data.id,
                    };
                    this.carregarLista();
                })
                .catch((errors) => {
                    this.transacaoStatus = "erro";
                    console.log(errors.response.data.errors);
                    this.transacaoDetalhes = {
                        mensagem: errors.response.data.message,
                        dados: errors.response.data.errors,
                    };
                });
        },
        filtrar() {
            let filtro = "";

            for (let chave in this.busca) {
                let percentSign = "%";
                if (this.busca[chave]) {
                    if (filtro != "") filtro += ";";

                    if (chave == "id") percentSign = "";

                    filtro +=
                        chave +
                        ":like:" +
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
        remover() {
            let confirmacao = confirm(
                "Tem certeza que deseja remover esse registro?"
            );

            if (confirmacao) {
                let url = this.urlbase + "/" + this.$store.state.item.id;

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
                    .catch((errors) => {
                        this.$store.state.transacao.status = "erro";
                        this.$store.state.transacao.mensagem =
                            errors.response.data.erro;
                    });
            }
        },
        atualizar() {
            let formData = new FormData();
            formData.append("_method", "patch");
            formData.append("nome", this.$store.state.item.nome);

            if (this.arquivoImagem[0])
                formData.append("imagem", this.arquivoImagem[0]);

            let url = this.urlbase + "/" + this.$store.state.item.id;

            let config = {
                headers: {
                    "Content-Type": "multipart/form-data",
                },
            };

            axios
                .post(url, formData, config)
                .then((response) => {
                    this.$store.state.transacao.status = "sucesso";
                    this.$store.state.transacao.mensagem =
                        "Registro de marca atualizado com sucesso!";
                    atualizarImagem.value = "";
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
};
</script>
