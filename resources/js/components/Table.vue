<template>
    <div>
        <table class="table table-hover">
            <thead>
                <tr>
                    <th v-for="(t, key) in titulos" :key="key" scope="col">
                        {{ t.titulo }}
                    </th>
                    <th
                        v-if="
                            visualizar.visivel ||
                            atualizar.visivel ||
                            remover.visivel
                        "
                    ></th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(obj, chave) in dadosFiltrados" :key="chave">
                    <td v-for="(valor, chaveValor) in obj" :key="chaveValor">
                        <span v-if="titulos[chaveValor].tipo == 'text'">
                            {{ valor }}
                        </span>
                        <span
                            v-if="titulos[chaveValor].tipo == 'data'"
                            class="text-wrap"
                        >
                            {{ $filters.formataDataTempo(valor) }}
                        </span>
                        <span v-if="titulos[chaveValor].tipo == 'imagem'">
                            <img
                                :src="'/storage/' + valor"
                                width="30"
                                height="30"
                            />
                        </span>
                    </td>
                    <td
                        v-if="
                            visualizar.visivel ||
                            atualizar.visivel ||
                            remover.visivel
                        "
                    >
                        <button
                            class="btn btn-outline-primary btn-sm me-2"
                            v-if="visualizar.visivel"
                            :data-bs-toggle="visualizar.dataToggle"
                            :data-bs-target="visualizar.dataTarget"
                            @click="setStore(obj)"
                        >
                            Visualizar
                        </button>
                        <button
                            class="btn btn-outline-primary btn-sm me-2"
                            v-if="atualizar.visivel"
                            :data-bs-toggle="atualizar.dataToggle"
                            :data-bs-target="atualizar.dataTarget"
                            @click="setStore(obj)"
                        >
                            Atualizar
                        </button>
                        <button
                            class="btn btn-outline-danger btn-sm"
                            v-if="remover.visivel"
                            :data-bs-toggle="remover.dataToggle"
                            :data-bs-target="remover.dataTarget"
                            @click="setStore(obj)"
                        >
                            Remover
                        </button>
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
        arrDadosRelacionais() {
            let arr = [];
            let dados = this.dadosRelacionais.dados;

            this.dadosRelacionais.campoRelacional.map((a) => {
                dados.forEach((d) => {
                    arr.push(d[a]);
                });
            });

            return arr;
        },
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
    },
    methods: {
        setStore(obj) {
            this.$store.state.transacao.status = "";
            this.$store.state.transacao.mensagem = "";
            this.$store.state.transacao.dados = "";
            this.$store.state.item = obj;
            this.$store.state.itensRelacionais = [];

            this.arrDadosRelacionais.map((dados) => {
                dados.forEach((d) => {
                    if (d[this.dadosRelacionais.idRelacional] == obj.id)
                        this.$store.state.itensRelacionais.push(d);
                });
            });
        },
    },
};
</script>
