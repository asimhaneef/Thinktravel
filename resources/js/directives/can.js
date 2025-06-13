import { usePermissionStore } from '@/stores/Permissions';

export default {
    mounted(el, binding) {
        const permission = binding.value;
        const store = usePermissionStore();

        // If permission not granted, remove element
        if (!store.hasPermission(permission)) {
            el.parentNode && el.parentNode.removeChild(el);
        }
    }
};
