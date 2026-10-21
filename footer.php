<footer>
    <p>
        <?php
        $yyyy = date("Y");
        $datum = ucwords(date('l, d F Y', time()));
        echo "{$datum}<br><span>&copy; copyright {$yyyy} &copy;</span>";
        ?>
        <br>
        <a href="/" target="_blank">Autoverkoop demo</a>
    </p>
</footer>