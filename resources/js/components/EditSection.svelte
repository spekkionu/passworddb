<script>
    import { Icon, PencilAlt } from "svelte-hero-icons";
    import {useForm} from '@inertiajs/inertia-svelte'
    import Modal from "./Modal.svelte";
    import FormField from "./FormField.svelte";

    export let section;

    let modal;
    let form = useForm({
        name: section.name,
    });

    function submit() {
        $form.patch(`/${section.site_id}/section/${section.id}`, {preserveScroll: true, onSuccess: modal.close});
    }

    function keydown(event) {
        const target = event.target;
        if (target.dataset.field) {
            $form.clearErrors(target.dataset.field);
        } else if (target.name) {
            $form.clearErrors(target.name);
        }
    }

</script>

<form method="post" on:submit|preventDefault={submit} on:keydown="{keydown}">
    <Modal title="Update Section" bind:this={modal} on:open={() => $form.reset()} on:closed={() => $form.reset()}>
        <div let:open={open} slot="trigger">
            <button on:click={open} type="button" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <Icon src="{PencilAlt}" class="w-5 h-5" />
                <span class="ml-2 hidden sm:inline">Edit <span class="hidden md:inline">Section</span></span>
            </button>
        </div>
        <div slot="submit">
            <button type="submit"
                    disabled={$form.processing}
                    class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm">
                Save
            </button>
        </div>

        <div class="grid grid-cols-1 gap-4">
            <FormField
                id="{`site-${section.site_id}-section-${section.id}`}"
                label="Name"
                form="{$form}"
                field="name"
                bind:value={$form.name}
                required
                maxlength="191"
                disabled={$form.processing}
            />
        </div>
    </Modal>
</form>

