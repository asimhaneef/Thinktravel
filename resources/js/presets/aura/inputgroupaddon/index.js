export default {
    root: {
        class: [
            // Flex
            'flex items-center justify-center',

            // Shape
            'first:rounded-l-md',
            'last:rounded-r-md',
            'border-y',

            'last:border-r',
            'border-l',
            'border-r-0',

            // Space
            'p-2',

            // Size
            'min-w-[2.5rem]',

            // Color
            'bg-transparent light:bg-surface-900',
            'text-surface-800 light:text-white/80',
            'border-surface-300 light:border-surface-700'
        ]
    }
};
