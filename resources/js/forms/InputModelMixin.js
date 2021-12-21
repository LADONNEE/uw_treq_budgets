export default {
    props: ['input', 'inputClass', 'focus'],
    methods: {
        handleChange: function(e) {
            this.$emit('input', this.input.value, e);
        },
        handleKeydown: function(e) {
            this.$emit('keydown', e);
        }
    },
    mounted() {
        if (this.focus) {
            this.$nextTick(function(){
                this.$refs.input.focus();
                this.$refs.input.select();
            });
        }
    }
}
