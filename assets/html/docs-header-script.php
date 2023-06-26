<script type="text/javascript">
    var toggleMenuShortScreen = function(e) {
        var menu = document.getElementById("menu");
        menu.classList.toggle("hidden-short-screen");
        return false;
    };
    var searchResources = function(e) {
        var searcher = document.getElementById("searcher");
        if (searcher.value.length) {
            window.location.href = "<?php echo $path; ?><?php echo \PHPBook\Http\Configuration\Directory::getDocs(); ?>/search" + '?search=' + searcher.value;
        };
        return false;
    };
    var toggleMenu = function(e, query) {
        var element = document.getElementById(query);
        element.style.display = element.style.display === 'none' ? 'block' : 'none';
        return false;
    };
</script>