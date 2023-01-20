<template>
    <div>
        <h6>{{ titulo }}</h6>
        <table class="table table-hover">
            <thead>
                <th v-for="(t, key) in titulosTable" :key="key">
                    {{ t.titulo }}
                </th>
            </thead>
            <tbody>
                <tr v-for="(obj, key) in dadosFiltrados" :key="key">
                    <td v-for="(valor, keyObj) in obj" :key="keyObj">
                        <span v-if="titulosTable[keyObj].tipo == 'text'">
                            {{ valor }}
                        </span>
                        <span v-if="titulosTable[keyObj].tipo == 'imagem'">
                            <img :src="'/storage/' + valor" height="30" />
                        </span>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
export default {
    props: ["titulo", "titulosTable"],
    computed: {
        dadosFiltrados() {
            let campos = Object.keys(this.titulosTable);
            let dados = this.$store.state.itensRelacionais;
            let arr = [];

            dados.map((d, index) => {
                let itemFiltrado = {};
                campos.forEach((camp) => {
                    itemFiltrado[camp] = d[camp];
                });
                arr.push(itemFiltrado);
            });

            return arr;
        },
    },
};
</script>
