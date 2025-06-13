export default {
    root: ({ props }) => ({
        class: [
            //Font
            'text-xs font-bold',

            //Alignments
            'inline-flex items-center justify-center',

            //Spacing
            'px-[0.4rem] py-1',

            //Shape
            {
                'rounded-md': !props.rounded,
                'rounded-full': props.rounded
            },

            //Colors
            {
                'text-primary-highlight-inverse bg-primary-highlight': props.severity == null || props.severity == 'primary',
                'text-green-700 light:text-green-300 bg-green-100 light:bg-green-500/20': props.severity == 'success',
                'text-surface-700 light:text-surface-300 bg-surface-100 light:bg-surface-500/20': props.severity == 'secondary',
                'text-blue-700 light:text-blue-300 bg-blue-100 light:bg-blue-500/20': props.severity == 'info',
                'text-orange-700 light:text-orange-300 bg-orange-100 light:bg-orange-500/20': props.severity == 'warning',
                'text-red-700 light:text-red-300 bg-red-100 light:bg-red-500/20': props.severity == 'danger',
                'text-surface-0 light:text-surface-900 bg-surface-900 light:bg-surface-0': props.severity == 'contrast'
            }
        ]
    }),
    value: {
        class: 'leading-normal'
    },
    icon: {
        class: 'mr-1 text-sm'
    }
};
