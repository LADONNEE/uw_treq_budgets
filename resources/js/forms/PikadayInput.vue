<template>
    <div class="input-group">
        <input type="text" class="form-control" :aria-label="label"
               :name="input.name"
               v-model="input.value"
               ref="date_input"
               @keydown="keyHander($event)"
               @blur="reportValue"
        >
        <div class="input-group-append">
            <button class="btn btn-secondary" type="button" aria-label="Choose from calendar" ref="trigger">
                <i class="fas fa-calendar"></i>
            </button>
        </div>
    </div>
</template>

<script>
import InputModelMixin from "./InputModelMixin";

    export default {
        mixins: [InputModelMixin],
        props: {
            value: String,
            label: {
                type: String,
                required: false,
                default: 'Date'
            },
            format: {
                type: String,
                required: false,
                default: 'M/D/YYYY'
            },
            range: {
                type: Object,
                required: false,
                default: () => {}
            },
            focus: {
                type: Boolean,
                required: false,
                default: () => false
            }
        },
        data() {
            return {
                dateValue: this.value,
                pikaday: null
            };
        },
        computed: {
            momentValue() {
                if (this.dateValue) {
                    return moment(this.dateValue, this.format);
                }
                return null;
            }
        },
        watch: {
            value(val) {
                this.dateValue = val;
            },
            // range(val) {
            //     this.setRange(val);
            // }
        },
        methods: {
            openCalendar() {

            },
            reportValue() {
                this.$emit('input', this.dateValue);
            },
            keyHander(event) {
                if (event.key === 'Enter' || event.keyCode === 13) {
                    this.$emit('save');
                }
                if (event.key === 'Escape' || event.keyCode === 27) {
                    this.$emit('cancel');
                }
            },
            pickedDate(value) {
                this.input.value = value.format(this.format);
                this.dateValue = value.format(this.format);
                this.reportValue();
            },
            setRange(range) {
                let before = this.toMoment(range.before || null);
                this.setBefore(before);
                let after = this.toMoment(range.after || null);
                this.setAfter(after);
            },
            setAfter(mDate) {
                if (!this.pikaday) {
                    return;
                }
                if (moment.isMoment(mDate)) {
                    this.pikaday.config({
                        minDate: mDate.toDate()
                    });
                } else {
                    this.pikaday.config({
                        minDate: null
                    });
                }
            },
            setBefore(mDate) {
                if (!this.pikaday) {
                    return;
                }
                if (moment.isMoment(mDate)) {
                    this.pikaday.config({
                        maxDate: mDate.toDate()
                    });
                } else {
                    this.pikaday.config({
                        maxDate: null
                    });
                }
            },
            toMoment(value) {
                if (!value) {
                    return null;
                }
                if (moment.isMoment(value)) {
                    return value;
                }
                if (value instanceof Date) {
                    return moment(value);
                }
                return moment(value, this.format);
            }
        },
        mounted() {
            let vm = this;
            this.pikaday = new window.Pikaday({
                field: this.$refs.date_input,
                format: this.format,
                defaultDate: (this.dateValue) ? moment(this.dateValue, this.format).toDate() : moment(),
                trigger: this.$refs.trigger,
                onSelect: function() {
                    vm.pickedDate(this.getMoment());
                }
            });
            // this.setRange(this.range);

            if (this.focus) {
                this.$nextTick(() => {
                    this.$refs.date_input.focus();
                    this.$refs.date_input.select();
                });
            }
        },
        beforeDestroy() {
            this.pikaday.destroy();
        }
    }
</script>
