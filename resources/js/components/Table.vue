<template>
    <div class="">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th
                        v-for="(t, key) in titulos"
                        :key="key"
                        scope="col"
                        :style="
                            t.tipo == 'withoutAppear' ? 'display: none;' : ''
                        "
                    >
                        {{ t.titulo }}
                    </th>
                    <th
                        class="text-center"
                        v-if="
                            visualizar.visivel ||
                            atualizar.visivel ||
                            remover.visivel
                        "
                    >
                        Ações
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(obj, chave) in dadosFiltrados" :key="chave">
                    <td
                        v-for="(valor, chaveValor) in obj"
                        :key="chaveValor"
                        :style="
                            titulos[chaveValor].tipo == 'withoutAppear'
                                ? 'display: none;'
                                : ''
                        "
                    >
                        <span v-if="titulos[chaveValor].tipo == 'text'">
                            {{ valor }}
                        </span>
                        <span
                            v-if="titulos[chaveValor].tipo == 'data'"
                            class="text-wrap"
                        >
                            {{
                                $filters.formataDataTempo(
                                    valor,
                                    titulos[chaveValor].separator ?? false
                                )
                            }}
                        </span>
                        <span v-if="titulos[chaveValor].tipo == 'imagem'">
                            <img :src="'/storage/' + valor" height="30" />
                        </span>
                        <span v-if="titulos[chaveValor].tipo == 'boolean'">
                            {{ valor == 0 ? "Não" : "Sim" }}
                        </span>
                        <span v-if="titulos[chaveValor].tipo == 'obj'">
                            {{ valor[titulos[chaveValor].campo] }}
                        </span>
                        <span v-if="titulos[chaveValor].tipo == 'relational'">
                            {{
                                $filters.dadosTabelaRelacional(
                                    dadosRelacionais.tabela,
                                    valor,
                                    titulos[chaveValor].campo
                                )
                            }}
                        </span>
                        <span v-if="titulos[chaveValor].tipo == 'amount'">
                            R$ {{ $filters.formatAmount(valor) }}
                        </span>
                    </td>
                    <td
                        class="dropdown text-center"
                        v-if="
                            visualizar.visivel ||
                            atualizar.visivel ||
                            remover.visivel
                        "
                    >
                        <a
                            href="#"
                            class="dropdown-toggle dropdown-toggle-table"
                            data-bs-toggle="dropdown"
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                width="16"
                                height="16"
                                fill="currentColor"
                                class="bi bi-three-dots"
                                viewBox="0 0 16 16"
                            >
                                <path
                                    d="M3 9.5a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3zm5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3zm5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3z"
                                />
                            </svg>
                        </a>
                        <div class="dropdown-menu">
                            <a
                                href="#"
                                class="dropdown-item"
                                v-if="visualizar.visivel"
                                :data-bs-toggle="visualizar.dataToggle"
                                :data-bs-target="visualizar.dataTarget"
                                @click="setStore(obj)"
                            >
                                Visualizar
                            </a>
                            <a
                                href="#"
                                class="dropdown-item"
                                v-if="atualizar.visivel"
                                :data-bs-toggle="atualizar.dataToggle"
                                :data-bs-target="atualizar.dataTarget"
                                @click="setStore(obj)"
                            >
                                Atualizar
                            </a>
                            <a
                                href="#"
                                class="dropdown-item"
                                v-if="remover.visivel"
                                :data-bs-toggle="remover.dataToggle"
                                :data-bs-target="remover.dataTarget"
                                @click="setStore(obj)"
                            >
                                Remover
                            </a>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
export default {
    props: [
        "dados",
        "dadosRelacionais",
        "titulos",
        "visualizar",
        "atualizar",
        "remover",
    ],
    computed: {
        dadosFiltrados() {
            let campos = Object.keys(this.titulos);
            let dadosFiltrados = [];

            this.dados.map((item, chave) => {
                let itemFiltrado = {};
                campos.forEach((campo) => {
                    itemFiltrado[campo] = item[campo]; // utilizar a sintaxe de arr para atribuir valores a objs
                });
                dadosFiltrados.push(itemFiltrado);
            });

            return dadosFiltrados;
        },
        arrDadosRelacionais() {
            let arr = [];

            if (this.dadosRelacionais.campoRelacional) {
                let dados = this.dadosRelacionais.dados;

                this.dadosRelacionais.campoRelacional.map((a) => {
                    dados.forEach((d) => {
                        arr.push(d[a]);
                    });
                });
            }

            return arr;
        },
    },
    methods: {
        setStore(obj) {
            this.$store.state.transacao.status = "";
            this.$store.state.transacao.mensagem = "";
            this.$store.state.transacao.dados = "";
            this.$store.state.item = obj;
            this.$store.state.itensRelacionais = [];

            if (this.dadosRelacionais.idRelacional) {
                this.arrDadosRelacionais.map((dados) => {
                    dados.forEach((d) => {
                        if (d[this.dadosRelacionais.idRelacional] == obj.id)
                            this.$store.state.itensRelacionais.push(d);
                    });
                });
            }
        },
    },
};
</script>

<style>
.dropdown-toggle-table::after {
    display: none;
}
</style>
