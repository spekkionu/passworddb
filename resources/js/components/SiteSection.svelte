<script>
    import DataGroup from '@/DataGroup.svelte';
    import EditSection from '@/EditSection.svelte';
    import AddRecord from "@/AddRecord.svelte";
    import DeleteSection from "@/DeleteSection.svelte";
    import MoveSection from "@/MoveSection.svelte";

    export let section;
    export let first;
    export let last;
</script>

<div class="my-4">
    <div class="pb-5 border-b border-gray-200 sm:flex sm:items-center sm:justify-between">
        <h2 class="text-2xl font-bold leading-7 text-gray-900 truncate" title="{section.name}">{section.name}</h2>
        <div class="mt-3 flex sm:mt-0 sm:ml-4 flex space-x-4">
            {#if !first || !last}
                <div class="flex justify-end items-center">
                    <MoveSection section="{section}" dir="up" disabled="{first}" />
                    <MoveSection section="{section}" dir="down" disabled="{last}" />
                </div>
            {/if}
            <EditSection section="{section}" />
            <DeleteSection section="{section}" />
            <AddRecord section="{section}" />
        </div>
    </div>

    <div class="section-records flex justify-start items-start flex-wrap mt-2 -mx-4">
    {#each section.data as item, index (item.id)}
        <DataGroup section="{section}" item="{item}" first="{index === 0}" last="{index === section.data.length - 1}" />
    {/each}
    </div>
</div>
