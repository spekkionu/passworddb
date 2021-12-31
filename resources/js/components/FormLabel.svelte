<script>
    export let required = false;
    export let hideLabel = false;
    export let id = null;
</script>
<label for="{id}" class:sr-only={hideLabel} class:required={required} {...$$restProps}><slot></slot></label>
