<script>
    import { Inertia } from '@inertiajs/inertia'
    import { Icon, Trash, Exclamation } from "svelte-hero-icons";
    import Modal from "@/Modal.svelte";

    export let section;
    let modal;

    function open(){
        modal.open();
    }

    function submit(){
        Inertia.delete(`/${section.site_id}/section/${section.id}`, {
            preserveScroll: true
        })
    }
</script>

<Modal size="sm" bind:this={modal} dismiss="{false}">
    <div let:open={open} slot="trigger">
        <button on:click={open} type="button" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
            <Icon src="{Trash}" class="w-5 h-5" />
            <span class="ml-2 hidden sm:inline">Delete <span class="hidden md:inline">Section</span></span>
        </button>
    </div>
    <div slot="submit">
        <button type="button"
                on:click={submit}
                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm"
        >
            Delete
        </button>
    </div>

    <div class="sm:flex sm:items-start">
        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
            <!-- Heroicon name: outline/exclamation -->
            <Icon src="{Exclamation}" class="h-6 w-6 text-red-600" />
        </div>
        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                Delete Section
            </h3>
            <div class="mt-2">
                <p class="text-sm text-gray-500">
                    Are you sure you want to delete this section?<br>
                    All data in this section will be deleted.<br>
                    This action cannot be undone.
                </p>
            </div>
        </div>
    </div>
</Modal>


