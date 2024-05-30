<script src="/storage/assets/js/jquery.min.js"></script>
<script src="/storage/assets/js/bootstrap.bundle.min.js"></script>
<script>
    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
    const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))
</script>