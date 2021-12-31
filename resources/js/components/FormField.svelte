<script>
    import FormLabel from "./FormLabel.svelte";
    import FormInput from "./FormInput.svelte";

    export let classes = '';
    export let label = '';
    export let help = '';
    export let hideLabel = false;
    export let id = Math.random().toString(36).replace(/[^a-z]+/g, '').substr(0, 5);
    export let field = null;
    export let form = null;
    export let required = false;
    export let value = null;

    $: errors = form && field && form.errors && form.errors[field] ? form.errors[field] : null;
</script>

<div class={classes}>
    <slot name="label">
    {#if label}
        <FormLabel
            {id}
            {required}
            {hideLabel}
        >{label}</FormLabel>
    {/if}
    </slot>
    <div class:mt-1={label && !hideLabel}>
        <slot>
            <FormInput {id} {field} {form} bind:value={value} {...$$restProps} {required} />
        </slot>
    </div>
    <slot name="errors">
        {#if errors}
            <p class="mt-2 text-sm text-red-600" id="{id}-error">{errors}</p>
        {/if}
    </slot>
    <slot name="help">
        {#if help}
            <p class="mt-2 text-sm text-gray-500" id="{id}-description">{help}</p>
        {/if}
    </slot>
</div>
