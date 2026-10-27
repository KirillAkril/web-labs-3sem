
<template>
    <div
        v-if="article"
        class="alert alert-primary"
        role="alert"
        style="position: fixed; top: 20px; right: 20px; z-index: 9999; background: white; padding: 20px; border: 1px solid #0d6efd; border-radius: 8px; box-shadow: 0 4px 12px #0002;"
    >
        Добавлена новая статья:
        <strong>
            <a :href="`/articles/${article.id}`">
                {{ article.title }}
            </a>
        </strong>
        <button @click="article = null" style="margin-left: 12px;">
            Закрыть
        </button>
    </div>
</template>

<script>
export default {
    data() {
        return {
            article: null,
        };
    },

    created() {
        if (window.Echo) {
            window.Echo.channel('test')
                .listen('.NewArticleEvent', (event) => {
                    console.log('Получено событие:', event);
                    this.article = event.article;
                });
        }
    },
};
</script>
