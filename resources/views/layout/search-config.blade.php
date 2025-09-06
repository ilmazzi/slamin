<script>
window.SearchConfig = {
    apiUrl: '{{ route("search.api") }}',
    searchUrl: '{{ route("search.index") }}',
    csrfToken: '{{ csrf_token() }}'
};
</script>

