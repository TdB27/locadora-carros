<template>
    <div :class="estilo" role="alert">
        {{ titulo }}
        <div v-if="detalhes.mensagem">
            <hr />
            <span>{{ detalhes.mensagem }}</span>
            <ul v-if="detalhes.dados">
                <li v-for="(e, key) in detalhes.dados" :key="key">
                    {{ e[0] }}
                </li>
            </ul>
        </div>
    </div>
</template>

<script>
export default {
    props: ["tipo", "titulo", "detalhes"],
    computed: {
        estilo() {
            return "alert alert-" + this.tipo;
        },
    },
};
</script>
