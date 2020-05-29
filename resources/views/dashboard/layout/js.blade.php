<script src="/js/vendors.min.js"></script>
<script src="/js/app-menu.js"></script>
<script src="/js/app.js"></script>
<script src="/js/components.js"></script>
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
</script>
@stack('js')
