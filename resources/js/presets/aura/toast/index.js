export default {
    root: ({ props }) => ({
        class: [
            //Size and Shape
            'w-96 rounded-md',

            // Positioning
            { '-translate-x-2/4': props.position == 'top-center' || props.position == 'bottom-center' }
        ]
    }),
    container: ({ props }) => ({
        class: [
            'mb-4 rounded-md w-full',
            'border border-transparent',
            'backdrop-blur-[10px] shadow-md',

            // Colors
            {
                'bg-blue-50/90 light:bg-blue-500/20': props.message.severity == 'info',
                'bg-green-50/90 light:bg-green-500/20': props.message.severity == 'success',
                'bg-surface-50 light:bg-surface-800': props.message.severity == 'secondary',
                'bg-orange-50/90 light:bg-orange-500/20': props.message.severity == 'warn',
                'bg-red-50/90 light:bg-red-500/20': props.message.severity == 'error',
                'bg-surface-950 light:bg-surface-0': props.message.severity == 'contrast'
            },
            {
                'border-blue-200 light:border-blue-500/20': props.message.severity == 'info',
                'border-green-200 light:border-green-500/20': props.message.severity == 'success',
                'border-surface-300 light:border-surface-500/20': props.message.severity == 'secondary',
                'border-orange-200 light:border-orange-500/20': props.message.severity == 'warn',
                'border-red-200 light:border-red-500/20': props.message.severity == 'error',
                'border-surface-950 light:border-surface-0': props.message.severity == 'contrast'
            },
            {
                'text-blue-700 light:text-blue-300': props.message.severity == 'info',
                'text-green-700 light:text-green-300': props.message.severity == 'success',
                'text-surface-700 light:text-surface-300': props.message.severity == 'secondary',
                'text-orange-700 light:text-orange-300': props.message.severity == 'warn',
                'text-red-700 light:text-red-300': props.message.severity == 'error',
                'text-surface-0 light:text-surface-950': props.message.severity == 'contrast'
            }
        ]
    }),
    content: ({ props }) => ({
        class: [
            'flex p-3',
            {
                'items-start': props.message.summary,
                'items-center': !props.message.summary
            }
        ]
    }),
    icon: ({ props }) => ({
        class: [
            // Sizing and Spacing
            props.message.severity === 'contrast' || props.message.severity === 'secondary' ? 'w-0' : 'w-[1.125rem] h-[1.125rem] mr-2',
            'text-lg leading-[normal]'
        ]
    }),
    text: {
        class: [
            // Font and Text
            'text-base leading-[normal]',
            'ml-2',
            'flex-1'
        ]
    },
    summary: {
        class: 'font-medium block'
    },
    detail: ({ props }) => ({
        class: ['block', 'text-sm', props.message.severity === 'contrast' ? 'text-surface-0 light:text-surface-950' : 'text-surface-700 light:text-surface-0', { 'mt-2': props.message.summary }]
    }),
    closebutton: ({ props }) => ({
        class: [
            // Flexbox
            'flex items-center justify-center',

            // Size
            'w-7 h-7',

            // Spacing and Misc
            'ml-auto  relative',

            // Shape
            'rounded-full',

            // Colors
            'bg-transparent',

            // Transitions
            'transition duration-200 ease-in-out',

            // States
            'hover:bg-surface-0/30 light:hover:bg-[rgba(255,255,255,0.03)]',
            'focus:outline-none focus:outline-offset-0 focus:ring-1',
            {
                'focus:ring-blue-500 light:focus:ring-blue-400': props.severity == 'info',
                'focus:ring-green-500 light:focus:ring-green-400': props.severity == 'success',
                'focus:ring-surface-500 light:focus:ring-surface-400': props.severity == 'secondary',
                'focus:ring-orange-500 light:focus:ring-orange-400': props.severity == 'warn',
                'focus:ring-red-500 light:focus:ring-red-4000': props.severity == 'error',
                'focus:ring-surface-0 light:focus:ring-surface-950': props.severity == 'contrast'
            },

            // Misc
            'overflow-hidden'
        ]
    }),
    transition: {
        enterFromClass: 'opacity-0 translate-y-2/4',
        enterActiveClass: 'transition-[transform,opacity] duration-300',
        leaveFromClass: 'max-h-[1000px]',
        leaveActiveClass: '!transition-[max-height_.45s_cubic-bezier(0,1,0,1),opacity_.3s,margin-bottom_.3s] overflow-hidden',
        leaveToClass: 'max-h-0 opacity-0 mb-0'
    }
};
